<?php
if(!isset($_SESSION['user_id'])){header('Location:'.BASE_URL.'/login');exit;}
$uid=$_SESSION['user_id']; $error=''; $success='';

/* ── Supprimer adresse ── */
if(isset($_GET['delete_address'])){
  try{$s=$pdo->prepare("DELETE FROM addresses WHERE id=? AND user_id=?");$s->execute([(int)$_GET['delete_address'],$uid]);header('Location:'.BASE_URL.'/account#addresses');exit;}
  catch(PDOException $e){$error='Erreur lors de la suppression.';}
}

/* ── Ajouter adresse (sans code postal ni libellé) ── */
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add_address'])){
  $ad = trim($_POST['address']     ?? '');
  $ci = trim($_POST['city']        ?? '');
  $ph = trim($_POST['phone']       ?? '');
  $pc = trim($_POST['postal_code'] ?? '');   // optionnel, peut être vide
  $id = isset($_POST['is_default']) ? 1 : 0;
  if(!$ad || !$ci){
    $error='L\'adresse et la ville sont obligatoires.';
  } else {
    try{
      if($id){ $s=$pdo->prepare("UPDATE addresses SET is_default=0 WHERE user_id=?"); $s->execute([$uid]); }
      $s=$pdo->prepare("INSERT INTO addresses (user_id,name,address,postal_code,city,phone,is_default) VALUES (?,?,?,?,?,?,?)");
      /* name = city pour la compatibilité avec checkout */
      $s->execute([$uid,$ci,$ad,$pc,$ci,$ph,$id]);
      $success='Adresse ajoutée avec succès !';
    } catch(PDOException $e){ $error='Erreur lors de l\'enregistrement. ('.$e->getMessage().')'; }
  }
}

/* ── Mettre à jour le profil ── */
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['update_profile'])){
  $fn = trim($_POST['firstname'] ?? '');
  $ln = trim($_POST['lastname']  ?? '');
  $em = trim($_POST['email']     ?? '');
  $ph = trim($_POST['phone']     ?? '');
  if(!$fn || !$ln || !$em){ $error='Les champs Prénom, Nom et Email sont obligatoires.'; }
  elseif(!filter_var($em,FILTER_VALIDATE_EMAIL)){ $error='Adresse email invalide.'; }
  else {
    try{
      $s=$pdo->prepare("SELECT id FROM users WHERE email=? AND id!=? LIMIT 1");
      $s->execute([$em,$uid]);
      if($s->fetch()){ $error='Cette adresse email est déjà utilisée.'; }
      else {
        $s=$pdo->prepare("UPDATE users SET firstname=?,lastname=?,email=?,phone=? WHERE id=?");
        $s->execute([$fn,$ln,$em,$ph,$uid]);
        $_SESSION['user_name']  = $fn.' '.$ln;
        $_SESSION['user_email'] = $em;
        $success='Profil mis à jour avec succès !';
      }
    } catch(PDOException $e){ $error='Erreur lors de la mise à jour. ('.$e->getMessage().')'; }
  }
}

