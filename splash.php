<?php
require_once 'config/constants.php';
$delay = 3;
$url   = BASE_URL . '/home';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TechStore — Chargement</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%;overflow:hidden}
body{
  font-family:'Times New Roman',Times,Georgia,serif;
  background:#0d1520;
  display:flex;align-items:center;justify-content:center;min-height:100vh;
  position:relative;
}

/* ── Fond animé ── */
.sp-bg{position:fixed;inset:0;z-index:0;overflow:hidden;}
.sp-bg::before{
  content:'';position:absolute;
  width:800px;height:800px;border-radius:50%;
  background:radial-gradient(circle,rgba(0,123,255,.15) 0%,transparent 65%);
  top:-300px;left:-300px;
  animation:bgPulse 7s ease-in-out infinite;
}
.sp-bg::after{
  content:'';position:absolute;
  width:700px;height:700px;border-radius:50%;
  background:radial-gradient(circle,rgba(255,160,122,.13) 0%,transparent 65%);
  bottom:-200px;right:-200px;
  animation:bgPulse 7s ease-in-out infinite 1s;
}
@keyframes bgPulse{0%,100%{transform:scale(1);opacity:.6}50%{transform:scale(1.2);opacity:1}}

/* Grille de points */
.sp-grid{
  position:fixed;inset:0;z-index:0;
  background-image:radial-gradient(circle,rgba(0,123,255,.18) 1px,transparent 1px);
  background-size:36px 36px;opacity:.35;
}

/* Rayon diagonal */
.sp-ray{
  position:fixed;top:-200px;left:50%;
  width:600px;height:800px;z-index:1;pointer-events:none;
  background:linear-gradient(180deg,rgba(0,123,255,.07) 0%,transparent 60%);
  transform:translateX(-50%) rotate(-15deg);
  animation:rayPulse 5s ease-in-out infinite;
}
@keyframes rayPulse{0%,100%{opacity:.4}50%{opacity:.9}}

/* Particules flottantes */
.sp-particle{
  position:fixed;border-radius:50%;pointer-events:none;z-index:2;
  animation:partRise linear infinite;
}
@keyframes partRise{
  0%{transform:translateY(100vh) scale(0);opacity:0}
  10%{opacity:1}90%{opacity:.5}
  100%{transform:translateY(-10vh) scale(.4);opacity:0}
}

/* ── Contenu ── */
.sp-wrap{
  position:relative;z-index:10;text-align:center;
  padding:52px 36px;width:100%;max-width:520px;
  animation:spIn .9s cubic-bezier(.22,1,.36,1) both;
}
@keyframes spIn{from{opacity:0;transform:translateY(40px)}to{opacity:1;transform:translateY(0)}}

/* Icône logo */
.sp-icon-ring{
  position:relative;width:128px;height:128px;margin:0 auto 34px;
  animation:iconFloat 3.5s ease-in-out infinite;
}
@keyframes iconFloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}

.sp-icon-glow{
  position:absolute;inset:-24px;border-radius:50%;
  background:radial-gradient(circle,rgba(0,123,255,.28) 0%,rgba(255,160,122,.14) 50%,transparent 70%);
  animation:glowPulse 2.5s ease-in-out infinite;
}
@keyframes glowPulse{0%,100%{transform:scale(1);opacity:.5}50%{transform:scale(1.25);opacity:1}}

.sp-icon-ring-outer{
  position:absolute;inset:-6px;border-radius:50%;
  border:1.5px solid rgba(0,123,255,.35);
  animation:ringRotate 8s linear infinite;
}
@keyframes ringRotate{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}

