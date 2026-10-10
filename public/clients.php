<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
include __DIR__ . '/../includes/header.php';
?>
<div style="max-width:1100px; margin:auto; padding:60px 20px;">
  
  <div style="text-align:center; margin-bottom:50px;">
    <h1 style="font-size:36px; font-weight:800; color:#1a1a1a;">Industry Clients We Serve</h1>
    <p style="color:#d32f2f; font-weight:600; margin-top:10px; font-size:18px;">Over 20+ Years of Excellence led by Mr. Pawan Mishra</p>
  </div>

  <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:25px; margin-bottom:70px;">
    <?php
    $clients = [
        ['name'=>'DANKAL TECH','person'=>'Mr. Sachin Dandekar','loc'=>'Nigdi'],
        ['name'=>'R K POLYMER','person'=>'Mr. Amol Shah','loc'=>'PCMC'],
        ['name'=>'KRAFT PLAST INDS','person'=>'Mr. Sanket Shah','loc'=>'PCMC'],
        ['name'=>'APEX ENGINEERS','person'=>'Mr. Murti','loc'=>'PCMC'],
        ['name'=>'SUHAS ENTERPRISES','person'=>'Mr. Ganesh Sathe','loc'=>'PCMC'],
        ['name'=>'TECHNOVA','person'=>'Mr. Jitendra Kelkar','loc'=>'PCMC'],
    ];
    foreach($clients as $c):
    ?>
    <div style="border:1px solid #eee; border-radius:16px; padding:25px; background:#fff; box-shadow:0 4px 15px rgba(0,0,0,0.05);">
      <div style="width:50px; height:50px; background:#d32f2f; color:#fff; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:20px; margin-bottom:15px;"><?= substr($c['name'],0,1) ?></div>
      <h3 style="font-weight:700; font-size:18px;"><?= $c['name'] ?></h3>
      <p style="color:#666; margin-top:5px;"><?= $c['person'] ?></p>
      <p style="color:#999; font-size:13px; margin-top:3px;"><?= $c['loc'] ?> • Verified Client</p>
    </div>
    <?php endforeach; ?>
  </div>

  <h2 style="text-align:center; font-size:28px; font-weight:800; margin-bottom:30px;">Real Feedback from Unit Heads</h2>
  <p style="text-align:center; color:#666; margin-bottom:30px;">Verified Reviews from PCMC Industrial Area Managers</p>

  <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:25px;">
    <div style="background:#fff8e1; border-radius:16px; padding:25px; border:1px solid #ffecb3;">
      <div style="color:#ffb300; margin-bottom:10px;">★★★★★</div>
      <p style="font-style:italic; font-weight:600; line-height:1.6;">"PINDARI ENTERPRISES PROVIDED US WITH 15 CNC OPERATORS WITHIN 24 HOURS! EXCELLENT SERVICE & HIGHLY DISCIPLINED WORKFORCE."</p>
      <p style="margin-top:15px; font-weight:700;">MR. SACHIN DANDEKAR</p><p style="font-size:13px; color:#666;">Dankal Tech, Nigdi</p>
    </div>
    <div style="background:#e8f5e9; border-radius:16px; padding:25px; border:1px solid #c8e6c9;">
      <div style="color:#ffb300; margin-bottom:10px;">★★★★★</div>
      <p style="font-style:italic; font-weight:600; line-height:1.6;">"THE MOST TRANSPARENT PAYROLL AND ADVANCE SYSTEM IN PCMC INDUSTRIAL AREA. OUR PRODUCTION HAS NEVER SUFFERED SINGLE MANPOWER SHORTAGE!"</p>
      <p style="margin-top:15px; font-weight:700;">MR. AMOL SHAH</p><p style="font-size:13px; color:#666;">RK Polymer</p>
    </div>
    <div style="background:#e3f2fd; border-radius:16px; padding:25px; border:1px solid #bbdefb;">
      <div style="color:#ffb300; margin-bottom:10px;">★★★★★</div>
      <p style="font-style:italic; font-weight:600; line-height:1.6;">"ALL WORKERS ARE PROPERLY VERIFIED WITH AADHAR & POLICE RECORD CHECKS. HIGHLY RECOMMENDED FOR RELIABLE INDUSTRIAL STAFFING!"</p>
      <p style="margin-top:15px; font-weight:700;">MR. SANKET SHAH</p><p style="font-size:13px; color:#666;">Kraft Plast Inds</p>
    </div>
  </div>

</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
