<?php 
session_start();
require_once __DIR__ . '/config/db.php';

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_contact'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (empty($name) || empty($email) || empty($message)) {
        $msg = "error:Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "error:Please enter a valid email address.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $subject, $message]);
            $msg = "Thank you for reaching out! Our Mumbai team will reply within 24 hours.";
        } catch(PDOException $e) {
            // Check if table exists error
            if ($e->getCode() == '42S02') {
                $msg = "error:Database table 'contacts' missing. Please run the SQL schema.";
            } else {
                $msg = "error:Database error. Please try again later.";
            }
        }
    }
}

include 'includes/header.php'; 
?>

<div style="padding: 8rem 5%; min-height: 80vh;">
    <div class="container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem;">
        <div class="animate-up">
            <h1 style="font-size: 3rem; color: var(--primary); margin-bottom: 1.5rem;">Let's Talk.</h1>
            <p style="color: var(--text-light); margin-bottom: 2rem;">Have a question about our Indian specialties or want to host a private event in Bandra? Send us a message.</p>
            
            <div style="margin-bottom: 2rem;">
                <h4 style="margin-bottom: 0.5rem; color: var(--accent);">Visit Us</h4>
                <p>Plot 45, Perry Road, Bandra West<br>Mumbai, MH 400050</p>
            </div>
            <div>
                <h4 style="margin-bottom: 0.5rem; color: var(--accent);">Call Us</h4>
                <p>+91 22 2640 1234</p>
            </div>
        </div>

        <div class="card glass-card animate-up" style="animation-delay: 0.2s;">
            <?php if($msg): ?>
                <?php $is_error = strpos($msg, 'error:') === 0; ?>
                <div class="alert <?php echo $is_error ? 'alert-danger' : 'alert-success'; ?>" style="display:block; position:static; margin-bottom: 2rem; padding: 1rem; border-radius: 8px; background: <?php echo $is_error ? 'rgba(239, 68, 68, 0.1)' : 'rgba(34, 197, 94, 0.1)'; ?>; color: <?php echo $is_error ? '#ef4444' : '#22c55e'; ?>; border: 1px solid <?php echo $is_error ? 'rgba(239, 68, 68, 0.2)' : 'rgba(34, 197, 94, 0.2)'; ?>;">
                    <?php echo str_replace('error:', '', $msg); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="contact.php">
                <div class="form-group">
                    <label style="display:block; margin-bottom: 8px; font-weight: 700; font-size: 0.75rem; color: var(--text); text-transform: uppercase;">Full Name</label>
                    <input type="text" name="name" required placeholder="Aarav Sharma" style="width: 100%; padding: 1rem; border-radius: 10px; border: 1px solid #eee; margin-bottom: 1rem;">
                </div>
                <div class="form-group">
                    <label style="display:block; margin-bottom: 8px; font-weight: 700; font-size: 0.75rem; color: var(--text); text-transform: uppercase;">Email Address</label>
                    <input type="email" name="email" required placeholder="aarav@example.com" style="width: 100%; padding: 1rem; border-radius: 10px; border: 1px solid #eee; margin-bottom: 1rem;">
                </div>
                <div class="form-group">
                    <label style="display:block; margin-bottom: 8px; font-weight: 700; font-size: 0.75rem; color: var(--text); text-transform: uppercase;">Subject</label>
                    <input type="text" name="subject" required placeholder="How can we help?" style="width: 100%; padding: 1rem; border-radius: 10px; border: 1px solid #eee; margin-bottom: 1rem;">
                </div>
                <div class="form-group">
                    <label style="display:block; margin-bottom: 8px; font-weight: 700; font-size: 0.75rem; color: var(--text); text-transform: uppercase;">Message</label>
                    <textarea name="message" rows="5" required placeholder="Tell us more about your inquiry..." style="width: 100%; padding: 1rem; border-radius: 10px; border: 1px solid #eee; margin-bottom: 1rem; resize: vertical;"></textarea>
                </div>
                <button type="submit" name="send_contact" class="btn btn-primary" style="width: 100%; padding: 1rem; border-radius: 50px; border: none; background: var(--accent); color: white; font-weight: 700; cursor: pointer;">Send Message</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>