/* ── Charger les données ── */
try{$s=$pdo->prepare("SELECT * FROM users WHERE id=? LIMIT 1");$s->execute([$uid]);$user=$s->fetch();}
catch(PDOException $e){$user=[];}
try{$s=$pdo->prepare("SELECT * FROM addresses WHERE user_id=? ORDER BY is_default DESC,id DESC");$s->execute([$uid]);$addresses=$s->fetchAll();}
catch(PDOException $e){$addresses=[];}
try{$s=$pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id=?");$s->execute([$uid]);$ocount=$s->fetchColumn();}
catch(PDOException $e){$ocount=0;}
?>

<div class="ts-acc-wrap">
  <h1 class="ts-page-title"><i class="fas fa-circle-user" style="opacity:.8;margin-right:10px;font-size:24px"></i>Mon Compte</h1>

  <?php if($error): ?>
  <div class="ts-acc-alert ts-acc-alert-err"><i class="fas fa-exclamation-circle"></i> <?=htmlspecialchars($error)?></div>
  <?php endif; ?>
  <?php if($success): ?>
  <div class="ts-acc-alert ts-acc-alert-ok"><i class="fas fa-check-circle"></i> <?=htmlspecialchars($success)?></div>
  <?php endif; ?>

  <div class="ts-acc-grid">
    <!-- ── Sidebar ── -->
    <div class="ts-acc-sidebar">
      <div class="ts-user-prof">
        <div class="ts-user-av">
          <?=strtoupper(substr($user['firstname']??'U',0,1))?>
        </div>
        <div class="ts-user-name"><?=htmlspecialchars(($user['firstname']??'').' '.($user['lastname']??''))?></div>
        <div class="ts-user-email"><?=htmlspecialchars($user['email']??'')?></div>
        <span class="ts-user-stat"><i class="fas fa-bag-shopping" style="margin-right:4px"></i><?=$ocount?> commande(s)</span>
      </div>
      <nav class="ts-acc-nav">
        <a class="ts-acc-nav-item on" id="nb-profile" onclick="shPanel('profile',this);return false;" href="#">
          <i class="fas fa-user-pen"></i> Mon Profil
        </a>
        <a class="ts-acc-nav-item" id="nb-addrs" onclick="shPanel('addrs',this);return false;" href="#">
          <i class="fas fa-location-dot"></i> Mes Adresses
          <?php if(count($addresses)>0): ?><span class="ts-acc-nav-badge"><?=count($addresses)?></span><?php endif; ?>
        </a>
        <a class="ts-acc-nav-item" href="<?=BASE_URL?>/orders">
          <i class="fas fa-bag-shopping"></i> Mes Commandes
        </a>
        <a class="ts-acc-nav-item" href="<?=BASE_URL?>/logout" style="color:rgba(248,113,113,.65);">
          <i class="fas fa-right-from-bracket"></i> Déconnexion
        </a>
      </nav>
    </div>

    <!-- ── Panels ── -->
    <div>
      <!-- Panel Profil -->
      <div class="ts-acc-panel on" id="panel-profile">
        <div class="ts-panel-card">
          <div class="ts-panel-head">
            <i class="fas fa-user-pen" style="color:#ffa07a;margin-right:8px"></i>Informations personnelles
          </div>
          <div class="ts-panel-body">
            <form method="POST">
              <input type="hidden" name="update_profile" value="1">
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
                <div class="ts-fgroup">
                  <label class="ts-label">Prénom <span style="color:#ffa07a">*</span></label>
                  <div class="ts-iico"><i class="fas fa-user"></i>
                    <input type="text" name="firstname" class="ts-input" required value="<?=htmlspecialchars($user['firstname']??'')?>">
                  </div>
                </div>
                <div class="ts-fgroup">
                  <label class="ts-label">Nom <span style="color:#ffa07a">*</span></label>
                  <div class="ts-iico"><i class="fas fa-user"></i>
                    <input type="text" name="lastname" class="ts-input" required value="<?=htmlspecialchars($user['lastname']??'')?>">
                  </div>
                </div>
              </div>
              <div class="ts-fgroup">
                <label class="ts-label">Adresse email <span style="color:#ffa07a">*</span></label>
                <div class="ts-iico"><i class="fas fa-envelope"></i>
                  <input type="email" name="email" class="ts-input" required value="<?=htmlspecialchars($user['email']??'')?>">
                </div>
              </div>
              <div class="ts-fgroup">
                <label class="ts-label">Téléphone</label>
                <div class="ts-iico"><i class="fas fa-phone"></i>
                  <input type="tel" name="phone" class="ts-input" placeholder="+237 6XX XXX XXX" value="<?=htmlspecialchars($user['phone']??'')?>">
                </div>
              </div>
              <button type="submit" class="ts-btn ts-btn-p"><i class="fas fa-save"></i> Sauvegarder les modifications</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Panel Adresses -->
      <div class="ts-acc-panel" id="panel-addrs">
        <div class="ts-panel-card">
          <div class="ts-panel-head">
            <i class="fas fa-location-dot" style="color:#ffa07a;margin-right:8px"></i>Mes adresses de livraison
            <span style="font-family:'Times New Roman',Times,serif;font-size:12px;font-style:italic;color:rgba(80,90,130,.5);font-weight:400;margin-left:8px"><?=count($addresses)?> adresse(s)</span>
          </div>
          <div class="ts-panel-body">

            <!-- Liste adresses existantes -->
            <?php if(!empty($addresses)): ?>
            <div class="ts-addrs-grid">
              <?php foreach($addresses as $a): ?>
              <div class="ts-addr-card <?=$a['is_default']?'def':''?>">
                <?php if($a['is_default']): ?>
                <div style="margin-bottom:8px"><span class="ts-badge ts-badge-g"><i class="fas fa-check"></i> Par défaut</span></div>
                <?php endif; ?>
                <div style="font-family:'Times New Roman',Times,serif;font-weight:700;color:#1a1d2e;margin-bottom:6px;font-size:14px">
                  <?=htmlspecialchars($a['city']??$a['name']??'Adresse')?>
                </div>
                <div style="font-family:'Times New Roman',Times,serif;font-size:13px;color:rgba(80,90,130,.6);line-height:1.7">
                  <?=htmlspecialchars($a['address'])?>
                  <?php if(!empty($a['phone'])): ?>
                  <br><i class="fas fa-phone" style="font-size:10px;margin-right:4px;opacity:.5"></i><?=htmlspecialchars($a['phone'])?>
                  <?php endif; ?>
                </div>
                <div style="margin-top:12px">
                  <a href="<?=BASE_URL?>/account?delete_address=<?=$a['id']?>"
                     class="ts-btn ts-btn-g ts-btn-sm"
                     style="border-color:rgba(248,113,113,.2);color:rgba(248,113,113,.7)"
                     onclick="return confirm('Supprimer cette adresse ?')">
                    <i class="fas fa-trash"></i> Supprimer
                  </a>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Formulaire ajouter adresse -->
            <div class="ts-add-addr-form">
              <div class="ts-add-addr-title">
                <i class="fas fa-plus-circle" style="color:#007bff"></i> Ajouter une nouvelle adresse
              </div>
              <form method="POST">
                <input type="hidden" name="add_address" value="1">

                <div class="ts-fgroup">
                  <label class="ts-label">Adresse complète <span style="color:#ffa07a">*</span></label>
                  <div class="ts-iico"><i class="fas fa-map-marker-alt"></i>
                    <input type="text" name="address" class="ts-input" placeholder="Rue, quartier, numéro…" required>
                  </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:13px">
                  <div class="ts-fgroup">
                    <label class="ts-label">Ville <span style="color:#ffa07a">*</span></label>
                    <div class="ts-iico"><i class="fas fa-city"></i>
                      <input type="text" name="city" class="ts-input" placeholder="Douala, Yaoundé…" required>
                    </div>
                  </div>
                  <div class="ts-fgroup">
                    <label class="ts-label">Code postal</label>
                    <div class="ts-iico"><i class="fas fa-map-pin"></i>
                      <input type="text" name="postal_code" class="ts-input" placeholder="Ex: 00237">
                    </div>
                  </div>
                </div>
                <div class="ts-fgroup" style="margin-top:13px">
                  <label class="ts-label">Téléphone</label>
                  <div class="ts-iico"><i class="fas fa-phone"></i>
                    <input type="tel" name="phone" class="ts-input" placeholder="+237 6XX XXX XXX">
                  </div>
                </div>

                <label style="display:flex;align-items:center;gap:9px;font-family:'Times New Roman',Times,serif;font-size:13px;color:rgba(80,90,130,.6);cursor:pointer;margin:14px 0 18px;padding:12px 14px;border:1px solid rgba(0,123,255,.15);border-radius:10px;transition:all .2s" onmouseover="this.style.borderColor='rgba(0,123,255,.35)'" onmouseout="this.style.borderColor='rgba(0,123,255,.15)'">
                  <input type="checkbox" name="is_default" style="accent-color:#007bff;width:16px;height:16px">
                  <span>Définir comme adresse par défaut</span>
                </label>

                <button type="submit" class="ts-btn ts-btn-b"><i class="fas fa-plus"></i> Ajouter l'adresse</button>
              </form>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* ── Account extras ── */
.ts-acc-alert{display:flex;align-items:center;gap:10px;padding:13px 18px;border-radius:12px;font-family:'Times New Roman',Times,serif;font-size:13.5px;margin-bottom:18px;max-width:720px}
.ts-acc-alert-err{background:rgba(248,113,113,.08);border:1px solid rgba(248,113,113,.25);color:#dc2626}
.ts-acc-alert-ok{background:rgba(74,222,128,.08);border:1px solid rgba(74,222,128,.25);color:#16a34a}
.ts-acc-nav-badge{margin-left:auto;background:rgba(0,123,255,.15);color:#3d9cff;font-size:10px;font-weight:700;font-family:'Times New Roman',Times,serif;padding:2px 8px;border-radius:20px}
.ts-add-addr-form{background:rgba(0,123,255,.04);border:1px solid rgba(0,123,255,.14);border-radius:16px;padding:22px;margin-top:22px}
.ts-add-addr-title{font-family:'Times New Roman',Times,serif;font-size:14px;font-weight:700;color:#1a1d2e;margin-bottom:18px;display:flex;align-items:center;gap:8px}
</style>

<script>
function shPanel(id,el){
  event.preventDefault();
  document.querySelectorAll('.ts-acc-panel').forEach(function(p){p.classList.remove('on');});
  document.querySelectorAll('.ts-acc-nav-item').forEach(function(a){a.classList.remove('on');});
  document.getElementById('panel-'+id).classList.add('on');
  el.classList.add('on');
}
</script>
