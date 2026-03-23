<?php
/**
 * TECHSTORE — Callback NotchPay
 * Page appelée après le paiement (succès ou échec)
 */

require_once APP_PATH . '/Services/NotchPay.php';

$oid    = intval($_GET['order_id']    ?? 0);
$cid    = intval($_GET['commande_id'] ?? 0);
$status = $_GET['status']             ?? '';

$success = false;
$message = '';

if (!$oid) {
    header('Location: ' . BASE_URL . '/home');
    exit;
}

// Récupérer la commande
try {
    $s = $pdo->prepare("SELECT * FROM orders WHERE id=? AND user_id=? LIMIT 1");
    $s->execute([$oid, $_SESSION['user_id'] ?? 0]);
    $order = $s->fetch();
} catch (PDOException $e) { $order = null; }

if (!$order) {
    header('Location: ' . BASE_URL . '/home');
    exit;
}

// Récupérer la transaction NotchPay depuis la table paiements
try {
    $lookupId = $cid ?: $oid; // priorité à commande_id
    $s = $pdo->prepare("SELECT * FROM paiements WHERE commande_id=? ORDER BY date_paiement DESC LIMIT 1");
    $s->execute([$lookupId]);
    $paiement = $s->fetch();
} catch (PDOException $e) { $paiement = null; }

if ($paiement && $paiement['transaction_notchpay']) {
    $notchpay = new NotchPay();
    $verify   = $notchpay->verifyPayment($paiement['transaction_notchpay']);

    if ($verify['success'] && $verify['status'] === 'complete') {
        $pdo->prepare("UPDATE paiements SET statut='complete' WHERE commande_id=?")
            ->execute([$lookupId]);
        $pdo->prepare("UPDATE orders SET payment_status='payé', status='confirmé' WHERE id=?")
            ->execute([$oid]);
        $pdo->prepare("UPDATE commandes SET statut='confirmé' WHERE id_commande=?")
            ->execute([$cid ?: $oid]);
        $success = true;

    } elseif ($verify['success'] && $verify['status'] === 'failed') {
        $pdo->prepare("UPDATE paiements SET statut='failed' WHERE commande_id=?")
            ->execute([$lookupId]);
        $pdo->prepare("UPDATE orders SET payment_status='échoué', status='annulé' WHERE id=?")
            ->execute([$oid]);
        $pdo->prepare("UPDATE commandes SET statut='annulé' WHERE id_commande=?")
            ->execute([$cid ?: $oid]);
        $message = 'Le paiement a échoué. Veuillez réessayer.';

    } elseif ($verify['success'] && $verify['status'] === 'cancelled') {
        $pdo->prepare("UPDATE paiements SET statut='cancelled' WHERE commande_id=?")
            ->execute([$lookupId]);
        $pdo->prepare("UPDATE orders SET payment_status='échoué', status='annulé' WHERE id=?")
            ->execute([$oid]);
        $message = 'Paiement annulé.';

    } else {
        $success = true;
        $message = 'Paiement en cours de vérification...';
    }
}
?>

<div style="max-width:580px;margin:60px auto;padding:0 20px;text-align:center;font-family:'Times New Roman',Times,serif">

  <?php if($success): ?>
  <!-- ── Succès ── -->
  <div style="background:#fff;border:1px solid rgba(0,123,255,.13);border-radius:24px;padding:48px 36px;box-shadow:0 8px 40px rgba(0,123,255,.1)">
    <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#16a34a,#4ade80);display:flex;align-items:center;justify-content:center;font-size:28px;color:#fff;margin:0 auto 20px;box-shadow:0 8px 24px rgba(74,222,128,.3)">
      <i class="fas fa-check"></i>
    </div>
    <h2 style="font-family:'Times New Roman',Times,serif,'Inter',sans-serif;font-size:26px;font-weight:800;color:#1a1d2e;margin-bottom:10px">Commande confirmée !</h2>
    <p style="color:rgba(80,90,130,.7);font-size:14px;margin-bottom:8px">
      Votre commande <strong style="color:#4ade80">#<?= $oid ?></strong> a bien été payée.
    </p>
    <p style="color:rgba(80,90,130,.6);font-size:13px;margin-bottom:28px">Notre équipe vous contactera pour la livraison sous 24–48h.</p>
    <div style="display:flex;gap:13px;justify-content:center;flex-wrap:wrap">
      <a href="<?= BASE_URL ?>/orders" style="display:inline-flex;align-items:center;gap:8px;padding:12px 22px;border-radius:12px;background:linear-gradient(135deg,#0056b3,#007bff);color:#fff;text-decoration:none;font-weight:600;font-size:13.5px">
        <i class="fas fa-bag-shopping"></i> Voir mes commandes
      </a>
      <a href="<?= BASE_URL ?>/catalogue" style="display:inline-flex;align-items:center;gap:8px;padding:12px 22px;border-radius:12px;background:rgba(0,123,255,.08);border:1px solid rgba(0,123,255,.2);color:#007bff;text-decoration:none;font-weight:600;font-size:13.5px">
        <i class="fas fa-shop"></i> Continuer mes achats
      </a>
    </div>
  </div>
  <script>localStorage.removeItem('techstore_cart'); if(window.updateCartCount) window.updateCartCount();</script>

  <?php else: ?>
  <!-- ── Échec ── -->
  <div style="background:#fff;border:1px solid rgba(248,113,113,.2);border-radius:24px;padding:48px 36px;box-shadow:0 8px 40px rgba(248,113,113,.08)">
    <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#dc2626,#f87171);display:flex;align-items:center;justify-content:center;font-size:28px;color:#fff;margin:0 auto 20px">
      <i class="fas fa-times"></i>
    </div>
    <h2 style="font-family:'Times New Roman',Times,serif,'Inter',sans-serif;font-size:24px;font-weight:800;color:#1a1d2e;margin-bottom:10px">Paiement échoué</h2>
    <p style="color:rgba(80,90,130,.7);font-size:14px;margin-bottom:28px"><?= htmlspecialchars($message) ?></p>
    <div style="display:flex;gap:13px;justify-content:center;flex-wrap:wrap">
      <a href="<?= BASE_URL ?>/checkout" style="display:inline-flex;align-items:center;gap:8px;padding:12px 22px;border-radius:12px;background:linear-gradient(135deg,#e07050,#ffa07a);color:#fff;text-decoration:none;font-weight:600;font-size:13.5px">
        <i class="fas fa-redo"></i> Réessayer
      </a>
      <a href="<?= BASE_URL ?>/home" style="display:inline-flex;align-items:center;gap:8px;padding:12px 22px;border-radius:12px;background:rgba(0,123,255,.08);border:1px solid rgba(0,123,255,.2);color:#007bff;text-decoration:none;font-weight:600;font-size:13.5px">
        <i class="fas fa-home"></i> Accueil
      </a>
    </div>
  </div>
  <?php endif; ?>

</div>