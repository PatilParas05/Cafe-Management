<?php
session_start();

// Auth Guard
if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/db.php';

// Fetch statistics
$total_customers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_res = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
$pending_res = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'pending'")->fetchColumn();
$total_tables = $pdo->query("SELECT COUNT(*) FROM cafe_tables WHERE status = 'active'")->fetchColumn();
$total_messages = $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();

// Recent reservations
$recent = $pdo->query("SELECT r.*, u.name, u.email FROM reservations r 
                       JOIN users u ON r.user_id = u.id 
                       ORDER BY r.created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Urban Grind Admin</title>
    <link rel="stylesheet" href="../assert/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-layout">
    <div class="sidebar">
        <h2>☕ Urban Grind</h2>
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="reservations.php">Reservations <?php if($pending_res > 0) echo "<span class='badge' style='background: var(--danger); margin-left: auto;'>$pending_res</span>"; ?></a>
        <a href="manage-tables.php">Tables</a>
        <a href="manage-menu.php">Menu</a>
        <a href="manage-contacts.php">Contacts</a>
        <div style="margin-top: auto; padding-top: 2rem; border-top: 2px solid var(--border);">
            <div style="padding: 1.25rem; margin-bottom: 1rem; background: #f1f5f9; border-radius: 12px; border: 1px solid var(--border);">
                <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Administrator</div>
                <div style="font-weight: 800; font-size: 1.1rem; color: var(--text-main);"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></div>
            </div>
            <a href="logout.php" style="color: var(--danger);">Logout</a>
        </div>
    </div>

    <main class="main-content">
        <div style="margin-bottom: 2rem;">
            <h1>Dashboard Overview</h1>
            <p style="color: var(--text-muted); font-size: 0.875rem;">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>! Here's what's happening today.</p>
        </div>
        
        <div class="stats-grid">
            <div class="card stat-card animate-up">
                <div class="label">Total Customers</div>
                <div class="value"><?php echo number_format($total_customers); ?></div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Registered members</div>
            </div>
            <div class="card stat-card animate-up">
                <div class="label">Total Reservations</div>
                <div class="value"><?php echo number_format($total_res); ?></div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">All time bookings</div>
            </div>
            <div class="card stat-card animate-up" style="border-top: 3px solid var(--danger);">
                <div class="label">Total Messages</div>
                <div class="value"><?php echo number_format($total_messages); ?></div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Customer inquiries</div>
            </div>
            <div class="card stat-card animate-up">
                <div class="label">Active Tables</div>
                <div class="value"><?php echo number_format($total_tables); ?></div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Available for booking</div>
            </div>
        </div>

        <div class="card animate-up">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3>Recent Bookings</h3>
                <a href="reservations.php" style="color: var(--accent); text-decoration: none; font-size: 0.875rem; font-weight: 600;">View All →</a>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Date & Time</th>
                            <th>Guests</th>
                            <th>Status</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($recent)): ?>
                            <tr><td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-muted);">No reservations yet.</td></tr>
                        <?php else: ?>
                            <?php foreach($recent as $res): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 600;"><?php echo htmlspecialchars($res['name']); ?></div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo htmlspecialchars($res['email']); ?></div>
                                </td>
                                <td>
                                    <?php echo date('M d, Y', strtotime($res['reservation_date'])); ?><br>
                                    <span style="font-size: 0.875rem; color: var(--text-muted);"><?php echo date('g:i A', strtotime($res['reservation_time'])); ?></span>
                                </td>
                                <td><?php echo $res['num_guests']; ?> people</td>
                                <td><span class="badge badge-<?php echo strtolower($res['status']); ?>"><?php echo ucfirst($res['status']); ?></span></td>
                                <td style="text-align: right;">
                                    <a href="reservations.php" style="color: var(--accent); text-decoration: none; font-size: 0.875rem; font-weight: 600;">Manage →</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script src="../assert/js/main.js"></script>
</body>
</html>