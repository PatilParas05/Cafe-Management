<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Grind | Artisan Coffee & Space</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
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
            transition: opacity 0.6s ease;
        }

        /* Navigation Style */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 2.5rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            transition: var(--transition);
        }

        nav.scrolled {
            background: var(--glass);
            backdrop-filter: blur(20px);
            padding: 1.2rem 5%;
            box-shadow: var(--shadow);
        }

        .logo {
            font-size: 1.6rem;
            font-weight: 800;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: var(--transition);
            letter-spacing: -0.5px;
            color: var(--white);
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        nav.scrolled .logo {
            color: var(--text);
            text-shadow: none;
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
            color: var(--white);
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
            transition: var(--transition);
        }

        nav.scrolled .nav-links a {
            color: var(--text);
            text-shadow: none;
        }

        .nav-links a:hover {
            color: var(--accent) !important;
        }

        /* Join Us Button Fix */
        .btn-nav {
            background: var(--accent) !important;
            color: var(--white) !important;
            padding: 0.7rem 1.8rem;
            border-radius: 50px;
            font-weight: 700 !important;
            text-shadow: none !important;
            box-shadow: 0 4px 15px rgba(193, 154, 107, 0.4);
        }

        nav.scrolled .btn-nav {
            background: var(--primary) !important;
        }

        /* Enhanced Hero Styles */
        .hero {
            position: relative;
            overflow: hidden;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(139, 69, 19, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(101, 67, 33, 0.15) 0%, transparent 50%);
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.1); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding-top: 100px; /* Space for Header */
        }

        .hero h1 {
            font-size: clamp(2.5rem, 8vw, 5rem);
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: white;
            line-height: 1.1;
            letter-spacing: -2px;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2.5rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .btn-hero {
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary { background: var(--accent); color: white; }
        .btn-white { background: white; color: var(--primary); }

        .btn-hero:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }

        /* Specialties Section */
        .container { max-width: 1200px; margin: 0 auto; }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
        }

        .card {
            background: white;
            padding: 3rem;
            border-radius: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.4s ease;
            text-align: center;
        }

        .card:hover { transform: translateY(-10px); }
        .coffee-icon { font-size: 3rem; margin-bottom: 1.5rem; display: block; }

        .particles { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; }
        .particle { position: absolute; background: rgba(255,255,255,0.2); border-radius: 50%; animation: rise 15s infinite; }

        @keyframes rise {
            0% { bottom: -10%; opacity: 0; }
            50% { opacity: 1; }
            100% { bottom: 110%; opacity: 0; }
        }

        .animate-up {
            opacity: 0;
            transform: translateY(25px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .animate-up.visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>
    <nav id="navbar">
        <a href="index.php" class="logo">
            <span>☕</span> URBAN GRIND
        </a>
        <div class="nav-links">
            <a href="menu.php">Menu</a>
            <a href="reserve.php">Reservations</a>
            <a href="contact.php">Contact</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">My Account</a>
                <a href="logout.php" class="btn-nav">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php" class="btn-nav">Join Us</a>
            <?php endif; ?>
        </div>
    </nav>

    <header class="hero">
        <div class="particles" id="particles"></div>
        <div class="hero-content animate-up">
            <h1>Artisan Coffee. <br> Modern Space.</h1>
            <p>Your daily ritual, perfected in every drop.</p>
            <div style="display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap;">
                <a href="reserve.php" class="btn-hero btn-primary">Book a Table</a>
                <a href="menu.php" class="btn-hero btn-white">Explore Menu</a>
            </div>
        </div>
    </header>

    <section class="container" style="padding: 10rem 5%;">
        <div style="text-align: center; margin-bottom: 5rem;" class="animate-up">
            <span class="coffee-icon">☕</span>
            <h2 style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;">Our Specialties</h2>
            <p style="color: var(--text-light);">Handcrafted with passion</p>
        </div>

        <div class="stats-grid">
            <div class="card animate-up">
                <span class="coffee-icon">🌱</span>
                <h3>Single Origin</h3>
                <p style="color: var(--text-light); margin-top: 1rem;">Sourced from high-altitude farms in Ethiopia and Colombia, roasted in small batches.</p>
            </div>
            <div class="card animate-up">
                <span class="coffee-icon">🥐</span>
                <h3>House Bakery</h3>
                <p style="color: var(--text-light); margin-top: 1rem;">Freshly baked sourdough and buttery croissants available every morning.</p>
            </div>
            <div class="card animate-up">
                <span class="coffee-icon">👥</span>
                <h3>Community First</h3>
                <p style="color: var(--text-light); margin-top: 1rem;">A space designed for digital nomads, creatives, and coffee lovers.</p>
            </div>
        </div>
    </section>

    <script>
    function createParticles() {
        const container = document.getElementById('particles');
        for (let i = 0; i < 25; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + '%';
            p.style.width = p.style.height = (Math.random() * 5 + 2) + 'px';
            p.style.animationDelay = Math.random() * 15 + 's';
            container.appendChild(p);
        }
    }

    // Navbar Scroll Effect
    window.addEventListener('scroll', () => {
        const nav = document.getElementById('navbar');
        if (window.scrollY > 80) nav.classList.add('scrolled');
        else nav.classList.remove('scrolled');
    });

    // Intersection Observer for animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    document.addEventListener('DOMContentLoaded', () => {
        createParticles();
        document.body.style.opacity = '1';
        document.querySelectorAll('.animate-up').forEach(el => observer.observe(el));
    });
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html>