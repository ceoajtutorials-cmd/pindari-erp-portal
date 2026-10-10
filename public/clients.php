<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
include __DIR__ . '/../includes/header.php';
$clients = [
 ['name'=>'DANKEL TECH','person'=>'Mr. Sachin Dandekar','logo'=>'dankel.png','letter'=>'D','color'=>'#0a8a29'],
 ['name'=>'R K POLYMER','person'=>'Mr. Amol Shah','logo'=>'rk-polymer.png','letter'=>'R','color'=>'#1976d2'],
 ['name'=>'KRAFT PLAST INDS','person'=>'Mr. Sanket Shah','logo'=>'kraft.png','letter'=>'K','color'=>'#ef6c00'],
 ['name'=>'APEX ENGINEERS','person'=>'Mr. Murti','logo'=>'apex.png','letter'=>'A','color'=>'#455a64'],
 ['name'=>'SUHAS ENTERPRISES','person'=>'Mr. Ganesh Sathe','logo'=>'suhas.png','letter'=>'S','color'=>'#1a237e'],
 ['name'=>'TECHNOVA','person'=>'Mr. Jitendra Kelkar','logo'=>'technova.png','letter'=>'T','color'=>'#d32f2f'],
];
?>
<div style="max-width:1150px;margin:auto;padding:60px 20px;font-family:system-ui;">
<h1 style="text-align:center;font-size:36px;font-weight:800;">Industry Clients We Serve</h1>
<p style="text-align:center;color:#d32f2f;font-weight:700;margin:8px 0 45px;">Over 20+ Years of Excellence led by Mr. Pawan Mishra</p>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:22px;margin-bottom:70px;">
<?php foreach($clients as $c):
 $full = __DIR__ . "/assets/clients/" . $c['logo'];
 $has = file_exists($full);
 $url = "/assets/clients/" . $c['logo'];
 if($c['logo']=='dankel.png' && !$has && file_exists(__DIR__."/assets/clients/dankal.png")){ $has=true; $url="/assets/clients/dankal.png"; }
?>
<div style="border:1px solid #eee;border-radius:16px;padding:26px;background:#fff;box-shadow:0 4px 20px rgba(0,0,0,0.06);">
<?php if($has): ?><img src="<?= $url ?>" style="height:65px;object-fit:contain;margin-bottom:16px;display:block;">
<?php else: ?><div style="width:56px;height:56px;background:<?= $c['color'] ?>;color:#fff;border-radius:12px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:22px;margin-bottom:16px;"><?= $c['letter'] ?></div><?php endif; ?>
<h3 style="font-weight:800;"><?= $c['name'] ?></h3><p style="color:#555;margin-top:6px;font-size:14px;"><?= $c['person'] ?></p>
</div>
<?php endforeach; ?>
</div>
<h2 style="text-align:center;font-size:28px;font-weight:800;">Real Feedback from Unit Heads</h2>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:22px;margin-top:25px;">
<div style="background:#fff8e1;border-radius:16px;padding:24px;border:1px solid #ffecb3;"><div>★★★★★</div><p style="font-style:italic;font-weight:700;margin-top:8px;">"PINDARI ENTERPRISES PROVIDED US WITH 15 CNC OPERATORS WITHIN 24 HOURS!"</p><b>MR. SACHIN DANDEKAR - Dankel Tech</b></div>
<div style="background:#e8f5e9;border-radius:16px;padding:24px;border:1px solid #c8e6c9;"><div>★★★★★</div><p style="font-style:italic;font-weight:700;margin-top:8px;">"THE MOST TRANSPARENT PAYROLL SYSTEM IN PCMC!"</p><b>MR. AMOL SHAH - RK Polymer</b></div>
<div style="background:#e3f2fd;border-radius:16px;padding:24px;border:1px solid #bbdefb;"><div>★★★★★</div><p style="font-style:italic;font-weight:700;margin-top:8px;">"ALL WORKERS VERIFIED WITH AADHAR & POLICE CHECKS!"</p><b>MR. SANKET SHAH - Kraft Plast</b></div>
</div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
