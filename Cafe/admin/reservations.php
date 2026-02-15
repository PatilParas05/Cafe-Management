<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/../config/db.php';

// Handle status updates
if(isset($_GET['status']) && isset($_GET['id'])) {
    $stmt = $pdo->prepare("UPDATE reservations SET status = ? WHERE id = ?");
    $stmt->execute([$_GET['status'], (int)$_GET['id']]);
    header("Location: reservations.php");
    exit;
}

// Fetch all reservations with detailed information
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
    <!-- PDF Generation Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    
    <style>
        /* Print-specific styles to ensure a clean document view */
        @media print {
            .sidebar, .export-controls, .management-actions-col {
                display: none !important;
            }
            .main-content {
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            table {
                width: 100% !important;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid #cbd5e1 !important;
                padding: 12px !important;
                color: #000 !important;
            }
            h1 { font-size: 24pt !important; margin-bottom: 20pt !important; }
        }
    </style>
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
                <div style="font-weight: 800; font-size: 1.1rem; color: var(--text-main);"><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></div>
            </div>
            <a href="logout.php" style="color: var(--danger); background: transparent; padding-left: 1.25rem; display: block; text-decoration: none; font-weight: 700;">Logout</a>
        </div>
    </div>

    <main class="main-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3.5rem;">
            <div>
                <h1 style="margin: 0;">Booking Management</h1>
                <p style="color: var(--text-muted); font-size: 1.1rem; margin-top: 5px;">Review and manage customer reservations.</p>
            </div>
            <div class="export-controls" style="display: flex; gap: 15px;">
                <button onclick="window.print()" class="btn" style="background: #64748b; color: white; display: flex; align-items: center; gap: 10px; border-radius: 8px;">
                    🖨️ Print Page
                </button>
                <button onclick="exportToPDF()" class="btn btn-primary" style="background: #4338ca; color: white; display: flex; align-items: center; gap: 10px; border-radius: 8px;">
                    📄 Export PDF
                </button>
            </div>
        </div>

        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="table-container">
                <table id="reservationsTable">
                    <thead>
                        <tr>
                            <th style="padding-left: 2rem;">Customer Info</th>
                            <th>Visit Schedule</th>
                            <th>Table & Guests</th>
                            <th>Current Status</th>
                            <th class="management-actions-col" style="text-align: center; padding-right: 2rem;">Management Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($all)): ?>
                            <tr><td colspan="5" style="text-align: center; padding: 6rem; color: var(--text-muted); font-size: 1.25rem;">No reservations found.</td></tr>
                        <?php else: ?>
                            <?php foreach($all as $res): ?>
                            <tr>
                                <td style="padding-left: 2rem;">
                                    <div style="font-weight: 800; font-size: 1.15rem; color: var(--text-main);"><?php echo htmlspecialchars($res['customer']); ?></div>
                                    <div style="font-size: 1rem; color: var(--accent); font-weight: 700; margin-top: 2px;"><?php echo htmlspecialchars($res['email']); ?></div>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: var(--text-main);"><?php echo date('d M Y', strtotime($res['reservation_date'])); ?></div>
                                    <div style="font-size: 1rem; color: var(--text-muted); font-weight: 600;"><?php echo date('h:i A', strtotime($res['reservation_time'])); ?></div>
                                </td>
                                <td>
                                    <div style="font-weight: 800; color: var(--text-main); font-size: 1.1rem;">#<?php echo $res['table_number']; ?></div>
                                    <div style="font-size: 0.95rem; font-weight: 600; color: var(--text-muted);"><?php echo $res['num_guests']; ?> People</div>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo strtolower($res['status']); ?>">
                                        <?php echo ucfirst($res['status']); ?>
                                    </span>
                                </td>
                                <td class="management-actions-col" style="text-align: center; padding-right: 2rem;">
                                    <?php if($res['status'] == 'pending'): ?>
                                        <div style="display: flex; gap: 1rem; justify-content: center;">
                                            <a href="?status=confirmed&id=<?php echo $res['id']; ?>" style="color: white; background: #059669; padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 800; font-size: 0.95rem;">Approve</a>
                                            <a href="?status=cancelled&id=<?php echo $res['id']; ?>" style="color: white; background: #dc2626; padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 800; font-size: 0.95rem;">Reject</a>
                                        </div>
                                    <?php else: ?>
                                        <span style="font-weight: 700; color: var(--text-muted); font-size: 0.95rem;">Processed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        // Professional Header
        doc.setFillColor(67, 56, 202); // Dark Indigo
        doc.rect(0, 0, 297, 30, 'F');

        doc.setFontSize(22);
        doc.setTextColor(255, 255, 255);
        doc.text("Urban Grind - Reservation Audit Report", 14, 20);
        
        doc.setFontSize(10);
        doc.setTextColor(200, 200, 200);
        doc.text("Generated: " + new Date().toLocaleString(), 230, 20);

        // Data Preparation
        const rows = [];
        const table = document.getElementById('reservationsTable');
        const trs = table.querySelectorAll('tbody tr');

        trs.forEach(tr => {
            const cells = tr.querySelectorAll('td');
            if (cells.length < 4) return;

            const customer = cells[0].innerText.trim().replace(/\n/g, ' ');
            const visit = cells[1].innerText.trim().replace(/\n/g, ' ');
            const allocation = cells[2].innerText.trim().replace(/\n/g, ' ');
            const status = cells[3].innerText.trim();
            
            rows.push([customer, visit, allocation, status]);
        });

        // Professional Table Generation
        doc.autoTable({
            head: [['Customer Profile', 'Scheduled Slot', 'Seating/Guests', 'Reservation Status']],
            body: rows,
            startY: 40,
            theme: 'grid',
            headStyles: { 
                fillColor: [67, 56, 202], 
                textColor: [255, 255, 255],
                fontSize: 12,
                fontStyle: 'bold',
                halign: 'center'
            },
            styles: { 
                fontSize: 10, 
                cellPadding: 5,
                valign: 'middle'
            },
            columnStyles: {
                0: { cellWidth: 80 },
                1: { cellWidth: 60, halign: 'center' },
                2: { cellWidth: 50, halign: 'center' },
                3: { cellWidth: 40, halign: 'center', fontStyle: 'bold' }
            },
            didParseCell: function(data) {
                if (data.section === 'body' && data.column.index === 3) {
                    const statusVal = data.cell.raw.toLowerCase();
                    if (statusVal.includes('confirmed')) {
                        data.cell.styles.textColor = [5, 150, 105]; // Emerald
                    } else if (statusVal.includes('cancelled')) {
                        data.cell.styles.textColor = [220, 38, 38]; // Red
                    } else if (statusVal.includes('pending')) {
                        data.cell.styles.textColor = [217, 119, 6]; // Amber
                    }
                }
            }
        });

        // Professional Footer
        const finalY = doc.lastAutoTable.finalY + 15;
        doc.setFontSize(10);
        doc.setTextColor(100, 116, 139);
        doc.text("Urban Grind Café Mumbai — Private Administrative Document", 14, finalY);

        doc.save(`UrbanGrind_Bookings_${new Date().toISOString().slice(0,10)}.pdf`);
    }
    </script>
    <script src="../assert/js/main.js"></script>
</body>
</html>