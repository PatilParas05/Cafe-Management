<?php
session_start();

// Database connection
require_once __DIR__ . '/../config/db.php';

// If already logged in, redirect to dashboard
if(isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Simple sanitization
    $username = htmlspecialchars(trim($username));

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid credentials. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Urban Grind</title>
    
    <!-- Using 'assert' to match your directory structure -->
    <link rel="stylesheet" href="../assert/css/style.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* CRITICAL FIXES FOR INTERACTION */
       /* ==========================================
   PREMIUM ADMIN LOGIN UI
   ========================================== */

:root {
    --accent: #c19a6b;
    --accent-dark: #b48a55;
    --white: #ffffff;
    --text-main: #2b2b2b;
    --text-muted: #777;
}

/* ===== FULL PAGE BACKGROUND ===== */

.login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f7f4ef, #ede6dc);
    font-family: 'Segoe UI', sans-serif;
}

/* ===== LOGIN CARD ===== */

.login-card {
    width: 100%;
    max-width: 420px;
    padding: 3rem 2.5rem;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(255,255,255,0.6);
    text-align: center;
    animation: fadeIn 0.5s ease;
}

/* ===== TITLE ===== */

.login-card h1 {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    color: var(--text-main);
}

.login-card p {
    color: var(--text-muted);
    margin-bottom: 2rem;
}

/* ===== FORM ===== */

.form-group {
    text-align: left;
    margin-bottom: 1.5rem;
}

.form-group label {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-main);
    margin-bottom: 0.5rem;
    display: block;
}

/* ===== INPUTS ===== */

.login-container input {
    width: 100%;
    padding: 0.9rem 1.2rem;
    border-radius: 12px;
    border: 1px solid #e6e6e6;
    background: #ffffff;
    font-size: 0.95rem;
    transition: 0.3s ease;
}

.login-container input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(193,154,107,0.2);
}

/* ===== BUTTON ===== */

.btn-primary {
    width: 100%;
    padding: 0.9rem;
    border-radius: 12px;
    background: var(--accent);
    color: white;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: 0.3s ease;
    margin-top: 1rem;
}

.btn-primary:hover {
    background: var(--accent-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(193,154,107,0.3);
}

/* ===== FOOTER ===== */

.login-card small {
    display: block;
    margin-top: 2rem;
    color: var(--text-muted);
    font-size: 0.8rem;
}

/* ===== ANIMATION ===== */

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

        /* Fix for any potential cursor-trail blocking */
        .cursor-dot {
            pointer-events: none !important;
        }
    </style>
</head>
<body class="login-container" style="display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0;">
    <!-- Particle Background -->
    <div id="particles" class="particles"></div>

    <div class="card login-card">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="font-size: 3.5rem; margin-bottom: 10px;">☕</div>
            <h2 style="color: white; font-weight: 800; font-size: 2rem; margin: 0; letter-spacing: -0.5px;">Urban Grind</h2>
            <p style="color: #94a3b8; margin-top: 8px; font-size: 0.95rem;">Administrator Portal</p>
        </div>
        
        <?php if($error): ?>
            <div style="background: rgba(239, 68, 68, 0.15); color: #ff8080; padding: 14px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; border: 1px solid rgba(239, 68, 68, 0.3); text-align: center;">
                ⚠️ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group" style="margin-bottom: 1.2rem;">
                <label style="display:block; margin-bottom: 10px; font-size: 0.9rem;">Username</label>
                <input type="text" name="username" placeholder="Enter username" required autofocus 
                       style="width: 100%; padding: 14px; border-radius: 8px; border: 2px solid rgba(255,255,255,0.1); box-sizing: border-box; font-size: 1rem;">
            </div>
            <div class="form-group" style="margin-bottom: 1.8rem;">
                <label style="display:block; margin-bottom: 10px; font-size: 0.9rem;">Password</label>
                <input type="password" name="password" placeholder="••••••••" required 
                       style="width: 100%; padding: 14px; border-radius: 8px; border: 2px solid rgba(255,255,255,0.1); box-sizing: border-box; font-size: 1rem;">
            </div>
            <button type="submit" class="btn btn-primary ripple" 
                    style="width: 100%; background: #c19a6b; color: white; border: none; padding: 16px; border-radius: 8px; font-weight: 800; cursor: pointer; font-size: 1.1rem; transition: transform 0.2s, background 0.2s;">
                Sign In
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 30px; font-size: 0.8rem; color: #64748b;">
            &copy; <?php echo date('Y'); ?> Urban Grind Coffee House
        </p>
    </div>
    
    <script src="../assert/js/main.js"></script>
</body>
</html>