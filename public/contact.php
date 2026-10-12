<?php
require_once __DIR__ . '/../config/config.php';
$pageTitle = 'Contact Us';
$currentPage = 'contact';

// Form Handling Logic
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            if (function_exists('db')) {
                $stmt = db()->prepare("INSERT INTO contact_messages (name, email, phone, company, message) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $email, $phone, $company, $message]);
            }
            $success_msg = 'Thank you! Your message has been received. Our team will contact you within 24 hours.';
        } catch (Exception $e) {
            $error_msg = 'Something went wrong. Please try again.';
        }
    } else {
        $error_msg = 'Please fill in all required fields.';
    }
}

// Include Header
if (file_exists(__DIR__ . '/../includes/header.php')) {
    require_once __DIR__ . '/../includes/header.php';
} elseif (file_exists('includes/header.php')) {
    require_once 'includes/header.php';
}
?>

<!-- Clean Hero Section -->
<section class="text-white py-5 text-center" style="background-color: #0A2540;">
    <div class="container py-3">
        <h1 class="fw-bold display-6 mb-2">Contact Us</h1>
        <p class="text-white-50 lead fs-6">Get in touch with Pindari Enterprises for verified manpower supply.</p>
    </div>
</section>

<!-- Clean Contact Section -->
<div class="container py-5">
    <div class="row g-4 justify-content-center">
        <!-- Head Office Info Box -->
        <div class="col-lg-4 col-md-5">
            <div class="card p-4 shadow-sm border-0 text-white h-100" style="background-color: #0A2540;">
                <h4 class="fw-bold mb-4 border-bottom pb-2">Head Office</h4>
                <p class="mb-3"><strong>📍 Location:</strong><br>PCMC, Chakan & Ranjangaon MIDC, Pune, Maharashtra</p>
                <p class="mb-3"><strong>📞 Phone:</strong><br>+91 98601 09069</p>
                <p class="mb-0"><strong>✉️ Email:</strong><br>info@pindarienterprises.com</p>
            </div>
        </div>

        <!-- Contact Form Box -->
        <div class="col-lg-7 col-md-7">
            <div class="card p-4 shadow-sm border-0 bg-white">
                <h4 class="fw-bold mb-3" style="color: #0A2540;">Send a Message</h4>

                <?php if (!empty($success_msg)): ?>
                    <div class="alert alert-success"><?php echo $success_msg; ?></div>
                <?php endif; ?>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                <?php endif; ?>

                <form action="contact.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Your Name *</label>
                        <input type="text" name="name" class="form-control" required placeholder="Enter your full name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address *</label>
                        <input type="email" name="email" class="form-control" required placeholder="Enter email address">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mobile Number</label>
                        <input type="tel" name="phone" class="form-control" placeholder="Enter mobile number">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Company / Industry</label>
                        <input type="text" name="company" class="form-control" placeholder="Company name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Message / Requirement *</label>
                        <textarea name="message" class="form-control" rows="4" required placeholder="Write your requirements..."></textarea>
                    </div>
                    <button type="submit" class="btn text-white w-100 fw-bold py-2" style="background-color: #E63946;">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
if (file_exists(__DIR__ . '/../includes/footer.php')) {
    require_once __DIR__ . '/../includes/footer.php';
} elseif (file_exists('includes/footer.php')) {
    require_once 'includes/footer.php';
}
?>
