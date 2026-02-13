<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/../config/db.php';

$msg = "";
if(isset($_POST['add'])) {
    $num = htmlspecialchars(trim($_POST['num']));
    $cap = (int)$_POST['cap'];
    try {
        $stmt = $pdo->prepare("INSERT INTO cafe_tables (table_number, capacity, status) VALUES (?, ?, 'active')");
        $stmt->execute([$num, $cap]);
        $msg = "Table #$num added!";
    } catch(PDOException $e) { $msg = "error:Already exists."; }
}

if(isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    $stmt = $pdo->prepare("DELETE FROM cafe_tables WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-tables.php");
    exit;
}

$tables = $pdo->query("SELECT * FROM cafe_tables ORDER BY table_number")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tables | Urban Grind Admin</title>
    <link rel="stylesheet" href="../assert/css/style.css">
</head>
<body class="admin-layout">
    <div class="sidebar">
        <h2><span>☕</span> Urban Grind</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="reservations.php">Reservations</a>
        <a href="manage-tables.php" class="active">Tables</a>
        <a href="manage-menu.php">Menu</a>
        <a href="manage-contacts.php">Contacts</a>
       <div style="margin-top: auto; padding-top: 2rem; border-top: 2px solid var(--border);">
            <div style="padding: 1.25rem; margin-bottom: 1rem; background: #f1f5f9; border-radius: 12px; border: 1px solid var(--border);">
                <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Administrator</div>
                <div style="font-weight: 800; font-size: 1.1rem; color: var(--text-main);"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></div>
            </div>
            <a href="logout.php" style="color: var(--danger); background: transparent; padding-left: 1.25rem; margin-top: 1rem;">Logout</a>
        </div>
    </div>

    <main class="main-content">
        <h1>Seating Arrangement</h1>
        <div style="display: grid; grid-template-columns: 450px 1fr; gap: 4rem;">
            <div class="card">
                <h3 style="margin-bottom: 2rem; font-size: 1.5rem; font-weight: 800;">Add New Table</h3>
                <form method="POST">
                    <div class="form-group">
                        <label>Table Number / ID</label>
                        <input type="text" name="num" placeholder="M-10" required>
                    </div>
                    <div class="form-group">
                        <label>Guests Capacity</label>
                        <select name="cap" required>
                            <option value="2">2 People</option>
                            <option value="4">4 People</option>
                            <option value="6">6 People</option>
                        </select>
                    </div>
                    <button type="submit" name="add" class="btn btn-primary" style="width: 100%; height: 55px;">Add Table</button>
                </form>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 2rem;">
                <?php foreach($tables as $t): ?>
                <div class="card" style="text-align: center; border-bottom: 6px solid <?php echo $t['status'] == 'active' ? '#10b981' : '#cbd5e1'; ?>;">
                    <div style="font-size: 1.8rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem;"><?php echo $t['table_number']; ?></div>
                    <div style="font-size: 1.1rem; font-weight: 600; color: var(--text-muted); margin-bottom: 1.5rem;"><?php echo $t['capacity']; ?> Seats</div>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <span class="badge badge-<?php echo $t['status']; ?>"><?php echo $t['status']; ?></span>
                        <a href="?del=<?php echo $t['id']; ?>" onclick="return confirm('Delete?')" style="color: var(--danger); font-weight: 800; text-decoration: none; font-size: 1rem;">🗑 Remove</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</body>
</html>