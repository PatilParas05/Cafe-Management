<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/../config/db.php';

if(isset($_GET['status']) && isset($_GET['id'])) {
    $stmt = $pdo->prepare("UPDATE reservations SET status = ? WHERE id = ?");
    $stmt->execute([$_GET['status'], (int)$_GET['id']]);
    header("Location: reservations.php");
    exit;
}

$all = $pdo->query("SELECT r.*, u.name as customer, u.email, t.table_number 
                     FROM reservations r 
                     JOIN users u ON r.user_id = u.id 
                     JOIN cafe_tables t ON r.table_id = t.id 
                     ORDER BY r.reservation_date DESC, r.reservation_time DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reservations | Urban Grind Admin</title>
    <link rel="stylesheet" href="../assert/css/style.css">
</head>
<body class="admin-layout">
    <div class="sidebar">
        <h2><span>☕</span> Urban Grind</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="reservations.php" class="active">Reservations</a>
        <a href="manage-tables.php">Tables</a>
        <a href="manage-menu.php">Menu</a>
        <a href="manage-contacts.php">Contacts</a>
       <div style="margin-top: auto; padding-top: 2rem; border-top: 2px solid var(--border);">
            <div style="padding: 1.25rem; margin-bottom: 1rem; background: #f1f5f9; border-radius: 12px; border: 1px solid var(--border);">
                <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Administrator</div>
                <div style="font-weight: 800; font-size: 1.1rem; color: var(--text-main);"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></div>
            </div>
            <a href="logout.php" style="color: var(--danger); margin-top: 1rem; padding-left: 1.25rem; display: block;">Logout</a>
        </div>
    </div>

    <main class="main-content">
        <h1>Booking Management</h1>
        <div class="card" style="padding: 0;">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="padding-left: 2rem;">Customer Info</th>
                            <th>Visit Schedule</th>
                            <th>Table & Guests</th>
                            <th>Current Status</th>
                            <th style="text-align: center; padding-right: 2rem;">Management Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($all as $res): ?>
                        <tr>
                            <td style="padding-left: 2rem;">
                                <div style="font-weight: 800; font-size: 1.15rem; color: var(--text-main);"><?php echo htmlspecialchars($res['customer']); ?></div>
                                <div style="font-size: 1rem; color: var(--accent); font-weight: 700;"><?php echo htmlspecialchars($res['email']); ?></div>
                            </td>
                            <td>
                                <div style="font-weight: 700;"><?php echo date('d M Y', strtotime($res['reservation_date'])); ?></div>
                                <div style="font-size: 1rem; color: var(--text-muted); font-weight: 600;"><?php echo date('h:i A', strtotime($res['reservation_time'])); ?></div>
                            </td>
                            <td>
                                <div style="font-weight: 800; color: var(--text-main);">#<?php echo $res['table_number']; ?></div>
                                <div style="font-size: 1rem; font-weight: 600;"><?php echo $res['num_guests']; ?> People</div>
                            </td>
                            <td><span class="badge badge-<?php echo strtolower($res['status']); ?>"><?php echo $res['status']; ?></span></td>
                            <td style="text-align: center; padding-right: 2rem;">
                                <?php if($res['status'] == 'pending'): ?>
                                    <div style="display: flex; gap: 1rem; justify-content: center;">
                                        <a href="?status=confirmed&id=<?php echo $res['id']; ?>" style="color: white; background: #059669; padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 800; font-size: 0.95rem;">Approve</a>
                                        <a href="?status=cancelled&id=<?php echo $res['id']; ?>" style="color: white; background: #dc2626; padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 800; font-size: 0.95rem;">Reject</a>
                                    </div>
                                <?php else: ?>
                                    <span style="font-weight: 700; color: var(--text-muted);">Handled</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>