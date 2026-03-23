<?php
if(isset($_SESSION['user'])){header('Location:'.BASE_URL.(in_array($_SESSION['user']['role']??'',['admin','super_admin'])?'/admin':'/account'));exit;}
$error='';$success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $fn=trim($_POST['firstname']??'');$ln=trim($_POST['lastname']??'');$em=trim($_POST['email']??'');$pw=$_POST['password']??'';$cp=$_POST['confirm_password']??'';$tc=isset($_POST['accept_terms']);
  if(!$fn||!$ln||!$em||!$pw||!$cp){$error='Veuillez remplir tous les champs';}
  elseif(strlen($pw)<8){$error='Mot de passe : 8 caractères minimum';}
  elseif($pw!==$cp){$error='Les mots de passe ne correspondent pas';}
  elseif(!$tc){$error='Vous devez accepter les conditions générales';}
  elseif(!filter_var($em,FILTER_VALIDATE_EMAIL)){$error='Adresse email invalide';}
  else{try{$s=$pdo->prepare("SELECT id FROM users WHERE email=? LIMIT 1");$s->execute([$em]);
    if($s->fetch()){$error='Cette adresse email est déjà utilisée';}
    else{$h=password_hash($pw,PASSWORD_DEFAULT);$s=$pdo->prepare("INSERT INTO users (firstname,lastname,email,password,role,created_at) VALUES (?,?,?,?,'client',NOW())");$s->execute([$fn,$ln,$em,$h]);$success='Compte créé avec succès !';}
  }catch(PDOException $e){$error='Une erreur est survenue.';}}
}
?>
<div class="ts-auth-page">
  <div class="ts-auth-card" style="max-width:500px">
    <div class="ts-auth-logo"><a href="<?=BASE_URL?>/home">TECHSTORE</a></div>
    <h2 class="ts-auth-title">Créer un compte</h2>
    <p class="ts-auth-sub">Rejoignez TechStore et commencez vos achats</p>
    <?php if($error): ?><div class="ts-alert ts-alert-err"><i class="fas fa-exclamation-circle"></i><?=htmlspecialchars($error)?></div><?php endif; ?>
    <?php if($success): ?><div class="ts-alert ts-alert-ok"><i class="fas fa-check-circle"></i><?=htmlspecialchars($success)?> Redirection…</div><script>setTimeout(()=>window.location.href='<?=BASE_URL?>/login',2800)</script><?php endif; ?>
    <?php if(!$success): ?>
    <form method="POST">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:13px">
        <div class="ts-fgroup" style="margin-bottom:0"><label class="ts-label">Prénom</label><div class="ts-iico"><i class="fas fa-user"></i><input type="text" name="firstname" class="ts-input" placeholder="Thierry" required value="<?=htmlspecialchars($_POST['firstname']??'')?>"></div></div>
        <div class="ts-fgroup" style="margin-bottom:0"><label class="ts-label">Nom</label><div class="ts-iico"><i class="fas fa-user"></i><input type="text" name="lastname" class="ts-input" placeholder="Armel" required value="<?=htmlspecialchars($_POST['lastname']??'')?>"></div></div>
      </div>
      <div class="ts-fgroup" style="margin-top:13px"><label class="ts-label">Adresse email</label><div class="ts-iico"><i class="fas fa-envelope"></i><input type="email" name="email" class="ts-input" placeholder="vous@exemple.com" required value="<?=htmlspecialchars($_POST['email']??'')?>"></div></div>
      <div class="ts-fgroup"><label class="ts-label">Mot de passe</label>
        <div class="ts-iico" style="position:relative"><i class="fas fa-lock"></i><input type="password" name="password" id="p1" class="ts-input" placeholder="Min. 8 caractères" required style="padding-right:44px">
        <button type="button" class="ts-pw-eye" onclick="tpw('p1','e1')"><i class="fas fa-eye" id="e1"></i></button></div></div>
      <div class="ts-fgroup"><label class="ts-label">Confirmer le mot de passe</label>
        <div class="ts-iico" style="position:relative"><i class="fas fa-lock"></i><input type="password" name="confirm_password" id="p2" class="ts-input" placeholder="Répéter le mot de passe" required style="padding-right:44px">
        <button type="button" class="ts-pw-eye" onclick="tpw('p2','e2')"><i class="fas fa-eye" id="e2"></i></button></div></div>
      <label style="display:flex;align-items:flex-start;gap:9px;font-family:'Times New Roman',Times,serif;font-size:12.5px;font-style:italic;color:var(--txm);cursor:pointer;margin-bottom:20px">
        <input type="checkbox" name="accept_terms" style="accent-color:#ffa07a;margin-top:3px;flex-shrink:0" <?=isset($_POST['accept_terms'])?'checked':''?>>
        <span>J'accepte les <a href="#" style="color:var(--blue-l)">conditions générales</a> et la <a href="#" style="color:var(--blue-l)">politique de confidentialité</a></span>
      </label>
      <button type="submit" class="ts-btn ts-btn-p"><i class="fas fa-user-plus"></i> Créer mon compte</button>
    </form>
    <?php endif; ?>
    <div class="ts-auth-sep"><span>ou</span></div>
    <div class="ts-auth-footer">Déjà inscrit ? <a href="<?=BASE_URL?>/login">Se connecter</a></div>
  </div>
</div>
<script>function tpw(id,eid){var i=document.getElementById(id),e=document.getElementById(eid);i.type=i.type==='password'?'text':'password';e.className=i.type==='password'?'fas fa-eye':'fas fa-eye-slash';}</script>
