<?php include 'header.php'; ?>
<style>
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 3rem;
        min-height: 420px;
    }

    .info-box {
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
        text-align: center;
        justify-content: center;
        padding: 30px 20px;
    }

    /* Magic Shine Effect */
    .info-box::after {
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

    .info-box:hover::after {
        top: -10%;
        left: -10%;
        opacity: 1;
    }

    .info-box:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }

    .info-box i {
        font-size: 1.8rem;
        color: var(--brand);
        background: rgba(183, 90, 59, 0.08);
        width: 65px;
        height: 65px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 1.5rem;
        animation: float 4s ease-in-out infinite;
    }

    .info-box h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        color: var(--brand-dark);
        margin-bottom: 10px;
    }

    .info-box p.short-desc {
        font-size: 0.9rem;
        color: #666;
        margin: 0;
    }

    .info-details {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        font-size: 0.95rem;
        line-height: 1.4;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid rgba(255,255,255,0.4);
        color: white;
        font-weight: 400;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }

    .close-hint {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-top: 20px;
        opacity: 0.7;
    }

    .pro-badge {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.5);
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 700;
        color: #fff;
        transform: translateY(-20px);
        transition: transform 0.4s ease 0.2s;
    }

    .info-box.active .pro-badge {
        transform: translateY(0);
    }

    /* Active State - Orange Reveal */
    .info-box.active {
        background: rgba(183, 90, 59, 0.95);
        background-image: 
            radial-gradient(circle at 2px 2px, rgba(255,255,255,0.1) 1px, transparent 0),
            linear-gradient(45deg, rgba(255,255,255,0.02) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.02) 50%, rgba(255,255,255,0.02) 75%, transparent 75%, transparent);
        background-size: 20px 20px;
        backdrop-filter: blur(10px);
        border-top-color: var(--brand-dark);
    }

    .info-box.active i, 
    .info-box.active h3, 
    .info-box.active p.short-desc {
        display: none;
    }

    .info-box.active .info-details {
        max-height: 300px;
        opacity: 1;
    }

    @media (max-width: 900px) {
        .info-grid { height: auto; }
        .info-box { height: 320px; }
    }
</style>

<section class="page-section">
    <h2>About Annah Events</h2>
    <p class="section-text">Annah Events delivers memorable, high-quality experiences for every client. Click the cards below to see what makes our approach unique.</p>
    <div class="info-grid">
        <div class="info-box" onclick="this.classList.toggle('active')">
            <i class="fa-solid fa-palette"></i>
            <h3>Creative Design</h3>
            <p class="short-desc">Immersive event themes for every budget.</p>
            <div class="info-details">
                <span class="pro-badge">100% Custom Concepts</span>
                <p>Our design team focuses on color psychology and spatial layout to transform any venue into a breathtaking environment that tells your story.</p>
                <span class="close-hint">Click to return</span>
            </div>
        </div>
        <div class="info-box" onclick="this.classList.toggle('active')">
            <i class="fa-solid fa-handshake"></i>
            <h3>Trusted Partners</h3>
            <p class="short-desc">Vetted venues and premium providers.</p>
            <div class="info-details">
                <span class="pro-badge">50+ Vetted Vendors</span>
                <p>We have built a network of the finest caterers, photographers, and florists, ensuring that every vendor involved meets our high standards of quality.</p>
                <span class="close-hint">Click to return</span>
            </div>
        </div>
        <div class="info-box" onclick="this.classList.toggle('active')">
            <i class="fa-solid fa-user-tie"></i>
            <h3>Professional Staff</h3>
            <p class="short-desc">Experienced coordinators for smooth events.</p>
            <div class="info-details">
                <span class="pro-badge">Certified Specialists</span>
                <p>From security to guest relations, our on-site team is trained to handle the unexpected, allowing you to enjoy your event without any stress.</p>
                <span class="close-hint">Click to return</span>
            </div>
        </div>
        <div class="info-box" onclick="this.classList.toggle('active')">
            <i class="fa-solid fa-headset"></i>
            <h3>Client Support</h3>
            <p class="short-desc">Informed and confident from start to finish.</p>
            <div class="info-details">
                <span class="pro-badge">24/7 Priority Support</span>
                <p>We provide 24/7 communication channels and regular progress updates so you are always in the loop regarding your event's planning phases.</p>
                <span class="close-hint">Click to return</span>
            </div>
        </div>
    </div>
</section>
<?php include 'footer.php'; ?>