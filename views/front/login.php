<?php
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email=trim($_POST['email']??'');$pass=$_POST['password']??'';
  if(!$email||!$pass){$error='Veuillez remplir tous les champs';}
  else{try{$s=$pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");$s->execute([$email]);$user=$s->fetch();
    if($user&&password_verify($pass,$user['password'])){
      $_SESSION['user']=['id'=>$user['id'],'firstname'=>$user['firstname'],'lastname'=>$user['lastname'],'email'=>$user['email'],'role'=>$user['role']??'client'];
      $_SESSION['user_id']=$user['id'];$_SESSION['user_name']=$user['firstname'].' '.$user['lastname'];$_SESSION['user_email']=$user['email'];$_SESSION['user_role']=$user['role']??'client';
      $r=$_GET['redirect']??'';
      $url=($r==='admin'||in_array($user['role'],['admin','super_admin']))?BASE_URL.'/admin_splash.php':BASE_URL.'/home';
      echo '<script>window.location.href="'.$url.'";</script>';exit;
    }else{$error='Email ou mot de passe incorrect';}
  }catch(PDOException $e){$error='Une erreur est survenue.';}}
}
?>
<div class="ts-auth-page">
  <div class="ts-auth-card">
    <div class="ts-auth-logo"><a href="<?=BASE_URL?>/home">TECHSTORE</a></div>
    <h2 class="ts-auth-title">Bienvenue</h2>
    <p class="ts-auth-sub">Connectez-vous à votre compte TechStore</p>
    <?php if($error): ?><div class="ts-alert ts-alert-err"><i class="fas fa-exclamation-circle"></i><?=htmlspecialchars($error)?></div><?php endif; ?>
    <form method="POST">
      <div class="ts-fgroup"><label class="ts-label">Adresse email</label>
        <div class="ts-iico"><i class="fas fa-envelope"></i><input type="email" name="email" class="ts-input" placeholder="vous@exemple.com" required value="<?=htmlspecialchars($_POST['email']??'')?>"></div></div>
      <div class="ts-fgroup"><label class="ts-label">Mot de passe</label>
        <div class="ts-iico" style="position:relative"><i class="fas fa-lock"></i>
          <input type="password" name="password" id="pwi" class="ts-input" placeholder="••••••••" required style="padding-right:44px">
          <button type="button" class="ts-pw-eye" onclick="tpw()"><i class="fas fa-eye" id="pwe"></i></button>
        </div></div>
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <label style="font-family:'Times New Roman',Times,serif;font-size:12.5px;font-style:italic;color:var(--txm);display:flex;align-items:center;gap:7px;cursor:pointer"><input type="checkbox" style="accent-color:#ffa07a"> Se souvenir de moi</label>
        <a href="#" style="font-family:'Times New Roman',Times,serif;font-size:12px;font-style:italic;color:var(--txm)">Mot de passe oublié ?</a>
      </div>
      <button type="submit" class="ts-btn ts-btn-p"><i class="fas fa-arrow-right-to-bracket"></i> Se connecter</button>
    </form>
    <div class="ts-auth-sep"><span>ou</span></div>
    <div class="ts-auth-footer">Pas encore de compte ? <a href="<?=BASE_URL?>/register">Créer un compte</a></div>
  </div>
</div>
<script>function tpw(){var i=document.getElementById('pwi'),e=document.getElementById('pwe');i.type=i.type==='password'?'text':'password';e.className=i.type==='password'?'fas fa-eye':'fas fa-eye-slash';}</script>
