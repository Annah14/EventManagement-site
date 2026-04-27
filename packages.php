<?php include 'header.php'; ?>
<style>
    .pricing-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 3rem;
        height: 420px;
    }

    .price-card {
        position: relative;
        height: 100%;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        transition: transform 0.5s cubic-bezier(0.2, 1, 0.3, 1);
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        background: linear-gradient(135deg, #ffffff 0%, #f9f9f9 100%);
        border: 1px solid #eee;
        border-top: 6px solid var(--brand);
    }

    .price-card::before {
        display: none;
    }

    .price-card:hover {
        transform: translateY(-10px);
        background: #fff;
        border-color: rgba(183, 90, 59, 0.3);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .card-content {
        height: 100%;
        width: 100%;
        padding: 25px 15px;
        z-index: 2;
        color: #333;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
        transition: all 0.5s cubic-bezier(0.2, 1, 0.3, 1);
    }

    /* New: Icon styling */
    .card-content .package-icon {
        font-size: 1.5rem;
        color: var(--brand);
        margin-bottom: 1.5rem;
        background: rgba(183, 90, 59, 0.08);
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-left: auto;
        margin-right: auto;
        transition: all 0.3s ease;
    }

    .card-content h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        margin-bottom: 10px;
        letter-spacing: 1px;
        color: var(--brand-dark);
    }

    .card-content h4 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.6rem;
        margin-top: 5px;
        margin-bottom: 10px;
        font-weight: 600;
        color: var(--brand);
        display: flex;
        flex-direction: column;
    }

    .card-content h4::before {
        content: 'Starting from';
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 400;
        color: #999;
        margin-bottom: 5px;
    }

    .card-content .short-desc {
        font-size: 0.9rem;
        opacity: 0.9;
        margin: 0;
        transition: opacity 0.3s ease;
    }

    .card-details {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: all 0.6s ease;
        font-size: 0.95rem;
        line-height: 1.4;
        padding-top: 15px;
        border-top: 1px solid rgba(255,255,255,0.4);
        margin-top: 15px;
        color: #ffffff;
        font-weight: 400;
    }

    /* Active State - When clicked */
    .price-card.active .card-content {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        background: rgba(183, 90, 59, 0.95); /* Restored Brand Color */
        backdrop-filter: blur(10px);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .price-card.active .card-details {
        max-height: 300px;
        opacity: 1;
    }

    .price-card.active h3 {
        color: white;
        text-shadow: none;
    }

    .price-card.active h4 {
        color: white;
    }

    .price-card.active h4::before {
        color: rgba(255, 255, 255, 0.7);
    }

    .price-card.active .short-desc {
        color: rgba(255, 255, 255, 0.9);
    }

    /* Hide icon when card is active */
    .price-card.active .package-icon {
        display: none;
    }

    .btn-mini {
        display: inline-block;
        margin-top: 25px;
        background: white;
        color: var(--brand);
        padding: 10px 25px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.3s;
    }

    .btn-mini:hover { background: #f0f0f0; transform: scale(1.05); }

    @media (max-width: 1024px) {
        .pricing-grid { grid-template-columns: repeat(2, 1fr); height: auto; }
        .price-card { height: 400px; } /* Adjusted height for smaller screens */
        .card-content .package-icon { font-size: 3rem; margin-bottom: 1rem; }
        .card-content h3 { font-size: 1.8rem; }
        .card-content h4 { font-size: 1.4rem; }
    }

    @media (max-width: 600px) {
        .pricing-grid { grid-template-columns: 1fr; }
        .price-card { height: 380px; }
    }
</style>
<section class="page-section">
    <h2>Packages</h2>
    <p class="section-text">Select the event package that best fits your celebration. Click to learn more about each offering.</p>
    <div class="pricing-grid">
        <div class="price-card" id="basic" onclick="this.classList.toggle('active')">
            <div class="card-content">
                <i class="fa-solid fa-gem package-icon"></i> <!-- Icon added -->
                <h3>Basic Package</h3>
                <h4>$500</h4>
                <p class="short-desc">Planning, venue coordination, and vendor support.</p>
                <div class="card-details">
                    <p>Ideal for smaller events, includes essential planning, venue liaison, and basic vendor management to ensure a smooth execution.</p>
                    <a class="btn-mini" href="booking.php?package=Basic">Book Now</a>
                </div>
            </div>
        </div>
        <div class="price-card" id="premium" onclick="this.classList.toggle('active')">
            <div class="card-content">
                <i class="fa-solid fa-crown package-icon"></i> <!-- Icon added -->
                <h3>Premium Package</h3>
                <h4>$1500</h4>
                <p class="short-desc">Full planning, décor, catering, photography, and event staffing.</p>
                <div class="card-details">
                    <p>Our most popular choice, offering comprehensive planning, elegant décor, gourmet catering, professional photography, and dedicated event staff.</p>
                    <a class="btn-mini" href="booking.php?package=Premium">Book Now</a>
                </div>
            </div>
        </div>
        <div class="price-card" id="luxury" onclick="this.classList.toggle('active')">
            <div class="card-content">
                <i class="fa-solid fa-wand-magic-sparkles package-icon"></i> 
                <h3>Luxury Package</h3>
                <h4>$3000</h4>
                <p class="short-desc">White-glove service, VIP coordination, entertainment, and premium styling.</p>
                <div class="card-details">
                    <p>The ultimate experience with bespoke design, VIP guest management, top-tier entertainment, and exquisite styling for an unforgettable event.</p>
                    <a class="btn-mini" href="booking.php?package=Luxury">Book Now</a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'footer.php'; ?>