.sp-icon-ring-inner{
  position:absolute;inset:0;border-radius:50%;
  background:linear-gradient(135deg,#0d1520,#162038);
  display:flex;align-items:center;justify-content:center;
  border:1px solid rgba(255,255,255,.08);
  box-shadow:0 0 40px rgba(0,123,255,.25), inset 0 1px 0 rgba(255,255,255,.08);
}

.sp-icon-ring-inner i{
  font-size:42px;
  background:linear-gradient(135deg,#007bff,#ffa07a);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}

/* Orbit dot */
.sp-orbit{
  position:absolute;inset:-14px;border-radius:50%;
  animation:orbitSpin 3s linear infinite;
}
@keyframes orbitSpin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
.sp-orbit-dot{
  position:absolute;top:0;left:50%;
  width:8px;height:8px;border-radius:50%;
  transform:translateX(-50%);
  background:linear-gradient(135deg,#007bff,#ffa07a);
  box-shadow:0 0 12px rgba(0,123,255,.8);
}

/* Titre */
.sp-brand{
  font-family:'Times New Roman',Times,serif;
  font-size:36px;font-weight:900;
  background:linear-gradient(135deg,#007bff,#0056b3,#ffa07a);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
  letter-spacing:.04em;margin-bottom:8px;
  animation:spIn .9s cubic-bezier(.22,1,.36,1) .1s both;
}
.sp-tagline{
  font-family:'Times New Roman',Times,serif;
  font-size:13px;color:rgba(180,200,255,.55);
  letter-spacing:.2em;text-transform:uppercase;margin-bottom:44px;
  animation:spIn .9s cubic-bezier(.22,1,.36,1) .2s both;
}

/* Barre de progression */
.sp-progress-wrap{
  margin:0 auto 28px;width:260px;
  animation:spIn .9s cubic-bezier(.22,1,.36,1) .3s both;
}
.sp-progress-track{
  height:3px;background:rgba(255,255,255,.07);
  border-radius:10px;overflow:hidden;margin-bottom:12px;
}
.sp-progress-bar{
  height:100%;border-radius:10px;
  background:linear-gradient(90deg,#007bff,#ffa07a);
  animation:progressFill <?= $delay ?>s cubic-bezier(.4,0,.2,1) both;
  box-shadow:0 0 12px rgba(0,123,255,.6);
}
@keyframes progressFill{from{width:0}to{width:100%}}
.sp-progress-text{
  font-family:'Times New Roman',Times,serif;
  font-size:11px;color:rgba(180,200,255,.45);
  letter-spacing:.15em;text-transform:uppercase;
}

/* Points de chargement */
.sp-dots{display:flex;justify-content:center;gap:6px;animation:spIn .9s cubic-bezier(.22,1,.36,1) .4s both;}
.sp-dot{
  width:6px;height:6px;border-radius:50%;
  background:rgba(0,123,255,.4);
  animation:dotBounce 1.4s ease-in-out infinite;
}
.sp-dot:nth-child(1){animation-delay:0s;background:rgba(0,123,255,.7);}
.sp-dot:nth-child(2){animation-delay:.2s;background:rgba(255,160,122,.6);}
.sp-dot:nth-child(3){animation-delay:.4s;background:rgba(0,123,255,.5);}
@keyframes dotBounce{0%,80%,100%{transform:scale(1);opacity:.5}40%{transform:scale(1.5);opacity:1}}

/* Features */
.sp-features{
  display:flex;justify-content:center;gap:28px;margin-top:36px;flex-wrap:wrap;
  animation:spIn .9s cubic-bezier(.22,1,.36,1) .5s both;
}
.sp-feature{
  display:flex;align-items:center;gap:7px;
  font-family:'Times New Roman',Times,serif;
  font-size:11px;color:rgba(180,200,255,.45);
}
.sp-feature i{font-size:13px;color:rgba(0,123,255,.6);}
</style>
</head>
<body>

<div class="sp-bg"></div>
<div class="sp-grid"></div>
<div class="sp-ray"></div>

<!-- Particules -->
<script>
for(let i=0;i<16;i++){
  const p=document.createElement('div');
  p.className='sp-particle';
  const size=Math.random()*4+2;
  const isOrange=Math.random()>.5;
  p.style.cssText=`width:${size}px;height:${size}px;left:${Math.random()*100}%;bottom:-${size}px;
    background:${isOrange?'rgba(255,160,122,':'rgba(0,123,255,'}${Math.random()*.5+.3});
    animation-duration:${Math.random()*12+8}s;animation-delay:${Math.random()*8}s;`;
  document.body.appendChild(p);
}
</script>

<div class="sp-wrap">
  <!-- Icône animée -->
  <div class="sp-icon-ring">
    <div class="sp-icon-glow"></div>
    <div class="sp-icon-ring-outer"></div>
    <div class="sp-icon-ring-inner">
      <i class="fas fa-bolt"></i>
    </div>
    <div class="sp-orbit"><div class="sp-orbit-dot"></div></div>
  </div>

  <div class="sp-brand">TechStore</div>
  <div class="sp-tagline">Votre expert high-tech au Cameroun</div>

  <div class="sp-progress-wrap">
    <div class="sp-progress-track">
      <div class="sp-progress-bar"></div>
    </div>
    <div class="sp-progress-text">Initialisation en cours…</div>
  </div>

  <div class="sp-dots">
    <div class="sp-dot"></div>
    <div class="sp-dot"></div>
    <div class="sp-dot"></div>
  </div>

  <div class="sp-features">
    <div class="sp-feature"><i class="fas fa-shield-check"></i> Produits certifiés</div>
    <div class="sp-feature"><i class="fas fa-truck-fast"></i> Livraison express</div>
    <div class="sp-feature"><i class="fas fa-headset"></i> Support 7j/7</div>
  </div>
</div>

<script>
setTimeout(()=>{ window.location.href='<?= $url ?>'; }, <?= $delay * 1000 ?>);
</script>
</body>
</html>
