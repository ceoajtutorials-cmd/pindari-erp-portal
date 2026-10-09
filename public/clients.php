<?php
require_once __DIR__ . '/../config/config.php';
$pageTitle = 'Clients';
$currentPage = 'clients';
require_once __DIR__ . '/../includes/header.php';

// Fetch clients from database
$clients = [];
try {
    $clients = db()->query("SELECT * FROM clients ORDER BY company_name")->fetchAll();
} catch (Exception $e) {
    // Use static list if DB not available
}

$clientLogos = [
    'Tata Motors' => ['letter' => 'T', 'color' => '#1a4a8e'],
    'Bajaj Auto' => ['letter' => 'B', 'color' => '#0a2a5e'],
    'Mahindra & Mahindra' => ['letter' => 'M', 'color' => '#d71921'],
    'Force Motors' => ['letter' => 'F', 'color' => '#c8102e'],
];

function getLogoInfo($name, $clientLogos) {
    foreach ($clientLogos as $key => $info) {
        if (stripos($name, $key) !== false || stripos($key, $name) !== false) {
            return $info;
        }
    }
    return ['letter' => strtoupper(substr($name, 0, 1)), 'color' => '#0a2a5e'];
}
?>

<section class="page-header">
  <div class="container">
    <h1>Our Clients</h1>
    <p>Partnering with India's leading manufacturing and automotive enterprises.</p>
    <div class="breadcrumb"><a href="<?= APP_URL ?>/public/index.php">Home</a> <span>/</span> <span>Clients</span></div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Enterprise Partners</span>
      <h2>Companies That Trust Us</h2>
      <p>We are honoured to serve some of India's most respected industrial houses across automotive, manufacturing, and engineering sectors.</p>
    </div>
    <div class="clients-grid">
      <?php if (!empty($clients)): ?>
        <?php foreach ($clients as $client): 
          $logo = getLogoInfo($client['company_name'], $clientLogos);
        ?>
          <div class="client-card">
            <div class="client-logo" style="background:<?= e($logo['color']) ?>;"><?= e($logo['letter']) ?></div>
            <h4><?= e($client['company_name']) ?></h4>
            <p><?= e($client['address'] ? explode(',', $client['address'])[0] : 'India') ?></p>
            <p style="margin-top:8px;font-size:12px;color:<?= $client['status'] === 'active' ? 'var(--success)' : 'var(--gray-500)' ?>;">
              &#9679; <?= ucfirst(e($client['status'])) ?>
            </p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <?php foreach ($clientLogos as $name => $logo): ?>
          <div class="client-card">
            <div class="client-logo" style="background:<?= e($logo['color']) ?>;"><?= e($logo['letter']) ?></div>
            <h4><?= e($name) ?></h4>
            <p>Maharashtra, India</p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Testimonials</span>
      <h2>What Our Clients Say</h2>
    </div>
    <div class="services-grid">
      <div class="service-card">
        <div style="font-size:40px;color:var(--red);margin-bottom:12px;">&ldquo;</div>
        <p style="font-size:16px;color:var(--gray-600);margin-bottom:20px;">Pindari Enterprises has been managing our contract workforce for over 3 years. Their compliance record and on-time payroll are exceptional. We've never had a single audit issue.</p>
        <h4 style="font-size:16px;color:var(--blue);">Rajesh Kumar</h4>
        <p style="font-size:14px;color:var(--gray-500);">Plant HR Head, Tata Motors Pune</p>
      </div>
      <div class="service-card">
        <div style="font-size:40px;color:var(--red);margin-bottom:12px;">&ldquo;</div>
        <p style="font-size:16px;color:var(--gray-600);margin-bottom:20px;">The team at Pindari is responsive and professional. They scaled up 100 workers for us within a week during our peak season. Highly recommended for manufacturing staffing.</p>
        <h4 style="font-size:16px;color:var(--blue);">Priya Sharma</h4>
        <p style="font-size:14px;color:var(--gray-500);">Operations Manager, Bajaj Auto</p>
      </div>
      <div class="service-card">
        <div style="font-size:40px;color:var(--red);margin-bottom:12px;">&ldquo;</div>
        <p style="font-size:16px;color:var(--gray-600);margin-bottom:20px;">Their compliance management service has saved us countless hours of paperwork. PF, ESI, and labour law filings are always on time. A true partner.</p>
        <h4 style="font-size:16px;color:var(--blue);">Amit Deshpande</h4>
        <p style="font-size:14px;color:var(--gray-500);">Admin Head, Mahindra &amp; Mahindra</p>
      </div>
      <div class="service-card">
        <div style="font-size:40px;color:var(--red);margin-bottom:12px;">&ldquo;</div>
        <p style="font-size:16px;color:var(--gray-600);margin-bottom:20px;">The ERP portal gives us real-time visibility into our deployed workforce, attendance, and payroll. It's exactly what we needed for transparent management.</p>
        <h4 style="font-size:16px;color:var(--blue);">Sunita Patil</h4>
        <p style="font-size:14px;color:var(--gray-500);">HR Director, Force Motors</p>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
