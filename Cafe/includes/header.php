<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Grind | Artisan Coffee & Space</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2d1b0d;
            --accent: #c19a6b;
            --accent-light: #dfc9ae;
            --bg: #faf7f2;
            --text: #1a1a1a;
            --text-light: #666666;
            --white: #ffffff;
            --glass: rgba(255, 255, 255, 0.9);
            --shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
            opacity: 0; 
        }

        /* Navigation Base */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 2rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            transition: var(--transition);
        }

        /* Default Header Styling (for internal pages) */
        nav.internal-nav, nav.scrolled {
            background: var(--glass);
            backdrop-filter: blur(20px);
            padding: 1.2rem 5%;
            box-shadow: var(--shadow);
        }

        /* Logo & Link Colors */
        .logo {
            font-size: 1.6rem;
            font-weight: 800;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: var(--transition);
            letter-spacing: -0.5px;
            color: var(--text); /* Default for internal */
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
            transition: var(--transition);
            color: var(--text); /* Default for internal */
        }

        /* Hero Page Transparent State (Only for index.php) */
        nav.hero-nav:not(.scrolled) .logo,
        nav.hero-nav:not(.scrolled) .nav-links a:not(.btn-nav) {
            color: var(--white);
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .nav-links a:hover {
            color: var(--accent) !important;
        }

        /* Reusable Components */
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .btn {
            padding: 0.8rem 2.2rem;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            font-weight: 700;
            transition: var(--transition);
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-size: 0.9rem;
        }

        /* "Join Us" Button Visibility Fix */
        nav .btn-nav {
            background: var(--accent) !important;
            color: var(--white) !important;
            box-shadow: 0 4px 15px rgba(193, 154, 107, 0.4);
        }

        nav.scrolled .btn-nav, nav.internal-nav .btn-nav {
            background: var(--primary) !important;
        }

        .card {
            background: var(--white);
            border-radius: 28px;
            padding: 3rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid rgba(0,0,0,0.02);
        }

        .form-group label {
            display: block;
            margin-bottom: 0.6rem;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input, select, textarea {
            width: 100%;
            padding: 1.2rem 1.4rem;
            border-radius: 16px;
            border: 1px solid #f0f0f0;
            background: #fcfcfc;
            transition: var(--transition);
            font-size: 1rem;
        }

        .animate-up {
            opacity: 0;
            transform: translateY(25px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .animate-up.visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<?php 
    $current_page = basename($_SERVER['PHP_SELF']);
    $nav_class = ($current_page == 'index.php') ? 'hero-nav' : 'internal-nav';
?>
<body>
    <nav id="navbar" class="<?php echo $nav_class; ?>">
        <a href="index.php" class="logo">
            <span>☕</span> URBAN GRIND
        </a>
        <div class="nav-links">
            <a href="menu.php">Menu</a>
            <a href="reserve.php">Reservations</a>
            <a href="contact.php">Contact</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">My Account</a>
                <a href="logout.php" class="btn btn-nav">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php" class="btn btn-nav">Join Us</a>
            <?php endif; ?>
        </div>
    </nav>