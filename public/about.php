<?php
require_once __DIR__ . '/../config/config.php';
$pageTitle = 'About Us';
$currentPage = 'about';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-header">
  <div class="container">
    <h1>About Pindari Enterprises</h1>
    <p>Building India's workforce backbone since 2009 with trust, compliance, and excellence.</p>
    <div class="breadcrumb"><a href="<?= APP_URL ?>/public/index.php">Home</a> <span>/</span> <span>About Us</span></div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="about-grid">
      <div class="about-text">
        <h2>Our Story</h2>
        <p>Founded in 2009, Pindari Enterprises has grown into one of Maharashtra's most trusted workforce solutions providers. Starting with a small team serving local manufacturing units, we now manage over 1,500 deployed personnel across 50+ enterprise clients.</p>
        <p>Our mission is simple: to be the bridge between skilled workforce and industry needs, ensuring compliance, efficiency, and dignity of labour at every step.</p>
        <ul class="about-checklist">
          <li>Licensed and compliant under all statutory regulations</li>
          <li>Dedicated account managers for every client</li>
          <li>On-site supervision and workforce monitoring</li>
          <li>End-to-end payroll and compliance handled in-house</li>
          <li>Pan-India deployment capability</li>
        </ul>
        <a href="<?= APP_URL ?>/public/contact.php" class="btn-primary">Work With Us &#8594;</a>
      </div>
      <div class="about-visual">
        <h3>Why Choose Us?</h3>
        <div class="about-feature">
          <div class="about-feature-icon">&#9989;</div>
          <div>
            <h4>Compliance First</h4>
            <p>Full PF, ESI, and labour law compliance with zero audit risks.</p>
          </div>
        </div>
        <div class="about-feature">
          <div class="about-feature-icon">&#9889;</div>
          <div>
            <h4>Rapid Deployment</h4>
            <p>Workforce mobilized within 7 days of requirement confirmation.</p>
          </div>
        </div>
        <div class="about-feature">
          <div class="about-feature-icon">&#128200;</div>
          <div>
            <h4>Cost Optimization</h4>
            <p>Reduce staffing costs by up to 30% without compromising quality.</p>
          </div>
        </div>
        <div class="about-feature">
          <div class="about-feature-icon">&#129309;</div>
          <div>
            <h4>Dedicated Support</h4>
            <p>24/7 on-site supervision and a dedicated relationship manager.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Our Values</span>
      <h2>What Drives Us Forward</h2>
    </div>
    <div class="services-grid">
      <div class="service-card">
        <div class="service-icon">&#7503;</div>
        <h3>Integrity</h3>
        <p>We operate with complete transparency in payroll, billing, and compliance. No hidden charges, no shortcuts.</p>
      </div>
      <div class="service-card">
        <div class="service-icon">&#9733;</div>
        <h3>Excellence</h3>
        <p>Every deployment, every payslip, every compliance filing meets the highest quality standards.</p>
      </div>
      <div class="service-card">
        <div class="service-icon">&#128150;</div>
        <h3>Dignity of Labour</h3>
        <p>We treat every worker with respect, ensuring fair wages, timely payments, and safe working conditions.</p>
      </div>
      <div class="service-card">
        <div class="service-icon">&#129518;</div>
        <h3>Partnership</h3>
        <p>We don't just supply manpower. We partner with your business to understand and solve workforce challenges.</p>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
