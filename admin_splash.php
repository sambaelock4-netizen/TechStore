<?php
require_once 'config/constants.php';
$delay = 3;
$url   = BASE_URL . '/admin';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TechStore Admin — Chargement</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%;overflow:hidden}
body{
  font-family:'Times New Roman',Times,Georgia,serif;
  background:#080e18;
  display:flex;align-items:center;justify-content:center;min-height:100vh;
  position:relative;
}

.sp-bg{position:fixed;inset:0;z-index:0;overflow:hidden;}
.sp-bg::before{
  content:'';position:absolute;width:900px;height:900px;border-radius:50%;
  background:radial-gradient(circle,rgba(0,123,255,.12) 0%,transparent 65%);
  top:-350px;left:-350px;animation:bgPulse 8s ease-in-out infinite;
}
.sp-bg::after{
  content:'';position:absolute;width:750px;height:750px;border-radius:50%;
  background:radial-gradient(circle,rgba(255,160,122,.12) 0%,transparent 65%);
  bottom:-250px;right:-250px;animation:bgPulse 8s ease-in-out infinite 1.5s;
}
@keyframes bgPulse{0%,100%{transform:scale(1);opacity:.5}50%{transform:scale(1.15);opacity:1}}

.sp-grid{
  position:fixed;inset:0;z-index:0;
  background-image:
    linear-gradient(rgba(0,123,255,.05) 1px,transparent 1px),
    linear-gradient(90deg,rgba(0,123,255,.05) 1px,transparent 1px);
  background-size:42px 42px;
}

.sp-scanline{
  position:fixed;top:-100%;left:0;right:0;height:200px;z-index:1;pointer-events:none;
  background:linear-gradient(180deg,transparent,rgba(0,123,255,.06),transparent);
  animation:scanDown 6s linear infinite;
}
@keyframes scanDown{from{top:-200px}to{top:100vh}}

.sp-particle{
  position:fixed;border-radius:50%;pointer-events:none;z-index:2;
  animation:partRise linear infinite;
}
@keyframes partRise{
  0%{transform:translateY(100vh) scale(0);opacity:0}
  10%{opacity:1}90%{opacity:.4}
  100%{transform:translateY(-10vh) scale(.3);opacity:0}
}

/* Badge admin */
.sp-badge{
  display:inline-flex;align-items:center;gap:8px;padding:7px 20px;border-radius:30px;margin-bottom:30px;
  background:rgba(255,160,122,.12);border:1px solid rgba(255,160,122,.3);
  font-family:'Times New Roman',Times,serif;font-size:11px;font-weight:700;
  color:rgba(255,160,122,.9);text-transform:uppercase;letter-spacing:2.5px;
  animation:spIn .9s cubic-bezier(.22,1,.36,1) both;
}

.sp-wrap{
  position:relative;z-index:10;text-align:center;
  padding:52px 36px;width:100%;max-width:500px;
  animation:spIn .9s cubic-bezier(.22,1,.36,1) both;
}
@keyframes spIn{from{opacity:0;transform:translateY(40px)}to{opacity:1;transform:translateY(0)}}

/* Hexagone admin icon */
.sp-hex-wrap{
  position:relative;width:130px;height:130px;margin:0 auto 32px;
  animation:iconFloat 4s ease-in-out infinite;
}
@keyframes iconFloat{0%,100%{transform:translateY(0) rotate(0deg)}50%{transform:translateY(-10px) rotate(3deg)}}

.sp-hex-glow{
  position:absolute;inset:-28px;
  background:radial-gradient(circle,rgba(255,160,122,.22) 0%,rgba(0,123,255,.12) 40%,transparent 70%);
  border-radius:50%;animation:glowPulse 2.8s ease-in-out infinite;
}
@keyframes glowPulse{0%,100%{opacity:.4;transform:scale(1)}50%{opacity:1;transform:scale(1.2)}}

