<?php
require_once __DIR__ . '/../config/config.php';
$pageTitle = 'Contact Us';
$currentPage = 'contact';
require_once __DIR__ . '/../includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $errors = [];
    if (empty($name)) $errors[] = 'Name is required';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
    if (empty($message)) $errors[] = 'Message is required';

    if (empty($errors)) {
        try {
            $stmt = db()->prepare("INSERT INTO contact_messages (name, email, phone, company, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $company, $message]);
            set_flash('success', 'Thank you! Your message has been received. Our team will contact you within 24 hours.');
            redirect(APP_URL . '/public/contact.php');
        } catch (Exception $e) {
            set_flash('error', 'Sorry, there was an issue submitting your message. Please try again or email us directly.');
        }
    } else {
        set_flash('error', implode('<br>', $errors));
    }
}

$flash = get_flash();
?>

<section class="page-header">
  <div class="container">
    <h1>Contact Us</h1>
    <p>Get in touch with our team to discuss your workforce requirements.</p>
    <div class="breadcrumb"><a href="<?= APP_URL ?>/public/index.php">Home</a> <span>/</span> <span>Contact</span></div>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if ($flash): ?>
      <div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>

    <div class="contact-grid">
      <div class="contact-info-card">
        <h3>Get In Touch</h3>
        <div class="contact-info-item">
          <div class="contact-info-icon">&#128205;</div>
          <div>
            <h5>Address</h5>
            <p>Plot No. 22, Industrial Estate<br>Pimpri-Chinchwad, Pune<br>Maharashtra 411018, India</p>
          </div>
        </div>
        <div class="contact-info-item">
          <div class="contact-info-icon">&#128222;</div>
          <div>
            <h5>Phone</h5>
            <p>+91 98765 43210<br>+91 020 6612 3400</p>
          </div>
        </div>
        <div class="contact-info-item">
          <div class="contact-info-icon">&#9993;</div>
          <div>
            <h5>Email</h5>
            <p>info@pindari.com<br>hr@pindari.com</p>
          </div>
        </div>
        <div class="contact-info-item">
          <div class="contact-info-icon">&#128337;</div>
          <div>
            <h5>Business Hours</h5>
            <p>Mon - Sat: 9:00 AM - 6:30 PM<br>Sunday: Closed</p>
          </div>
        </div>
      </div>

      <div class="contact-form">
        <h3 style="font-size:24px;font-weight:700;color:var(--blue);margin-bottom:8px;">Send Us a Message</h3>
        <p style="color:var(--gray-500);margin-bottom:24px;font-size:15px;">Fill out the form below and we'll get back to you within 24 hours.</p>
        <form method="POST" action="<?= APP_URL ?>/public/contact.php">
          <div class="form-row">
            <div class="form-group">
              <label>Full Name *</label>
              <input type="text" name="name" required value="<?= e($_POST['name'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Phone Number</label>
              <input type="tel" name="phone" value="<?= e($_POST['phone'] ?? '') ?>">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Email Address *</label>
              <input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Company Name</label>
              <input type="text" name="company" value="<?= e($_POST['company'] ?? '') ?>">
            </div>
          </div>
          <div class="form-group">
            <label>Message *</label>
            <textarea name="message" rows="5" required placeholder="Tell us about your workforce requirements..."><?= e($_POST['message'] ?? '') ?></textarea>
          </div>
          <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">Send Message &#8594;</button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
