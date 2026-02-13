<?php 
session_start();

// Database connection MUST come first
require_once __DIR__ . '/config/db.php';

// Auth Guard: Check before any output
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$msg = "";
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $guests = $_POST['guests'];
    
    // Check available table
    $stmt = $pdo->prepare("SELECT id FROM cafe_tables WHERE capacity >= ? AND status = 'active' LIMIT 1");
    $stmt->execute([$guests]);
    $table = $stmt->fetch();

    if($table) {
        $stmt = $pdo->prepare("INSERT INTO reservations (user_id, table_id, reservation_date, reservation_time, num_guests) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $table['id'], $date, $time, $guests]);
        
        // Redirect to dashboard with success message (POST/Redirect/GET pattern)
        header("Location: dashboard.php?msg=Reservation successful! We look forward to seeing you.");
        exit;
    } else {
        $msg = "No tables available for the selected group size. Please try a different date or smaller group.";
    }
}

include 'includes/header.php'; 
?>

<div style="padding: 8rem 5%; background: #FDFBF9; min-height: 100vh;">
    <div class="container" style="max-width: 600px;">
        <div class="card glass-card animate-up">
            <h2 style="margin-bottom: 2rem; color: var(--primary);">Book Your Experience</h2>
            
            <?php if($msg): ?>
                <div class="alert alert-error animate-up" style="display:block; position:static; margin-bottom: 2rem; background: #fff5f5; border: 1px solid #feb2b2; color: #c53030; padding: 1rem; border-radius: 12px;">
                    ⚠️ <?php echo htmlspecialchars($msg); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Preferred Date</label>
                    <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>" style="width: 100%; padding: 0.8rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                </div>
                <div class="form-group">
                    <label>Preferred Time</label>
                    <select name="time" required style="width: 100%; padding: 0.8rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                        <option value="">Select a time...</option>
                        <option value="09:00">09:00 AM - Morning Coffee</option>
                        <option value="12:00">12:00 PM - Lunch</option>
                        <option value="15:00">03:00 PM - Afternoon Tea</option>
                        <option value="18:00">06:00 PM - Evening</option>
                        <option value="20:00">08:00 PM - Dinner</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Number of Guests</label>
                    <select name="guests" required style="width: 100%; padding: 0.8rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                        <option value="">Select party size...</option>
                        <option value="1">1 Person</option>
                        <option value="2">2 People</option>
                        <option value="4">4 People</option>
                        <option value="6">6 People</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem;">Confirm Booking</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>