<?php
require_once __DIR__ . '/db.php';
requireLogin();
$user = currentUser();
$message = '';
$package = $_GET['package'] ?? '';
$packageList = $pdo->query("SELECT name FROM packages ORDER BY price ASC")->fetchAll(PDO::FETCH_COLUMN);

if ($package !== '' && !in_array($package, $packageList, true)) {
    $package = '';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_date = $_POST['event_date'] ?? '';
    $event_type = trim($_POST['event_type'] ?? '');
    $venue = trim($_POST['venue'] ?? '');
    $guests = intval($_POST['guests'] ?? 0);
    $message_text = trim($_POST['message'] ?? '');
    $selected_package = $_POST['package_type'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $payment_pin = trim($_POST['payment_pin'] ?? '');

    if (!$event_date || !$event_type || !$venue || $guests <= 0 || !$payment_method || !$selected_package) {
        $message = 'Please complete all required booking fields.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO bookings (user_id, event_date, package_type, event_type, venue, guests, message, payment_method, payment_pin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$user['id'], $event_date, $selected_package, $event_type, $venue, $guests, $message_text, $payment_method, $payment_pin]);
        $message = 'Your booking request was submitted successfully. Check your dashboard for approval status.';
    }
}
?>
<?php include 'header.php'; ?>
<style>
    .booking-page {
        background: url('https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=1600&q=80') center/cover fixed;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem 2rem;
        position: relative;
    }
    .booking-page::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.85);
        z-index: 1;
    }
    .booking-card-form {
        position: relative;
        z-index: 2;
        background: transparent;
        width: 100%;
        max-width: 600px;
    }
    .booking-card-form h2 {
        color: white;
        text-align: left;
        margin-bottom: 0.5rem;
        font-size: 2.5rem;
    }
    .booking-card-form p.subtitle {
        color: #b75a3b;
        margin-bottom: 2.5rem;
        font-size: 0.95rem;
        letter-spacing: 1px;
    }
    .booking-card-form label {
        color: white;
        font-weight: 500;
        margin-bottom: 10px;
        display: block;
        font-size: 0.9rem;
    }
    .booking-card-form .input-group {
        margin-bottom: 1.8rem;
    }
    .booking-card-form input, 
    .booking-card-form select, 
    .booking-card-form textarea {
        background: white !important;
        color: #333 !important;
        border: 2px solid #b75a3b !important;
        border-radius: 12px !important;
        padding: 15px 20px !important;
        width: 100%;
        font-family: 'Poppins', sans-serif;
    }
    .btn-booking {
        background: #b75a3b;
        color: white;
        width: 100%;
        padding: 1.2rem;
        border-radius: 40px;
        font-weight: 700;
        border: none;
        margin-top: 2rem;
        cursor: pointer;
        transition: 0.3s;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .btn-booking:hover {
        background: #92442b;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(183, 90, 59, 0.3);
    }
    #paymentDetailsGroup {
        display: none;
        background: rgba(255, 255, 255, 0.05);
        padding: 20px;
        border-radius: 15px;
        border-left: 4px solid #b75a3b;
        margin-bottom: 1.8rem;
    }
</style>

<div class="booking-page">
    <div class="booking-card-form">
        <h2>Book Your Event</h2>
        <p class="subtitle">Reserve your luxury experience</p>
        
        <?php if ($message): ?>
            <div class="message success" style="margin-bottom: 2.5rem;"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="input-group">
                <label>Package Type</label>
                <select name="package_type" required>
                    <option value="" disabled <?= $package === '' ? 'selected' : '' ?>>-- Select Package --</option>
                    <?php foreach ($packageList as $item): ?>
                        <option value="<?= $item ?>" <?= $item === $package ? 'selected' : '' ?>><?= $item ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-group">
                <label>Event Type</label>
                <input type="text" name="event_type" placeholder="e.g. Wedding, Birthday" required>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                <div class="input-group">
                    <label>Event Date</label>
                    <input type="date" name="event_date" required>
                </div>
                <div class="input-group">
                    <label>Estimated Guests</label>
                    <input type="number" name="guests" placeholder="How many?" min="1" required>
                </div>
            </div>
            
            <div class="input-group">
                <label>Venue Location</label>
                <input type="text" name="venue" placeholder="Enter venue address" required>
            </div>

            <div class="input-group">
                <label>Payment Method</label>
                <select name="payment_method" id="paymentMethod" required>
                    <option value="" disabled selected>-- Select Payment Method --</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Mobile Money">Mobile Money</option>
                    <option value="PayPal">PayPal (Online)</option>
                    <option value="Cash on Meeting">Cash on Meeting</option>
                </select>
            </div>
            
            <div id="paymentDetailsGroup">
                <div class="input-group" style="margin-bottom: 0;">
                    <label id="paymentRefLabel">Transaction Details</label>
                    <input type="text" name="payment_pin" placeholder="Enter Reference or Number">
                </div>
            </div>
            
            <div class="input-group">
                <label>Additional Details</label>
                <textarea name="message" rows="3" placeholder="Any special requests?"></textarea>
            </div>
            
            <button class="btn-booking" type="submit">Submit Booking Request</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('paymentMethod').addEventListener('change', function() {
        const details = document.getElementById('paymentDetailsGroup');
        const label = document.getElementById('paymentRefLabel');
        const selected = this.value;
        
        if (selected === 'Bank Transfer' || selected === 'Mobile Money') {
            details.style.display = 'block';
            label.innerText = selected === 'Mobile Money' ? 'Mobile Number & Payment PIN' : 'Bank Account Number & Reference';
        } else {
            details.style.display = 'none';
        }
    });
</script>

<?php include 'footer.php'; ?>