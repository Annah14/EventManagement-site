<?php include 'header.php'; ?>
<section class="page-section">
    <h2>Contact</h2>
    <p class="section-text">Have a question or want a custom quote? Send us your details and we will respond quickly.</p>
    <div class="contact-form contact-page-form">
        <form method="post" action="contact.php">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="subject" placeholder="Subject" required>
            <textarea name="message" rows="5" placeholder="Your message" required></textarea>
            <button class="btn-primary" type="submit">Send Message</button>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo '<p class="message success">Thank you! Your message has been received. We will contact you soon.</p>';
        }
        ?>
    </div>
</section>
<?php include 'footer.php'; ?>