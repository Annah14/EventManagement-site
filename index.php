<?php 
require_once __DIR__ . '/db.php';
$services = $pdo->query('SELECT * FROM services ORDER BY id ASC LIMIT 4')->fetchAll();
$packages = $pdo->query('SELECT * FROM packages ORDER BY price ASC LIMIT 3')->fetchAll();
include 'header.php'; 
?>
<style>
    .service-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-top: 3rem;
        min-height: 420px;
    }

    .service-card {
        position: relative;
        height: 100%;
        border-radius: 20px;
        overflow: hidden;
        cursor: pointer;
        background-color: #fdfdfd;
        background-size: cover;
        background-position: center;
        border: 1px solid #eee;
        border-top: 6px solid var(--brand);
        transition: transform 0.5s cubic-bezier(0.2, 1, 0.3, 1);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }

    .service-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to bottom, transparent 30%, rgba(0,0,0,0.8) 100%);
        z-index: 1;
    }

    /* Magic Shine Effect */
    .service-card::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: rgba(255,255,255,0.2);
        transform: rotate(45deg);
        transition: 0.6s;
        opacity: 0;
        pointer-events: none;
    }

    .service-card:hover::after {
        top: -10%; left: -10%; opacity: 1;
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .card-content {
        height: 100%;
        width: 100%;
        padding: 30px 20px;
        z-index: 2;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
        transition: all 0.5s cubic-bezier(0.2, 1, 0.3, 1);
        text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        color: white;
    }

    .card-content h3 {
        font-weight: 700;
        font-size: 1.6rem;
        color: white;
    }

    .card-content i {
        font-size: 2.5rem;
        color: white;
        margin-bottom: 1rem;
    }

    .card-details {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        font-size: 0.95rem;
        line-height: 1.4;
        padding-top: 15px;
        border-top: 1px solid rgba(255,255,255,0.4);
        margin-top: 15px;
        display: flex;
        flex-direction: column;
        color: white;
        align-items: center;
        gap: 12px;
    }

    .pro-badge {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.5);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 700;
        color: #fff;
        transform: translateY(-15px);
        transition: transform 0.4s ease 0.2s;
    }

    .service-card.active .pro-badge {
        transform: translateY(0);
    }

    .close-hint {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-top: 10px;
        opacity: 0.7;
    }

    .service-card.active .card-content {
        position: absolute;
        top: 0; left: 0;
        height: 100%;
        background: rgba(183, 90, 59, 0.9);
        backdrop-filter: blur(10px);
        color: white;
    }

    .service-card.active .card-details {
        max-height: 300px;
        opacity: 1;
    }

    .service-card.active i { color: white; }

    /* Gallery Interactivity */
    .gallery-item {
        position: relative;
        height: 250px;
        border-radius: 15px;
        overflow: hidden;
        cursor: pointer;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
    }
    .gallery-item:hover { transform: translateY(-5px); }
    .gallery-item img { width: 100%; height: 100%; object-fit: cover; }
    .gallery-caption {
        position: absolute;
        inset: 0;
        background: rgba(183, 90, 59, 0.95);
        background-image: 
            radial-gradient(circle at 2px 2px, rgba(255,255,255,0.1) 1px, transparent 0),
            linear-gradient(45deg, rgba(255,255,255,0.02) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.02) 50%, rgba(255,255,255,0.02) 75%, transparent 75%, transparent);
        background-size: 20px 20px;
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 30px 20px;
        opacity: 0;
        transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        backdrop-filter: blur(5px);
    }
    .gallery-item.active .gallery-caption { 
        opacity: 1; 
    }
    .gallery-caption p { 
        font-weight: 500; 
        font-size: 1rem; 
        line-height: 1.4;
        margin-top: 10px;
    }
    .gallery-item.active .pro-badge {
        transform: translateY(0);
    }
</style>

