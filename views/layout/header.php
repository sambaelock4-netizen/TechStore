<?php
/**
 * ==================================================================================
 * TECHSTORE — Entête de Mise en Page (Header Layout)
 * ==================================================================================
 * Ce fichier définit la structure commune du haut de page :
 * 1. Métadonnées HTML, inclusions CSS (style.css, FontAwesome).
 * 2. Navigation principale (Desktop & Mobile).
 * 3. Barre de recherche temps réel et menu utilisateur.
 * 4. Gestion dynamique du panier (compteur).
 * ==================================================================================
 */

$isLogged = isset($_SESSION['user']);
$userName = $isLogged ? ($_SESSION['user']['firstname'] ?? 'Mon compte') : null;
$isAdmin  = $isLogged && in_array($_SESSION['user']['role'] ?? '', ['admin','super_admin']);
$curPath  = $_SERVER['REQUEST_URI'] ?? '';
function tsAct($p){ global $curPath; return strpos($curPath,$p)!==false?'active':''; }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($title) ? htmlspecialchars($title) : 'TechStore — Boutique High-Tech Cameroun' ?></title>
<meta name="description" content="TechStore - Composants, accessoires et matériel informatique aux meilleurs prix. Livraison rapide au Cameroun.">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Times New Roman is a system font — no external import needed -->
<link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/style.css">
</head>
<body>

<header class="ts-nav" id="tsNav">
  <div class="ts-nav-inner">
    <a href="<?= BASE_URL ?>/home" class="ts-logo">TECHSTORE</a>

    <form action="<?= BASE_URL ?>/catalogue" method="GET" class="ts-search">
      <i class="fas fa-search"></i>
      <input type="text" name="q" placeholder="Rechercher un produit, une marque…"
             value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" autocomplete="off">
      <button type="submit"><i class="fas fa-arrow-right" style="font-size:11px"></i></button>
    </form>

    <nav class="ts-nav-links" id="navLinks">
      <a href="<?= BASE_URL ?>/home"      class="ts-nav-link <?= tsAct('/home') ?>"><i class="fas fa-house"></i><span>Accueil</span></a>
      <a href="<?= BASE_URL ?>/catalogue" class="ts-nav-link <?= (tsAct('/catalogue')||tsAct('/product'))&&!tsAct('/admin')?'active':'' ?>"><i class="fas fa-grid-2"></i><span>Catalogue</span></a>

      <?php if($isLogged): ?>
      <div class="ts-drop" id="accDrop">
        <button class="ts-nav-link ts-drop-toggle <?= tsAct('/account')||tsAct('/orders')?'active':'' ?>" onclick="toggleDrop();return false;">
          <i class="fas fa-circle-user"></i><span><?= htmlspecialchars($userName) ?></span>
          <i class="fas fa-chevron-down" style="font-size:9px;opacity:.5;margin-left:2px"></i>
        </button>
        <div class="ts-drop-menu" id="dropMenu">
          <div class="ts-drop-head">
            <div class="ts-drop-name"><?= htmlspecialchars(($_SESSION['user']['firstname']??'').' '.($_SESSION['user']['lastname']??'')) ?></div>
            <div class="ts-drop-email"><?= htmlspecialchars($_SESSION['user']['email']??'') ?></div>
          </div>
          <a href="<?= BASE_URL ?>/account" class="ts-drop-item"><i class="fas fa-user-pen"></i> Mon profil</a>
          <a href="<?= BASE_URL ?>/orders"  class="ts-drop-item"><i class="fas fa-bag-shopping"></i> Mes commandes</a>
          <a href="<?= BASE_URL ?>/cart"    class="ts-drop-item"><i class="fas fa-cart-shopping"></i> Mon panier</a>
          <?php if($isAdmin): ?>
          <div class="ts-drop-sep"></div>
          <a href="<?= BASE_URL ?>/admin" class="ts-drop-item" style="color:#6b8cff"><i class="fas fa-shield-halved"></i> Administration</a>
          <?php endif; ?>
          <div class="ts-drop-sep"></div>
          <a href="<?= BASE_URL ?>/logout" class="ts-drop-item ts-drop-out"><i class="fas fa-right-from-bracket"></i> Déconnexion</a>
        </div>
      </div>
      <?php else: ?>
      <a href="<?= BASE_URL ?>/login"    class="ts-nav-link <?= tsAct('/login') ?>"><i class="fas fa-arrow-right-to-bracket"></i><span>Connexion</span></a>
      <a href="<?= BASE_URL ?>/register" class="ts-btn ts-btn-p" style="padding:8px 17px;font-size:13px;border-radius:20px"><i class="fas fa-user-plus"></i> S'inscrire</a>
      <?php endif; ?>

      <a href="<?= BASE_URL ?>/cart" class="ts-cart-btn">
        <i class="fas fa-cart-shopping"></i>
        <span class="d-none d-lg-inline">Panier</span>
        <span class="ts-cart-count" id="headerCartCount" style="display:none">0</span>
      </a>
    </nav>

    <div class="ts-mob-actions">
      <button onclick="toggleMobSearch()" class="ts-mob-toggle"><i class="fas fa-search"></i></button>
      <a href="<?= BASE_URL ?>/cart" class="ts-mob-cart">
        <i class="fas fa-cart-shopping"></i>
        <span class="ts-cart-count" id="mobCartCount" style="display:none">0</span>
      </a>
      <button onclick="toggleNav()" class="ts-mob-toggle" id="navToggle"><i class="fas fa-bars" id="navIcon"></i></button>
    </div>
  </div>
  <div id="mobSearch" style="display:none" class="ts-mob-search">
    <form action="<?= BASE_URL ?>/catalogue" method="GET" class="ts-search" style="max-width:100%;border-radius:0;border-left:none;border-right:none">
      <i class="fas fa-search"></i>
      <input type="text" name="q" placeholder="Rechercher…" autocomplete="off" autofocus>
      <button type="submit"><i class="fas fa-arrow-right" style="font-size:11px"></i></button>
    </form>
  </div>
</header>

<?php if(!empty($_SESSION['flash'])): ?>
<div style="position:fixed;top:74px;right:18px;z-index:9000;max-width:370px;width:calc(100% - 36px)">
  <div class="ts-flash ts-flash-<?= htmlspecialchars($_SESSION['flash']['type']??'inf') ?>">
    <i class="fas fa-<?= $_SESSION['flash']['type']==='ok'?'check-circle':'info-circle' ?>"></i>
    <span><?= htmlspecialchars($_SESSION['flash']['msg']??'') ?></span>
    <button onclick="this.closest('div').parentElement.remove()" style="background:none;border:none;color:inherit;margin-left:auto;cursor:pointer"><i class="fas fa-times"></i></button>
  </div>
</div>
<?php unset($_SESSION['flash']); endif; ?>

<div id="tsToast" class="ts-toast" style="display:none">
  <i class="fas fa-cart-check" style="color:#4ade80;font-size:18px;flex-shrink:0"></i>
  <span id="tsToastMsg">Produit ajouté</span>
</div>
