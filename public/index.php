<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../config/config.php';
$pageTitle = 'Home';
$currentPage = 'home';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- HERO -->
<section class="hero">
  <div class="container hero-content">
    <div class="hero-badge animate-in">
      <span>&#9679;</span> Trusted by 50+ Indian Enterprises
    </div>
    <h1 class="animate-up">Empowering <span class="accent">Workforce Solutions</span> for India's Leading Industries</h1>
    <p class="lead animate-up">From manpower outsourcing to payroll and compliance management, Pindari Enterprises delivers end-to-end workforce solutions that drive operational excellence.</p>
    <div class="hero-actions animate-up">
      <a href="<?= APP_URL ?>/public/contact.php" class="btn-primary">Get a Quote &#8594;</a>
      <a href="<?= APP_URL ?>/public/services.php" class="btn-outline">Explore Services</a>
    </div>
  </div>
</section>

<!-- STATS -->
<section class="stats-section">
  <div class="container">
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-number" data-value="1500">0</div>
        <div class="stat-label">Workforce Deployed</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" data-value="50" data-suffix="+">0</div>
        <div class="stat-label">Enterprise Clients</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" data-value="15" data-suffix="+">0</div>
        <div class="stat-label">Years Experience</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" data-value="98" data-suffix="%">0</div>
        <div class="stat-label">Client Retention</div>
      </div>
    </div>
  </div>
</section>

<!-- SERVICES PREVIEW -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">What We Do</span>
      <h2>Comprehensive Workforce Solutions</h2>
      <p>We provide a full spectrum of services designed to manage your workforce efficiently, compliantly, and cost-effectively.</p>
    </div>
    <div class="services-grid">
      <div class="service-card">
        <div class="service-icon">&#128200;</div>
        <h3>Manpower Outsourcing</h3>
        <p>Complete workforce management for production, logistics, and operations with skilled and semi-skilled personnel.</p>
      </div>
      <div class="service-card">
        <div class="service-icon">&#128101;</div>
        <h3>Contract Staffing</h3>
        <p>Flexible staffing solutions to meet seasonal demands and project-based requirements without long-term overhead.</p>
      </div>
      <div class="service-card">
        <div class="service-icon">&#128176;</div>
        <h3>Payroll Management</h3>
        <p>Accurate, timely payroll processing with statutory deductions, payslip generation, and full compliance.</p>
      </div>
      <div class="service-card">
        <div class="service-icon">&#128220;</div>
        <h3>Compliance Management</h3>
        <p>PF, ESI, labour law compliance, and statutory reporting handled by experts to keep your business audit-ready.</p>
      </div>
    </div>
  </div>
</section>

<!-- CLIENTS PREVIEW -->
<section class="section section-alt">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Our Clients</span>
      <h2>Trusted by Industry Leaders</h2>
      <p>We are proud to partner with India's most respected manufacturing and automotive enterprises.</p>
    </div>
    <div class="clients-grid">
      <div class="client-card">
        <div class="client-logo" style="background:#1a4a8e;">T</div>
        <h4>Tata Motors</h4>
        <p>Pune, Maharashtra</p>
      </div>
      <div class="client-card">
        <div class="client-logo" style="background:#0a2a5e;">B</div>
        <h4>Bajaj Auto</h4>
        <p>Aurangabad, Maharashtra</p>
      </div>
      <div class="client-card">
        <div class="client-logo" style="background:#d71921;">M</div>
        <h4>Mahindra</h4>
        <p>Pune, Maharashtra</p>
      </div>
      <div class="client-card">
        <div class="client-logo" style="background:#c8102e;">F</div>
        <h4>Force Motors</h4>
        <p>Pune, Maharashtra</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section">
  <div class="container">
    <div class="cta-banner">
      <div>
        <h3>Ready to optimize your workforce?</h3>
        <p>Let's discuss how Pindari Enterprises can support your staffing and compliance needs.</p>
      </div>
      <a href="<?= APP_URL ?>/public/contact.php" class="btn-primary">Contact Us Today</a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
