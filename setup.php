<?php
$host = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'event_management';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbName`");

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(120) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('user','admin') NOT NULL DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        event_date DATE NOT NULL,
        package_type VARCHAR(100) NOT NULL,
        event_type VARCHAR(100) NOT NULL,
        venue VARCHAR(150) NOT NULL,
        guests INT NOT NULL,
        message TEXT,
        payment_method VARCHAR(50) NOT NULL,
        payment_pin VARCHAR(20) DEFAULT NULL,
        status ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS services (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        short_desc VARCHAR(255) NOT NULL,
        long_desc TEXT NOT NULL,
        icon_class VARCHAR(50) NOT NULL,
        image_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS packages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        short_desc VARCHAR(255) NOT NULL,
        long_desc TEXT NOT NULL,
        icon_class VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS inquiries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(150) NOT NULL,
        email VARCHAR(150) NOT NULL,
        subject VARCHAR(200) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Seed initial Services if empty to prevent empty site
    $count = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
    if ($count == 0) {
        $stmt = $pdo->prepare("INSERT INTO services (title, short_desc, long_desc, icon_class, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Weddings', 'Luxury & Intimate Ceremonies', 'From custom floral installations to full-day coordination, we ensure your wedding is a masterpiece of elegance.', 'fa-ring', 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80']);
        $stmt->execute(['Corporate Events', 'Conferences & Branding', 'We deliver high-impact corporate production, including AV management and sophisticated catering for seminars.', 'fa-building', 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80']);
        $stmt->execute(['Private Parties', 'Social Celebrations', 'Bespoke birthdays and anniversaries featuring premium styling, DJ booking, and unique theme development.', 'fa-cake-candles', 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=80']);
        $stmt->execute(['Concerts & Shows', 'Live Technical Production', 'Large-scale logistics including stage rigging, pro-audio sound engineering, and lighting design for live shows.', 'fa-microphone', 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=800&q=80']);
    }

    // Seed initial Packages if empty
    $count = $pdo->query("SELECT COUNT(*) FROM packages")->fetchColumn();
    if ($count == 0) {
        $stmt = $pdo->prepare("INSERT INTO packages (name, price, short_desc, long_desc, icon_class) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Basic Package', 500.00, 'Planning, venue coordination, and vendor support.', 'Ideal for smaller events, includes essential planning, venue liaison, and basic vendor management.', 'fa-gem']);
        $stmt->execute(['Premium Package', 1500.00, 'Full planning, décor, catering, photography, and event staffing.', 'Our most popular choice, offering comprehensive planning, elegant décor, and gourmet catering.', 'fa-crown']);
        $stmt->execute(['Luxury Package', 3000.00, 'White-glove service, VIP coordination, and premium styling.', 'The ultimate experience with bespoke design, VIP guest management, and exquisite styling.', 'fa-wand-magic-sparkles']);
    }

    // Safe check to add payment_method if table already exists
    try {
        $pdo->query("SELECT payment_method FROM bookings LIMIT 1");
    } catch (Exception $e) {
        $pdo->exec("ALTER TABLE bookings ADD COLUMN payment_method VARCHAR(50) NOT NULL AFTER message");
    }

    try {
        $pdo->query("SELECT payment_pin FROM bookings LIMIT 1");
    } catch (Exception $e) {
        $pdo->exec("ALTER TABLE bookings ADD COLUMN payment_pin VARCHAR(20) DEFAULT NULL AFTER payment_method");
    }

    $adminEmail = 'admin@annah.com';
    $adminPassword = password_hash('Admin@123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$adminEmail]);
    if (!$stmt->fetch()) {
        $pdo->prepare('INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)
        ')->execute(['Annah Admin', $adminEmail, $adminPassword, 'admin']);
    }

    echo "<h1>Setup completed</h1>";
    echo "<p>Database <strong>$dbName</strong> and tables are ready.</p>";
    echo "<p>Admin login: <strong>$adminEmail</strong><br>Password: <strong>Admin@123</strong></p>";
    echo "<p><a href=\"index.php\">Go to homepage</a></p>";
} catch (PDOException $e) {
    echo '<h1>Setup failed</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
}
