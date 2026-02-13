<?php
session_start();

// Auth Guard
if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/db.php';

// Handle message deletion
if(isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-contacts.php?msg=deleted");
    exit;
}

// Fetch all messages
$messages = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Messages | Urban Grind Admin</title>
    <link rel="stylesheet" href="../assert/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-layout">
    <div class="sidebar">
        <h2>☕ Urban Grind</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="reservations.php">Reservations</a>
        <a href="manage-tables.php">Tables</a>
        <a href="manage-menu.php">Menu</a>
        <a href="manage-contacts.php" class="active">Contacts</a>
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
            <h1>Customer Inquiries</h1>
            <p style="color: var(--text-muted); font-size: 0.875rem;">Messages received from the contact form.</p>
        </div>

        <div class="card animate-up">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Sender</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($messages)): ?>
                            <tr><td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-muted);">No messages yet.</td></tr>
                        <?php else: ?>
                            <?php foreach($messages as $m): ?>
                            <tr>
                                <td style="white-space: nowrap; font-size: 0.85rem; color: var(--text-muted);">
                                    <?php echo date('M d, Y', strtotime($m['created_at'])); ?>
                                </td>
                                <td>
                                    <div style="font-weight: 600;"><?php echo htmlspecialchars($m['name']); ?></div>
                                    <div style="font-size: 0.75rem; color: var(--accent);"><?php echo htmlspecialchars($m['email']); ?></div>
                                </td>
                                <td style="font-weight: 600;"><?php echo htmlspecialchars($m['subject']); ?></td>
                                <td style="font-size: 0.875rem; color: var(--text-muted); max-width: 400px;"><?php echo nl2br(htmlspecialchars($m['message'])); ?></td>
                                <td style="text-align: right;">
                                    <a href="?del=<?php echo $m['id']; ?>" 
                                       onclick="return confirm('Delete this message?')" 
                                       style="color: var(--danger); text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                                        🗑 Delete
                                    </a>
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