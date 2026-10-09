<?php
require_once __DIR__ . '/../config/config.php';
$pageTitle = 'Our Services';
$currentPage = 'services';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-header">
  <div class="container">
    <h1>Our Services</h1>
    <p>End-to-end workforce solutions tailored for manufacturing, automotive, and industrial sectors.</p>
    <div class="breadcrumb"><a href="<?= APP_URL ?>/public/index.php">Home</a> <span>/</span> <span>Services</span></div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Core Services</span>
      <h2>Everything You Need to Manage Your Workforce</h2>
      <p>From hiring to payroll to compliance, we handle the full lifecycle so you can focus on your core business.</p>
    </div>
    <div class="services-grid">
      <div class="service-card" id="manpower">
        <div class="service-icon">&#128200;</div>
        <h3>Manpower Outsourcing</h3>
        <p>Complete outsourcing of skilled, semi-skilled, and unskilled workforce for production lines, assembly, packaging, warehousing, and facility management. We handle recruitment, deployment, supervision, and performance management.</p>
      </div>
      <div class="service-card" id="staffing">
        <div class="service-icon">&#128101;</div>
        <h3>Contract Staffing</h3>
        <p>Flexible staffing for seasonal peaks, project-based work, and temporary requirements. Quickly scale up or down without permanent headcount commitments. Ideal for automotive and manufacturing ramp-ups.</p>
      </div>
      <div class="service-card" id="payroll">
        <div class="service-icon">&#128176;</div>
        <h3>Payroll Management</h3>
        <p>Comprehensive payroll processing including salary calculation, statutory deductions (PF, ESI, PT), TDS, payslip generation, and bank transfer coordination. Fully compliant with all regulations.</p>
      </div>
      <div class="service-card" id="compliance">
        <div class="service-icon">&#128220;</div>
        <h3>Compliance Management</h3>
        <p>End-to-end statutory compliance including PF registrations, ESI filings, labour licence management, minimum wage adherence, contract labour regulation compliance, and audit-ready documentation.</p>
      </div>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Additional Services</span>
      <h2>Beyond the Basics</h2>
    </div>
    <div class="services-grid">
      <div class="service-card">
        <div class="service-icon">&#128269;</div>
        <h3>Recruitment & RPO</h3>
        <p>Recruitment Process Outsourcing for bulk hiring drives, campus placements, and specialized roles across manufacturing and operations.</p>
      </div>
      <div class="service-card">
        <div class="service-icon">&#127891;</div>
        <h3>Training & Skilling</h3>
        <p>On-the-job training, safety orientation, and skill development programs to ensure deployed workforce meets your operational standards.</p>
      </div>
      <div class="service-card">
        <div class="service-icon">&#128737;</div>
        <h3>Safety & PPE</h3>
        <p>Complete safety gear provisioning, periodic safety audits, and compliance with factory safety regulations for all deployed personnel.</p>
      </div>
      <div class="service-card">
        <div class="service-icon">&#128202;</div>
        <h3>Reporting & Analytics</h3>
        <p>Detailed MIS reports on attendance, productivity, payroll costs, and compliance status through our client portal dashboard.</p>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="cta-banner">
      <div>
        <h3>Need a customized workforce solution?</h3>
        <p>Talk to our team about your specific requirements and get a tailored proposal.</p>
      </div>
      <a href="<?= APP_URL ?>/public/contact.php" class="btn-primary">Request a Proposal</a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
