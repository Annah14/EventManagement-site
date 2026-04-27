<?php 
require_once __DIR__ . '/db.php';
$all_services = $pdo->query('SELECT * FROM services ORDER BY created_at DESC')->fetchAll();
include 'header.php'; 
?>
<style>
    .service-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-top: 3rem;
        height: 420px;
    }

    .service-card {
        position: relative;
        height: 100%;
        border-radius: 20px;
        overflow: hidden;
        cursor: pointer;
        background-size: cover;
        background-position: center;
        transition: transform 0.5s cubic-bezier(0.2, 1, 0.3, 1);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }

    .service-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to bottom, transparent 30%, rgba(0,0,0,0.8) 100%);
        transition: opacity 0.5s ease;
    }

    .service-card:hover {
        transform: scale(1.02);
    }

    .card-content {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 30px 20px;
        z-index: 2;
        color: white;
        text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        transition: all 0.5s cubic-bezier(0.2, 1, 0.3, 1);
    }

    .card-content h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: white;
    }

    .card-content .short-desc {
        font-size: 0.9rem;
        opacity: 0.9;
        margin: 0;
        color: white;
        font-weight: 500;
        transition: opacity 0.3s ease;
    }

    .card-details {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: all 0.5s ease;
        font-size: 0.95rem;
        line-height: 1.6;
        padding-top: 15px;
        border-top: 1px solid rgba(255,255,255,0.3);
        margin-top: 15px;
        color: white;
    }

    /* Active State - When clicked */
    .service-card.active .card-content {
        background: rgba(183, 90, 59, 0.9);
        color: white;
        backdrop-filter: blur(10px);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .service-card.active .card-details {
        max-height: 200px;
        opacity: 1;
    }

    .service-card.active .short-desc {
        display: none;
    }

    .btn-mini {
        display: inline-block;
        margin-top: 20px;
        color: #fff;
        border: 1px solid #fff;
        padding: 8px 15px;
        border-radius: 50px;
        font-size: 0.8rem;
        text-decoration: none;
        transition: background 0.3s;
    }

    .btn-mini:hover { background: white; color: var(--brand); }

    .explore-more-section {
        margin-top: 4rem;
        padding: 3rem;
        background: var(--brand-dark);
        border-radius: 20px;
        color: white;
        text-align: center;
    }

    @media (max-width: 1024px) {
        .service-grid { grid-template-columns: repeat(2, 1fr); height: auto; }
        .service-card { height: 350px; }
    }

    @media (max-width: 600px) {
        .service-grid { grid-template-columns: 1fr; }
    }
</style>

<section class="page-section">
    <h2>Services</h2>
    <p class="section-text">Bespoke events designed with precision. Click a service to explore our expertise.</p>
    
    <div class="service-grid">
        <?php foreach ($all_services as $s): ?>
            <div class="service-card" 
                 style="background-image: url('<?= htmlspecialchars($s['image_url'] ?: 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80') ?>')" 
                 onclick="this.classList.toggle('active')">
                <div class="card-content">
                    <h3><?= htmlspecialchars($s['title']) ?></h3>
                    <p class="short-desc"><?= htmlspecialchars($s['short_desc']) ?></p>
                    <div class="card-details">
                        <p><?= htmlspecialchars($s['long_desc']) ?></p>
                        <a href="booking.php?package=<?= urlencode($s['title']) ?>" class="btn-mini">Book Now</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="explore-more-section">
        <h3 style="color:white; margin-bottom:10px;">Ready to see our magic in action?</h3>
        <p style="margin-bottom:20px;">Transforming spaces into unforgettable memories.</p>
        <a href="index.php#gallery" class="btn-primary" style="background:white; color:var(--brand-dark);">View Full Gallery</a>
    </div>
</section>
<?php include 'footer.php'; ?>