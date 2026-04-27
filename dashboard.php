<?php
require_once __DIR__ . '/db.php';
requireLogin();
if (isAdmin()) {
    header('Location: admin.php');
    exit;
}
$user = currentUser();
$stmt = $pdo->prepare('SELECT * FROM bookings WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user['id']]);
$bookings = $stmt->fetchAll();

// Calculate simple stats for the user
$stats = [
    'total' => count($bookings),
    'pending' => count(array_filter($bookings, fn($b) => $b['status'] === 'Pending')),
    'approved' => count(array_filter($bookings, fn($b) => $b['status'] === 'Approved')),
];

// Fetch inquiries for the user
$stmt = $pdo->prepare('SELECT * FROM inquiries WHERE email = ? ORDER BY created_at DESC');
$stmt->execute([$user['email']]);
$userInquiries = $stmt->fetchAll();
?>
<?php include 'header.php'; ?>
<style>
    .dashboard-container { display: grid; grid-template-columns: 300px 1fr; gap: 30px; margin-top: 20px; }
    .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
    .stat-widget { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-left: 5px solid var(--brand); }
    .stat-widget h4 { font-size: 0.8rem; text-transform: uppercase; color: #888; margin-bottom: 5px; }
    .stat-widget .count { font-size: 1.8rem; font-weight: 700; color: var(--brand-dark); }
    
    /* Profile Sidebar */
    .profile-sidebar { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); height: fit-content; }
    .profile-avatar { width: 80px; height: 80px; background: var(--brand); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 15px; }
    
    .booking-card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    
    @media (max-width: 992px) {
        .dashboard-container { grid-template-columns: 1fr; }
    }
</style>

<section class="page-section">
    <div class="dashboard-header">
        <h2>Welcome back, <?= htmlspecialchars(explode(' ', $user['fullname'])[0]) ?>!</h2>
        <p class="text-muted" style="margin:0;">Manage your event bookings and account details here.</p>
    </div>

    <div class="stats-row">
        <div class="stat-widget">
            <h4><i class="fa-solid fa-calendar-check"></i> Total Requests</h4>
            <div class="count"><?= $stats['total'] ?></div>
        </div>
        <div class="stat-widget" style="border-left-color: #f39c12;">
            <h4><i class="fa-solid fa-hourglass-half"></i> Pending</h4>
            <div class="count"><?= $stats['pending'] ?></div>
        </div>
        <div class="stat-widget" style="border-left-color: #27ae60;">
            <h4><i class="fa-solid fa-circle-check"></i> Approved</h4>
            <div class="count"><?= $stats['approved'] ?></div>
        </div>
    </div>

    <div class="dashboard-container">
        <aside class="profile-sidebar">
            <div class="profile-avatar" style="background: linear-gradient(45deg, var(--brand), #e07b5b);">
                <i class="fa-solid fa-user"></i>
            </div>
            <h3 style="text-align:center; margin-bottom:20px;"><?= htmlspecialchars($user['fullname']) ?></h3>
            <div style="font-size: 0.95rem; color: #666; line-height: 2;">
                <p><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($user['email']) ?></p>
                <p><i class="fa-solid fa-shield"></i> <?= ucfirst($user['role']) ?> Account</p>
            </div>
            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
            <a class="btn-primary" href="booking.php" style="display:block; text-align:center;">New Booking</a>
        </aside>

        <div class="booking-card">
            <h3><i class="fa-solid fa-clipboard-list"></i> Recent Bookings</h3>
            <?php if (!$bookings): ?>
                <div style="text-align:center; padding: 40px; color: #888;">
                    <i class="fa-solid fa-calendar-xmark" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.3;"></i>
                    <p>You haven't made any bookings yet.</p>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Event Details</th>
                                <th>Location</th>
                                <th>Payment</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($booking['package_type']) ?></strong><br>
                                    <small style="color:#888;"><i class="fa-solid fa-calendar"></i> <?= htmlspecialchars($booking['event_date']) ?></small>
                                </td>
                                <td>
                                    <i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($booking['venue']) ?><br>
                                    <small><?= htmlspecialchars($booking['guests']) ?> Guests</small>
                                </td>
                                <td>
                                    <span style="font-size: 0.85rem;"><?= htmlspecialchars($booking['payment_method']) ?></span>
                                    <?php if (!empty($booking['payment_pin'])): ?>
                                        <br><code style="font-size: 0.7rem; color: var(--brand);">Ref: <?= htmlspecialchars($booking['payment_pin']) ?></code>
                                    <?php endif; ?>
                                </td>
                                <td><span class="status-badge status-<?= strtolower($booking['status']) ?>"><?= htmlspecialchars($booking['status']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Enquiries Section - Enlarged & Premium Style -->
    <div class="booking-card" style="margin-top: 40px; border-top: 4px solid var(--brand); background: #fff; padding: 35px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h3 style="margin:0;"><i class="fa-solid fa-envelope-open-text" style="color: var(--brand);"></i> My Enquiries</h3>
            <span style="font-size: 0.85rem; color: #888; font-weight: 500;">History & Admin Responses</span>
        </div>

        <?php if (!$userInquiries): ?>
            <div style="text-align:center; padding: 60px; color: #aaa; background: #fafafa; border-radius: 12px; border: 2px dashed #eee;">
                <i class="fa-solid fa-comment-dots" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.2;"></i>
                <p>You haven't sent any enquiries yet. Need help? <a href="contact.php" style="color: var(--brand);">Contact us</a></p>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 18px 15px;">Topic / Subject</th>
                            <th>Original Message</th>
                            <th>Official Reply</th>
                            <th style="text-align: center;">Current Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userInquiries as $inq): ?>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="font-weight: 600; color: var(--brand-dark); padding: 20px 15px; min-width: 150px;">
                                <i class="fa-solid fa-tag" style="font-size: 0.8rem; margin-right: 5px; opacity: 0.5;"></i>
                                <?= htmlspecialchars($inq['subject']) ?>
                                <div style="font-size: 0.7rem; color: #aaa; font-weight: 400; margin-top: 4px;">
                                    <?= date('M d, Y', strtotime($inq['created_at'])) ?>
                                </div>
                            </td>
                            <td style="font-size: 0.9rem; max-width: 300px; color: #555; line-height: 1.5; padding: 20px 15px;">
                                <?= nl2br(htmlspecialchars($inq['message'])) ?>
                            </td>
                            <td style="font-size: 0.9rem; max-width: 350px; background: #fdfaf8; padding: 20px 15px;">
                                <?php if ($inq['admin_reply']): ?>
                                    <div style="background: white; padding: 12px; border-radius: 8px; border-left: 3px solid #27ae60; box-shadow: 0 2px 5px rgba(0,0,0,0.03);">
                                        <small style="display:block; color: #27ae60; font-weight: 700; text-transform: uppercase; font-size: 0.65rem; margin-bottom: 5px;">Admin Response</small>
                                        <div style="color: #333; line-height: 1.5;"><?= nl2br(htmlspecialchars($inq['admin_reply'])) ?></div>
                                    </div>
                                <?php else: ?>
                                    <div style="padding: 10px; color: #999; font-style: italic; font-size: 0.85rem;">
                                        <i class="fa-solid fa-clock-rotate-left"></i> Our team is reviewing your message...
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center; padding: 20px 15px;">
                                <?php if ($inq['admin_reply']): ?>
                                    <span class="status-badge status-approved" style="padding: 6px 15px; font-size: 0.75rem;">RESOLVED</span>
                                <?php else: ?>
                                    <span class="status-badge status-pending" style="padding: 6px 15px; font-size: 0.75rem;">IN REVIEW</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php include 'footer.php'; ?>