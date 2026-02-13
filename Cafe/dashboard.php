<?php 
session_start();

// FIX: Pointing to the correct path 'config/db.php' using __DIR__ for reliability
require_once __DIR__ . '/config/db.php';

// Auth Guard: Move to the top to prevent "Headers already sent"
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$msg = $_GET['msg'] ?? "";

// Handle Cancellation
if(isset($_GET['cancel'])) {
    $stmt = $pdo->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['cancel'], $user_id]);
    header("Location: dashboard.php?msg=Reservation successfully cancelled");
    exit;
}

$stmt = $pdo->prepare("SELECT r.*, t.table_number FROM reservations r 
                       JOIN cafe_tables t ON r.table_id = t.id 
                       WHERE r.user_id = ? ORDER BY r.reservation_date DESC, r.reservation_time DESC");
$stmt->execute([$user_id]);
$reservations = $stmt->fetchAll();

include 'includes/header.php'; 
?>

<div style="padding: 10rem 5% 6rem; min-height: 90vh;">
    <div class="container">
        <!-- Dashboard Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem;" class="animate-up">
            <div>
                <span style="color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem;">Member Dashboard</span>
                <h1 style="font-size: 3rem; color: var(--primary); margin-top: 0.5rem;">Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>.</h1>
            </div>
            <a href="reserve.php" class="btn btn-primary">Book New Table</a>
        </div>

        <?php if($msg): ?>
            <div class="alert alert-success animate-up" style="margin-bottom: 3rem; border: 1px solid #c6f6d5;">
                ✨ <?php echo htmlspecialchars($msg); ?>
            </div>
        <?php endif; ?>

        <!-- Stats Overview -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 4rem;">
            <div class="card animate-up" style="padding: 2rem; text-align: left;">
                <p style="color: var(--text-light); font-size: 0.9rem; font-weight: 600;">Total Bookings</p>
                <h2 style="font-size: 2.5rem; color: var(--primary); margin-top: 0.5rem;"><?php echo count($reservations); ?></h2>
            </div>
            <div class="card animate-up" style="padding: 2rem; text-align: left;">
                <p style="color: var(--text-light); font-size: 0.9rem; font-weight: 600;">Active Reservations</p>
                <h2 style="font-size: 2.5rem; color: var(--accent); margin-top: 0.5rem;">
                    <?php 
                        $active = array_filter($reservations, function($r) { 
                            return $r['status'] == 'pending' || $r['status'] == 'confirmed'; 
                        });
                        echo count($active);
                    ?>
                </h2>
            </div>
            <div class="card animate-up" style="padding: 2rem; text-align: left; border-left: 4px solid var(--accent);">
                <p style="color: var(--text-light); font-size: 0.9rem; font-weight: 600;">Next Coffee</p>
                <h2 style="font-size: 1.5rem; color: var(--primary); margin-top: 0.5rem;">
                    <?php 
                        // Get upcoming reservations (pending or confirmed, and in the future)
                        $today = date('Y-m-d');
                        $upcoming = array_filter($reservations, function($r) use ($today) { 
                            return ($r['status'] == 'pending' || $r['status'] == 'confirmed') 
                                   && $r['reservation_date'] >= $today; 
                        });
                        
                        if (!empty($upcoming)) {
                            $next = reset($upcoming);
                            echo date('M d, Y', strtotime($next['reservation_date'])) . '<br>';
                            echo '<span style="font-size: 1rem; color: var(--text-light);">' . 
                                 date('g:i A', strtotime($next['reservation_time'])) . '</span>';
                        } else {
                            echo "No bookings";
                        }
                    ?>
                </h2>
            </div>
        </div>

        <!-- Reservations List -->
        <div class="card animate-up" style="overflow: hidden; padding: 0;">
            <div style="padding: 2rem; border-bottom: 1px solid #f0f0f0;">
                <h3 style="color: var(--primary);">Your Booking History</h3>
            </div>
            
            <div style="overflow-x: auto; padding: 1rem 2rem 2rem;">
                <table style="width: 100%; border-spacing: 0;">
                    <thead>
                        <tr style="border-bottom: 2px solid #faf7f2;">
                            <th style="padding: 1.5rem 1rem; text-align: left;">DATE</th>
                            <th style="padding: 1.5rem 1rem; text-align: left;">TIME</th>
                            <th style="padding: 1.5rem 1rem; text-align: left;">TABLE</th>
                            <th style="padding: 1.5rem 1rem; text-align: left;">GUESTS</th>
                            <th style="padding: 1.5rem 1rem; text-align: left;">STATUS</th>
                            <th style="padding: 1.5rem 1rem; text-align: right;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($reservations)): ?>
                            <tr>
                                <td colspan="6" style="padding: 4rem; text-align: center; color: var(--text-light);">
                                    You haven't made any reservations yet. 
                                    <br><a href="reserve.php" style="color: var(--accent); font-weight: 700; text-decoration: none; margin-top: 1rem; display: inline-block;">Book your first table &rarr;</a>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach($reservations as $r): ?>
                            <tr style="border-bottom: 1px solid #fafafa;">
                                <td style="padding: 1.2rem 1rem; font-weight: 700; color: var(--primary);"><?php echo date('M d, Y', strtotime($r['reservation_date'])); ?></td>
                                <td style="padding: 1.2rem 1rem;"><?php echo date('g:i A', strtotime($r['reservation_time'])); ?></td>
                                <td style="padding: 1.2rem 1rem;"><span style="background: #faf7f2; padding: 0.4rem 0.8rem; border-radius: 8px; font-size: 0.8rem;">#<?php echo $r['table_number']; ?></span></td>
                                <td style="padding: 1.2rem 1rem;"><?php echo $r['num_guests']; ?> People</td>
                                <td style="padding: 1.2rem 1rem;">
                                    <?php 
                                        $status_color = match($r['status']) {
                                            'confirmed' => '#2f855a',
                                            'cancelled' => '#c53030',
                                            default => '#c19a6b'
                                        };
                                        $status_bg = match($r['status']) {
                                            'confirmed' => '#f0fff4',
                                            'cancelled' => '#fff5f5',
                                            default => '#fffaf0'
                                        };
                                    ?>
                                    <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; padding: 0.4rem 1rem; border-radius: 50px; background: <?php echo $status_bg; ?>; color: <?php echo $status_color; ?>;">
                                        <?php echo $r['status']; ?>
                                    </span>
                                </td>
                                <td style="padding: 1.2rem 1rem; text-align: right;">
                                    <?php if($r['status'] == 'pending'): ?>
                                        <a href="?cancel=<?php echo $r['id']; ?>" 
                                           onclick="return confirm('Are you sure you want to cancel this reservation?')" 
                                           style="color: #c53030; text-decoration: none; font-size: 0.8rem; font-weight: 700; border: 1px solid #feb2b2; padding: 0.4rem 1rem; border-radius: 50px; transition: 0.3s;"
                                           onmouseover="this.style.background='#fff5f5'"
                                           onmouseout="this.style.background='transparent'">
                                            Cancel
                                        </a>
                                    <?php else: ?>
                                        <span style="opacity: 0.3;">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>