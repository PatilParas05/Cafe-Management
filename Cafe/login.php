<?php 
// 1. session_start() MUST be the very first thing in the file
session_start();

// 2. Load the database connection using the absolute path to your config folder
// Based on your directory structure, db.php is in the 'config' folder
require_once __DIR__ . '/config/db.php';

/**
 * REDIRECT LOOP FIX:
 * We only redirect to dashboard if the user IS logged in.
 * If they are NOT logged in, we simply stay on this page to show the form.
 */
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input using the function defined in your db.php
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    // Select the user from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verify password and set session
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Incorrect email or password. Please try again.";
    }
}

// 3. Include the header AFTER all potential header("Location: ...") calls
include 'includes/header.php'; 
?>

<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1200') center/cover;">
    <div class="card glass-card animate-up" style="width: 100%; max-width: 400px; margin: 0 5%; background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); border-radius: 24px; padding: 2.5rem; box-shadow: var(--shadow);">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <span style="font-size: 3rem;">☕</span>
            <h2 style="color: var(--primary); margin-top: 1rem; font-size: 1.8rem; font-weight: 800;">Member Login</h2>
            <p style="color: var(--text-light); font-size: 0.9rem; margin-top: 0.5rem;">Enter your details to access your account</p>
        </div>
        
        <?php if ($error): ?>
            <div style="background: #fff5f5; color: #c53030; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; text-align: center; font-size: 0.85rem; border: 1px solid #feb2b2; font-weight: 600;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success" style="margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 600;">
                <?php echo sanitize($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="name@example.com" style="background: #fdfcfb;">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••" style="background: #fdfcfb;">
            </div>
            <button type="submit" class="btn-primary" style="width: 100%; margin-top: 1rem; border: none; cursor: pointer; padding: 1.1rem; border-radius: 50px; font-size: 1rem; color: white; background: var(--accent); font-weight: 700;">Sign In</button>
        </form>

        <p style="text-align: center; margin-top: 2.5rem; font-size: 0.9rem; color: var(--text-light);">
            New to Urban Grind? <a href="register.php" style="color: var(--accent); font-weight: 700; text-decoration: none;">Create an account</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>