<section class="hero hero-small">
    <div class="hero-overlay">
        <h1>Elegant & Luxury Event Planning</h1>
        <p>We design unforgettable moments with style and perfection.</p>
        <a href="packages.php" class="btn-primary">View Packages</a>
        <a href="contact.php" class="btn-outline">Contact Us</a>
    </div>
</section>

<section class="about page-section">
    <h2>About Annah Events</h2>
    <p>Annah Events is a premium event management company that creates unforgettable weddings, corporate gatherings, concerts, and private celebrations. Our team handles every detail from venue selection to day-of coordination.</p>
    <div class="stats">
        <div class="stat-box"><h3>250+</h3><p>Events Managed</p></div>
        <div class="stat-box"><h3>120+</h3><p>Happy Clients</p></div>
        <div class="stat-box"><h3>15+</h3><p>Professional Staff</p></div>
    </div>
</section>

<section class="services page-section">
    <h2>Our Services</h2>
    <div class="service-grid">
        <?php foreach ($services as $s): ?>
            <div class="service-card" 
                 style="background-image: url('<?= htmlspecialchars($s['image_url'] ?: 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80') ?>')" 
                 onclick="this.classList.toggle('active')">
                <div class="card-content">
                    <i class="fa-solid <?= htmlspecialchars($s['icon_class']) ?>"></i>
                    <h3><?= htmlspecialchars($s['title']) ?></h3>
                    <p class="short-desc"><?= htmlspecialchars($s['short_desc']) ?></p>
                    <div class="card-details">
                        <p><?= htmlspecialchars($s['long_desc']) ?></p>
                        <span class="close-hint">Click to return</span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="pricing page-section">
    <h2>Popular Packages</h2>
    <div class="pricing-grid">
        <?php foreach ($packages as $p): ?>
            <div class="price-card">
                <h3><?= htmlspecialchars($p['name']) ?></h3>
                <h4>$<?= number_format($p['price'], 0) ?></h4>
                <p><?= htmlspecialchars($p['short_desc']) ?></p>
                <a href="packages.php" class="btn-primary">Choose</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="gallery page-section">
    <h2>Event Gallery</h2>
    <div class="gallery-grid">
        <div class="gallery-item" onclick="this.classList.toggle('active')">
            <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=1200&q=80" alt="Elegant event seating">
            <div class="gallery-caption">
                <span class="pro-badge">Wedding Design</span>
                <p>Bespoke Table Settings for Luxury Weddings</p>
                <span class="close-hint">Click to close</span>
            </div>
        </div>
        <div class="gallery-item" onclick="this.classList.toggle('active')">
            <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80" alt="Wedding décor">
            <div class="gallery-caption">
                <span class="pro-badge">Venue Styling</span>
                <p>Grand Ballroom Transformations</p>
                <span class="close-hint">Click to close</span>
            </div>
        </div>
        <div class="gallery-item" onclick="this.classList.toggle('active')">
            <img src="https://images.unsplash.com/photo-1504805572947-34fad45aed93?auto=format&fit=crop&w=1200&q=80" alt="Corporate event lighting">
            <div class="gallery-caption">
                <span class="pro-badge">Gala Excellence</span>
                <p>High-End Corporate Gala Productions</p>
                <span class="close-hint">Click to close</span>
            </div>
        </div>
        <div class="gallery-item" onclick="this.classList.toggle('active')">
            <img src="https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&w=1200&q=80" alt="Party atmosphere">
            <div class="gallery-caption">
                <span class="pro-badge">Social Events</span>
                <p>Intimate & Vibrant Social Celebrations</p>
                <span class="close-hint">Click to close</span>
            </div>
        </div>
    </div>
</section>

<section class="contact page-section">
    <h2>Book an Event</h2>
    <p class="section-text">Use our contact page or sign in to place a booking request. All bookings are stored in your account and visible in your dashboard.</p>
    <div class="button-row">
        <a class="btn-primary" href="register.php">Create Account</a>
        <a class="btn-outline" href="login.php">Login</a>
    </div>
</section>

<?php include 'footer.php'; ?>