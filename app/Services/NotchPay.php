<?php
/**
 * TECHSTORE — Service NotchPay
 * Gestion des paiements via l'API NotchPay
 */
class NotchPay {

    private $publicKey;
    private $secretKey;
    private $apiUrl;

    public function __construct() {
        $this->publicKey = NOTCHPAY_PUBLIC_KEY;
        $this->secretKey = NOTCHPAY_SECRET_KEY;
        $this->apiUrl    = NOTCHPAY_API_URL;
    }

    /**
     * Initialiser un paiement NotchPay
     * Retourne l'URL de paiement ou false en cas d'erreur
     */
    public function initPayment(array $data): array {
        $payload = [
            'amount'      => $data['amount'],
            'currency'    => 'XAF',
            'email'       => $data['email'],
            'name'        => $data['name'],
            'phone'       => $data['phone'] ?? '',
            'reference'   => $data['reference'],
            'callback'    => $data['callback_url'],
            'description' => $data['description'] ?? 'Commande TechStore #'.$data['reference'],
        ];

        $response = $this->request('POST', '/payments/initialize', $payload);

        error_log('[NotchPay] initPayment response: ' . json_encode($response));

        // authorization_url est à la RACINE de la réponse (confirmé par test API)
        $paymentUrl = $response['authorization_url']
                   ?? $response['transaction']['authorization_url']
                   ?? null;

        $transactionRef = $response['transaction']['reference']
                       ?? $response['reference']
                       ?? null;

        if ($paymentUrl && $transactionRef) {
            return [
                'success'         => true,
                'payment_url'     => $paymentUrl,
                'transaction_ref' => $transactionRef,
            ];
        }

        // Fallback : Payment initialized sans URL explicite
        if (isset($response['status']) && $response['status'] === 'Accepted') {
            $ref = $transactionRef ?? $data['reference'];
            return [
                'success'         => true,
                'payment_url'     => 'https://pay.notchpay.co/' . $ref,
                'transaction_ref' => $ref,
            ];
        }

        return [
            'success' => false,
            'message' => $response['message'] ?? 'Erreur lors de l\'initialisation du paiement.',
        ];
    }

    public function verifyPayment(string $reference): array {
        $response = $this->request('GET', '/payments/' . $reference);

        if (isset($response['transaction'])) {
            $t = $response['transaction'];
            return [
                'success'   => true,
                'status'    => $t['status'],          // complete, failed, pending...
                'amount'    => $t['amount'],
                'reference' => $t['reference'],
            ];
        }

        return [
            'success' => false,
            'message' => $response['message'] ?? 'Paiement introuvable.',
        ];
    }

    /**
     * Effectuer une requête HTTP vers l'API NotchPay
     */
    private function request(string $method, string $endpoint, array $body = []): array {
        $url = $this->apiUrl . $endpoint;

        $headers = [
            'Authorization: ' . $this->publicKey,
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,     $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT,        30);

        // ── Fix SSL pour XAMPP local ──
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        // ── Fix DNS pour XAMPP local ──
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $result   = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        // curl_close() déprécié en PHP 8+ — la connexion se ferme automatiquement

        if ($error) {
            error_log('[NotchPay] cURL error: ' . $error);
            return ['message' => 'Erreur réseau : ' . $error];
        }

        if (empty($result)) {
            error_log('[NotchPay] Empty response, HTTP: ' . $httpCode);
            return ['message' => 'Réponse vide de NotchPay (HTTP ' . $httpCode . ').'];
        }

        $decoded = json_decode($result, true);
        if (!$decoded) {
            error_log('[NotchPay] Invalid JSON response: ' . $result);
            return ['message' => 'Réponse invalide de NotchPay.'];
        }

        return $decoded;
    }
}