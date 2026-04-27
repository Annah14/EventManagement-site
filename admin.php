<?php
require_once __DIR__ . '/db.php';
requireAdmin();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Booking Status Update
    $bookingId = intval($_POST['booking_id'] ?? 0);
    $newStatus = $_POST['status'] ?? '';
    if ($bookingId && in_array($newStatus, ['Approved', 'Rejected'], true)) {
        $stmt = $pdo->prepare('UPDATE bookings SET status = ? WHERE id = ?');
        $stmt->execute([$newStatus, $bookingId]);
        $message = 'Booking status updated.';
    }

    // Handle Service Addition
    if (isset($_POST['add_service'])) {
        $title = trim($_POST['title'] ?? '');
        $short = trim($_POST['short_desc'] ?? '');
        $long = trim($_POST['long_desc'] ?? '');
        $icon = trim($_POST['icon_class'] ?? 'fa-star');
        $img = trim($_POST['image_url'] ?? '');
        if ($title && $short) {
            $stmt = $pdo->prepare('INSERT INTO services (title, short_desc, long_desc, icon_class, image_url) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$title, $short, $long, $icon, $img]);
            $message = 'Service added successfully.';
        }
    }

    // Handle Service Deletion
    if (isset($_POST['delete_service'])) {
        $id = intval($_POST['service_id']);
        $pdo->prepare('DELETE FROM services WHERE id = ?')->execute([$id]);
        $message = 'Service removed.';
    }

    // Handle Package Addition
    if (isset($_POST['add_package'])) {
        $name = trim($_POST['name'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $short = trim($_POST['short_desc'] ?? '');
        $long = trim($_POST['long_desc'] ?? '');
        $icon = trim($_POST['icon_class'] ?? 'fa-box');
        if ($name && $price > 0) {
            $stmt = $pdo->prepare('INSERT INTO packages (name, price, short_desc, long_desc, icon_class) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$name, $price, $short, $long, $icon]);
            $message = 'Package added successfully.';
        }
    }

    // Handle Package Deletion
    if (isset($_POST['delete_package'])) {
        $id = intval($_POST['package_id']);
        $pdo->prepare('DELETE FROM packages WHERE id = ?')->execute([$id]);
        $message = 'Package removed.';
    }

    // Handle Inquiry Reply
    if (isset($_POST['reply_inquiry'])) {
        $id = intval($_POST['inquiry_id']);
        $reply = trim($_POST['reply_text'] ?? '');
        if ($id && $reply) {
            $stmt = $pdo->prepare('UPDATE inquiries SET admin_reply = ? WHERE id = ?');
            $stmt->execute([$reply, $id]);
            $message = 'Reply sent to user.';
        }
    }
}
$stmt = $pdo->query('SELECT b.*, u.fullname, u.email FROM bookings b JOIN users u ON b.user_id = u.id ORDER BY b.created_at DESC');
$bookings = $stmt->fetchAll();

$users = $pdo->query('SELECT id, fullname, email, role, created_at FROM users ORDER BY created_at DESC')->fetchAll();
$inquiries = $pdo->query('SELECT * FROM inquiries ORDER BY created_at DESC')->fetchAll();

$services = $pdo->query('SELECT * FROM services ORDER BY created_at DESC')->fetchAll();
$packages = $pdo->query('SELECT * FROM packages ORDER BY created_at DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annah Admin - Control Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-page-body">
<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="sidebar-brand">Annah<span>Admin</span></div>
        <nav>
            <ul class="admin-nav">
                <li><a href="#bookings" class="active"><i class="fa-solid fa-calendar-day"></i> <span>Bookings</span></a></li>
                <li><a href="#users"><i class="fa-solid fa-users"></i> <span>Users</span></a></li>
                <li><a href="#inquiries"><i class="fa-solid fa-envelope"></i> <span>Inquiries</span></a></li>
                <li><a href="#services"><i class="fa-solid fa-sparkles"></i> <span>Services</span></a></li>
                <li><a href="#packages"><i class="fa-solid fa-tags"></i> <span>Packages</span></a></li>
                <li style="margin-top: auto; padding-top: 2rem;">
                    <a href="index.php" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> <span>Live Site</span></a>
                </li>
                <li><a href="logout.php" style="color: #e74c3c;"><i class="fa-solid fa-power-off"></i> <span>Logout</span></a></li>
            </ul>
        </nav>
    </aside>

    <main class="admin-content">
        <?php if ($message): ?>
            <div class="message success">
                <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div id="bookings" class="admin-card-main">
            <h4><i class="fa-solid fa-envelope-open-text"></i> Booking Requests</h4>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Event Info</th>
                            <th>Payment Details</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 600;"><?= htmlspecialchars($booking['fullname']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($booking['email']) ?></small>
                                </td>
                                <td>
                                    <div style="color: var(--brand); font-weight: 500;"><?= htmlspecialchars($booking['package_type']) ?></div>
                                    <small><?= htmlspecialchars($booking['event_date']) ?> • <?= htmlspecialchars($booking['venue']) ?></small>
                                </td>
                                <td>
                                    <small><?= htmlspecialchars($booking['payment_method']) ?></small>
                                    <?php if (!empty($booking['payment_pin'])): ?>
                                        <br><code style="font-size: 0.75rem;">ID: <?= htmlspecialchars($booking['payment_pin']) ?></code>
                                    <?php endif; ?>
                                </td>
                                <td><span class="status-badge status-<?= strtolower($booking['status']) ?>"><?= $booking['status'] ?></span></td>
                                <td>
                                    <?php if ($booking['status'] === 'Pending'): ?>
                                        <form method="post" style="display: flex; gap: 5px;">
                                            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                            <button type="submit" name="status" value="Approved" class="action-button approve" title="Approve"><i class="fa-solid fa-check"></i></button>
                                            <button type="submit" name="status" value="Rejected" class="action-button reject" title="Reject"><i class="fa-solid fa-xmark"></i></button>
                                        </form>
                                    <?php else: ?>
                                        <i class="fa-solid fa-circle-minus text-muted"></i>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="users" class="admin-card-main" style="margin-top: 2rem;">
            <h4><i class="fa-solid fa-users"></i> Registered Users</h4>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['fullname']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><span class="status-badge status-<?= $u['role'] ?>"><?= ucfirst($u['role']) ?></span></td>
                                <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="inquiries" class="admin-card-main" style="margin-top: 2rem;">
            <h4><i class="fa-solid fa-envelope"></i> Contact Inquiries</h4>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Admin Reply</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inquiries as $i): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($i['fullname']) ?></strong><br>
                                    <small><?= htmlspecialchars($i['email']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($i['subject']) ?></td>
                                <td><div style="max-width: 250px; font-size: 0.85rem;"><?= nl2br(htmlspecialchars($i['message'])) ?></div></td>
                                <td><div style="max-width: 250px; font-size: 0.85rem; color: #27ae60; font-weight: 500;"><?= nl2br(htmlspecialchars($i['admin_reply'] ?? '')) ?></div></td>
                                <td>
                                    <form method="post" style="display: flex; flex-direction: column; gap: 5px;">
                                        <input type="hidden" name="inquiry_id" value="<?= $i['id'] ?>">
                                        <textarea name="reply_text" placeholder="Type reply..." required style="font-size: 0.75rem; padding: 5px; border-radius: 4px; border: 1px solid #ddd;"></textarea>
                                        <button type="submit" name="reply_inquiry" class="action-button approve" style="width: 100%; border-radius: 4px;" title="Send Reply"><i class="fa-solid fa-paper-plane"></i> Reply</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem;">
            <div id="services" class="admin-card-main">
                <h4><i class="fa-solid fa-magic-wand-sparkles"></i> Manage Services</h4>
                <form method="post" style="display:grid; gap: 12px; margin-bottom: 2rem; background: #fafafa; padding: 20px; border-radius: 12px;">
                    <input type="text" name="title" placeholder="Service Title" required style="padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    <input type="text" name="short_desc" placeholder="Tagline" required style="padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    <textarea name="long_desc" placeholder="Extended Description" required style="padding: 10px; border-radius: 8px; border: 1px solid #ddd;"></textarea>
                    <input type="text" name="icon_class" placeholder="Icon Class (fa-ring)" style="padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    <button type="submit" name="add_service" class="btn-primary" style="width: 100%; border-radius: 8px;">Publish Service</button>
                </form>
                <table class="data-table">
                    <?php foreach ($services as $s): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($s['title']) ?></strong></td>
                            <td style="text-align: right;">
                                <form method="post" onsubmit="return confirm('Delete service?');">
                                    <input type="hidden" name="service_id" value="<?= $s['id'] ?>">
                                    <button type="submit" name="delete_service" style="background:none; border:none; color:#dc3545; cursor:pointer;"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div id="packages" class="admin-card-main">
                <h4><i class="fa-solid fa-gem"></i> Manage Packages</h4>
                <form method="post" style="display:grid; gap: 12px; margin-bottom: 2rem; background: #fafafa; padding: 20px; border-radius: 12px;">
                    <input type="text" name="name" placeholder="Package Name" required style="padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    <input type="number" step="0.01" name="price" placeholder="Price ($)" required style="padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    <input type="text" name="short_desc" placeholder="One-line summary" required style="padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    <textarea name="long_desc" placeholder="Full Details" required style="padding: 10px; border-radius: 8px; border: 1px solid #ddd;"></textarea>
                    <button type="submit" name="add_package" class="btn-primary" style="width: 100%; border-radius: 8px;">Launch Package</button>
                </form>
                <table class="data-table">
                    <?php foreach ($packages as $p): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($p['name']) ?></strong><br><small>$<?= number_format($p['price'], 2) ?></small></td>
                            <td style="text-align: right;">
                                <form method="post" onsubmit="return confirm('Delete package?');">
                                    <input type="hidden" name="package_id" value="<?= $p['id'] ?>">
                                    <button type="submit" name="delete_package" style="background:none; border:none; color:#dc3545; cursor:pointer;"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </main>
</div>
<script>
    function toggleSidebar() {
        const layout = document.querySelector('.admin-layout');
        layout.classList.toggle('collapsed');
        // Optional: Store preference in localStorage
        // if (layout.classList.contains('collapsed')) localStorage.setItem('sidebarCollapsed', 'true');
        // else localStorage.removeItem('sidebarCollapsed');
    }
</script>
</body>
</html>