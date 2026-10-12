<?php
require_once __DIR__ . '/../config/config.php';
$pageTitle = 'Contact Us';
$currentPage = 'contact';

// Include header safely
if (file_exists(__DIR__ . '/../includes/header.php')) {
    require_once __DIR__ . '/../includes/header.php';
} elseif (file_exists('includes/header.php')) {
    require_once 'includes/header.php';
}

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
            if (function_exists('db')) {
                $stmt = db()->prepare("INSERT INTO contact_messages (name, email, phone, company, message) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $email, $phone, $company, $message]);
            }
            if (function_exists('set_flash')) {
                set_flash('success', 'Thank you! Your message has been received. Our team will contact you within 24 hours.');
            }
            if (function_exists('redirect')) {
                redirect(APP_URL . '/public/contact.php');
            }
        } catch (Exception $e) {
            $error_msg = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!-- Contact Section Layout -->
<section class="bg-navy text-white py-5 text-center" style="background-color: #0A2540;">
    <div class="container">
        <h1 class="fw-bold mb-2">Contact Us</h1>
        <p class="text-white-50">Get in touch with Pindari Enterprises for verified manpower supply.</p>
    </div>
</section>

<div class="container py-5">
    <div class="row g-4">
        <!-- Head Office Info Box -->
        <div class="col-md-5">
            <div class="card p-4 shadow-sm border-0 text-white h-100" style="background-color: #0A2540;">
                <h3 class="fw-bold mb-3">Head Office</h3>
                <p class="mb-3">📍 PCMC, Chakan & Ranjangaon MIDC, Pune, Maharashtra</p>
                <p class="mb-3">📞 Phone: +91 98601 09069</p>
                <p class="mb-0">✉️ Email: info@pindarienterprises.com</p>
            </div>
        </div>

        <!-- Contact Form Box -->
        <div class="col-md-7">
            <div class="card p-4 shadow-sm border-0">
                <h3 class="fw-bold mb-3" style="color: #0A2540;">Send a Message</h3>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $err): ?>
                                <li><?php echo htmlspecialchars($err); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="contact.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Your Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="Enter your full name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="Enter email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mobile Number</label>
                        <input type="tel" name="phone" class="form-control" placeholder="Enter mobile number">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Company / Industry</label>
                        <input type="text" name="company" class="form-control" placeholder="Company name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message / Requirement</label>
                        <textarea name="message" class="form-control" rows="4" required placeholder="Write your requirements..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-bold" style="background-color: #E63946; border-color: #E63946;">Send Message</button>
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
