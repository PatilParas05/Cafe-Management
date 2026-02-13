<?php 
session_start();
require_once __DIR__ . '/config/db.php';

// If already logged in, go to dashboard
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$msg = "";
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        // Insert user into database
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
        
        // Redirect to login after successful registration
        header("Location: login.php?msg=Registration successful! Please log in.");
        exit;
    } catch(PDOException $e) {
        if ($e->getCode() == 23000) { // Integrity constraint violation (duplicate email)
            $msg = "This email is already registered.";
        } else {
            $msg = "An error occurred during registration. Please try again.";
        }
    }
}

// Include header AFTER processing logic
include 'includes/header.php'; 
?>

<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200') center/cover;">
    <div class="card glass-card animate-up" style="width: 100%; max-width: 450px; margin: 0 5%;">
        <h2 style="text-align: center; margin-bottom: 2rem; color: var(--primary);">Join Urban Grind</h2>
        
        <?php if($msg): ?>
            <div style="background: #fff5f5; color: #c53030; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; text-align: center; font-size: 0.85rem; border: 1px solid #feb2b2;">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" required placeholder="John Doe">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="name@example.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Create a strong password">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem; padding: 1rem;">Register Now</button>
        </form>
        
        <p style="text-align: center; margin-top: 2rem; font-size: 0.9rem; color: var(--text-light);">
            Already have an account? <a href="login.php" style="color: var(--accent); font-weight: 700; text-decoration: none;">Sign in here</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>