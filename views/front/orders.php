<?php
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer toutes les commandes de l'utilisateur
try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $orders = [];
}

// Fonction pour récupérer les détails d'une commande
function getOrderDetails($pdo, $order_id) {
    try {
        $stmt = $pdo->prepare("
            SELECT oi.*, p.name as product_name, p.image as product_image 
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$order_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}
?>

<div class="orders-page py-5">
    <div class="container">
        <h1 class="mb-4 fw-bold"><i class="bi bi-bag me-2"></i>Mes Commandes</h1>
        
        <?php if (empty($orders)): ?>
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-bag fs-1 text-muted"></i>
                    <h4 class="mt-3">Aucune commande</h4>
                    <p class="text-muted">Vous n'avez pas encore passé de commande.</p>
                    <a href="<?= BASE_URL ?>/catalogue" class="btn btn-primary mt-2">
                        <i class="bi bi-shop me-2"></i>Découvrir nos produits
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($orders as $order): ?>
                    <?php $order_items = getOrderDetails($pdo, $order['id']); ?>
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white py-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                    <div>
                                        <h5 class="mb-1 fw-bold">Commande #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></h5>
                                        <p class="text-muted mb-0 small">
                                            <i class="bi bi-calendar me-1"></i>
                                            Passée le <?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?>
                                        </p>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <?php
                                        $status_class = [
                                            'en_attente' => 'warning',
                                            'confirme' => 'info',
                                            'en_preparation' => 'primary',
                                            'expedie' => 'info',
                                            'livre' => 'success',
                                            'annule' => 'danger'
                                        ];
                                        $status_labels = [
                                            'en_attente' => 'En attente',
                                            'confirme' => 'Confirmé',
                                            'en_preparation' => 'En préparation',
                                            'expedie' => 'Expédié',
                                            'livre' => 'Livré',
                                            'annule' => 'Annulé'
                                        ];
                                        ?>
                                        <span class="badge bg-<?= $status_class[$order['status']] ?? 'secondary' ?> fs-6">
                                            <?= $status_labels[$order['status']] ?? $order['status'] ?>
                                        </span>
                                        <span class="fw-bold text-primary">
                                            <?= number_format($order['total_amount'], 2, ',', ' ') ?> FC
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="mb-3">Articles commandés:</h6>
                                <div class="row g-3">
                                    <?php foreach ($order_items as $item): ?>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="d-flex align-items-center p-2 border rounded">
                                                <div class="me-3">
                                                    <?php if ($item['product_image']): ?>
                                                        <img src="<?= htmlspecialchars($item['product_image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                            <i class="bi bi-image text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="mb-0 fw-medium"><?= htmlspecialchars($item['product_name']) ?></p>
                                                    <p class="mb-0 text-muted small">
                                                        <?= $item['quantity'] ?> x <?= number_format($item['unit_price'], 2, ',', ' ') ?> FC
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <?php if ($order['shipping_address'] || $order['shipping_city']): ?>
                                    <hr class="my-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6><i class="bi bi-truck me-2"></i>Adresse de livraison:</h6>
                                            <p class="text-muted mb-0">
                                                <?= nl2br(htmlspecialchars($order['shipping_address'])) ?><br>
                                                <?= htmlspecialchars($order['shipping_postal_code']) ?> <?= htmlspecialchars($order['shipping_city']) ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.orders-page {
    background-color: #f8f9fa;
    min-height: calc(100vh - 76px);
}

.orders-page .card {
    border-radius: 12px;
    border: none;
}

.orders-page .badge {
    padding: 0.5em 0.75em;
}
</style>