.sp-hex-ring1,.sp-hex-ring2{
  position:absolute;inset:0;border-radius:50%;border:1px solid transparent;
}
.sp-hex-ring1{
  border-color:rgba(0,123,255,.3);
  animation:ringRotateCW 6s linear infinite;
}
.sp-hex-ring2{
  inset:-10px;
  border-color:rgba(255,160,122,.2);
  animation:ringRotateCCW 10s linear infinite;
}
@keyframes ringRotateCW{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
@keyframes ringRotateCCW{from{transform:rotate(0deg)}to{transform:rotate(-360deg)}}

.sp-hex-orbit{
  position:absolute;inset:-8px;border-radius:50%;
  animation:orbitSpin 3.5s linear infinite;
}
@keyframes orbitSpin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
.sp-hex-orb{
  position:absolute;top:0;left:50%;width:9px;height:9px;border-radius:50%;
  transform:translateX(-50%);
  background:linear-gradient(135deg,#007bff,#ffa07a);
  box-shadow:0 0 14px rgba(0,123,255,.9);
}

.sp-hex-inner{
  position:absolute;inset:12px;border-radius:50%;
  background:linear-gradient(135deg,#0a1220,#111d30);
  display:flex;align-items:center;justify-content:center;
  border:1px solid rgba(255,255,255,.06);
  box-shadow:0 0 50px rgba(0,123,255,.2),inset 0 1px 0 rgba(255,255,255,.07);
}
.sp-hex-inner i{
  font-size:38px;
  background:linear-gradient(135deg,#007bff,#ffa07a);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}

/* Corner decorations */
.sp-corner{position:absolute;width:20px;height:20px;}
.sp-corner-tl{top:0;left:0;border-top:2px solid rgba(0,123,255,.5);border-left:2px solid rgba(0,123,255,.5);}
.sp-corner-tr{top:0;right:0;border-top:2px solid rgba(255,160,122,.5);border-right:2px solid rgba(255,160,122,.5);}
.sp-corner-bl{bottom:0;left:0;border-bottom:2px solid rgba(255,160,122,.5);border-left:2px solid rgba(255,160,122,.5);}
.sp-corner-br{bottom:0;right:0;border-bottom:2px solid rgba(0,123,255,.5);border-right:2px solid rgba(0,123,255,.5);}

.sp-brand{
  font-family:'Times New Roman',Times,serif;
  font-size:32px;font-weight:900;
  background:linear-gradient(135deg,#007bff,#0056b3,#ffa07a);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
  letter-spacing:.04em;margin-bottom:5px;
  animation:spIn .9s cubic-bezier(.22,1,.36,1) .1s both;
}
.sp-sub{
  font-family:'Times New Roman',Times,serif;
  font-size:11px;color:rgba(180,200,255,.4);
  letter-spacing:.25em;text-transform:uppercase;margin-bottom:40px;
  animation:spIn .9s cubic-bezier(.22,1,.36,1) .2s both;
}

/* Status grid */
.sp-status-grid{
  display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:32px;
  animation:spIn .9s cubic-bezier(.22,1,.36,1) .3s both;
}
.sp-status-item{
  padding:10px 8px;border-radius:10px;
  background:rgba(255,255,255,.03);border:1px solid rgba(0,123,255,.12);
  font-family:'Times New Roman',Times,serif;font-size:10px;
  color:rgba(180,200,255,.5);text-align:center;
}
.sp-status-item i{display:block;font-size:15px;margin-bottom:5px;color:rgba(0,123,255,.6);}
.sp-status-item.ok i{color:rgba(74,222,128,.7);}
.sp-status-item.warn i{color:rgba(255,160,122,.7);}

/* Progress */
.sp-progress-wrap{animation:spIn .9s cubic-bezier(.22,1,.36,1) .4s both;}
.sp-progress-track{
  height:2px;background:rgba(255,255,255,.06);border-radius:10px;overflow:hidden;margin-bottom:14px;
}
.sp-progress-bar{
  height:100%;border-radius:10px;
  background:linear-gradient(90deg,#007bff,#ffa07a,#007bff);
  background-size:200% 100%;
  animation:progressFill <?= $delay ?>s cubic-bezier(.4,0,.2,1) both, shimmer 1.5s linear infinite;
  box-shadow:0 0 10px rgba(0,123,255,.5);
}
@keyframes progressFill{from{width:0}to{width:100%}}
@keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}

.sp-loading-text{
  font-family:'Times New Roman',Times,serif;
  font-size:11px;color:rgba(180,200,255,.4);
  letter-spacing:.15em;text-transform:uppercase;margin-bottom:16px;
}

/* Dots */
.sp-dots{display:flex;justify-content:center;gap:5px;animation:spIn .9s cubic-bezier(.22,1,.36,1) .5s both;}
.sp-dot{
  width:5px;height:5px;border-radius:50%;
  animation:dotBounce 1.6s ease-in-out infinite;
}
.sp-dot:nth-child(1){background:rgba(0,123,255,.8);animation-delay:0s;}
.sp-dot:nth-child(2){background:rgba(255,160,122,.7);animation-delay:.25s;}
.sp-dot:nth-child(3){background:rgba(0,123,255,.6);animation-delay:.5s;}
.sp-dot:nth-child(4){background:rgba(255,160,122,.5);animation-delay:.75s;}
@keyframes dotBounce{0%,80%,100%{transform:scale(1);opacity:.4}40%{transform:scale(1.6);opacity:1}}
</style>
</head>
<body>
<div class="sp-bg"></div>
<div class="sp-grid"></div>
<div class="sp-scanline"></div>

<script>
for(let i=0;i<12;i++){
  const p=document.createElement('div');
  p.className='sp-particle';
  const size=Math.random()*3+1.5;
  const isOrange=Math.random()>.5;
  p.style.cssText=`width:${size}px;height:${size}px;left:${Math.random()*100}%;bottom:-${size}px;
    background:${isOrange?'rgba(255,160,122,':'rgba(0,123,255,'}${Math.random()*.4+.3});
    animation-duration:${Math.random()*14+10}s;animation-delay:${Math.random()*10}s;`;
  document.body.appendChild(p);
}
</script>

<div class="sp-wrap">
  <div class="sp-badge"><i class="fas fa-lock"></i> Administration</div>

  <div class="sp-hex-wrap">
    <div class="sp-hex-glow"></div>
    <div class="sp-hex-ring1"></div>
    <div class="sp-hex-ring2"></div>
    <div class="sp-hex-orbit"><div class="sp-hex-orb"></div></div>
    <div class="sp-hex-inner"><i class="fas fa-shield-halved"></i></div>
    <div class="sp-corner sp-corner-tl"></div>
    <div class="sp-corner sp-corner-tr"></div>
    <div class="sp-corner sp-corner-bl"></div>
    <div class="sp-corner sp-corner-br"></div>
  </div>

  <div class="sp-brand">TechStore</div>
  <div class="sp-sub">Panneau d'administration</div>

  <div class="sp-status-grid">
    <div class="sp-status-item ok"><i class="fas fa-database"></i>Base de données</div>
    <div class="sp-status-item ok"><i class="fas fa-server"></i>Serveur actif</div>
    <div class="sp-status-item warn"><i class="fas fa-lock"></i>Sécurisé</div>
  </div>

  <div class="sp-progress-wrap">
    <div class="sp-progress-track">
      <div class="sp-progress-bar"></div>
    </div>
    <div class="sp-loading-text">Chargement du tableau de bord…</div>
    <div class="sp-dots">
      <div class="sp-dot"></div>
      <div class="sp-dot"></div>
      <div class="sp-dot"></div>
      <div class="sp-dot"></div>
    </div>
  </div>
</div>

<script>setTimeout(()=>{window.location.href='<?= $url ?>';},<?= $delay * 1000 ?>);</script>
</body>
</html>
