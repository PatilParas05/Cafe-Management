<?php
session_start();

if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/db.php';

$msg = "";
if(isset($_POST['add'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $cat = htmlspecialchars(trim($_POST['category']));
    $desc = htmlspecialchars(trim($_POST['description']));
    $price = floatval($_POST['price']);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO menu_items (name, category, description, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $cat, $desc, $price]);
        $msg = "Item '$name' added successfully!";
    } catch(PDOException $e) { $msg = "error:Failed to add item."; }
}

if(isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-menu.php?msg=deleted");
    exit;
}

$items = $pdo->query("SELECT * FROM menu_items ORDER BY category, name")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu | Urban Grind Admin</title>
    <link rel="stylesheet" href="../assert/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-layout">
    <div class="sidebar">
        <h2><span>☕</span> Urban Grind</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="reservations.php">Reservations</a>
        <a href="manage-tables.php">Tables</a>
        <a href="manage-menu.php" class="active">Menu</a>
        <a href="manage-contacts.php">Contacts</a>
        <div style="margin-top: auto; padding-top: 2rem; border-top: 2px solid var(--border);">
            <div style="padding: 1.25rem; margin-bottom: 1rem; background: #f1f5f9; border-radius: 12px; border: 1px solid var(--border);">
                <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Administrator</div>
                <div style="font-weight: 800; font-size: 1.1rem; color: var(--text-main);"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></div>
            </div>
            <a href="logout.php" style="color: var(--danger); background: transparent; padding-left: 1.25rem;">Logout</a>
        </div>
    </div>

    <main class="main-content">
        <h1 style="margin-bottom: 1rem;">Menu Management</h1>
        
        <div class="card">
            <h3 style="margin-bottom: 2rem; font-size: 1.5rem; font-weight: 800;">Add New Menu Item</h3>
            <form method="POST">
                <div style="display: grid; grid-template-columns: 2fr 1.5fr 1fr; gap: 2rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label>Item Name</label>
                        <input type="text" name="name" required placeholder="e.g. Masala Chai">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" required>
                            <option value="Coffee">Coffee</option>
                            <option value="Tea">Teas</option>
                            <option value="Food">Food & Snacks</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Price (₹)</label>
                        <input type="number" step="0.01" name="price" required placeholder="250">
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" required placeholder="Describe the flavors and ingredients..."></textarea>
                </div>
                <button type="submit" name="add" class="btn btn-primary" style="height: 55px; width: 220px;">Add Item</button>
            </form>
        </div>

        <div class="card" style="padding: 0;">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="padding-left: 2rem;">Item Details</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th style="text-align: right; padding-right: 2rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($items as $i): ?>
                        <tr>
                            <td style="padding-left: 2rem;">
                                <div style="font-weight: 800; font-size: 1.2rem; color: var(--text-main);"><?php echo htmlspecialchars($i['name']); ?></div>
                            </td>
                            <td><span style="font-weight: 700; color: var(--accent); background: var(--accent-light); padding: 0.4rem 1rem; border-radius: 8px; font-size: 0.9rem; text-transform: uppercase;"><?php echo $i['category']; ?></span></td>
                            <td style="color: var(--text-muted); font-size: 1rem; max-width: 400px;"><?php echo htmlspecialchars($i['description']); ?></td>
                            <td style="font-weight: 800; font-size: 1.25rem;">₹<?php echo number_format($i['price'], 2); ?></td>
                            <td style="text-align: right; padding-right: 2rem;">
                                <a href="?del=<?php echo $i['id']; ?>" onclick="return confirm('Delete this item?')" style="color: var(--danger); font-weight: 800; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; background: #fee2e2;">🗑 Remove</a>
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