<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Marketplace — Buy & Sell with Ease</title>
    <meta name="description" content="Mini Marketplace is a simple platform to register, list, and sell items online. Built with PHP & MySQL.">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/landing.css">
</head>
<body>

<!-- ── Navbar ── -->
<nav class="navbar">
    <a href="index.php" class="brand">🛍️ Mini Marketplace</a>
    <a href="index.php" class="active">Home</a>
    <?php if ($is_logged_in): ?>
        <a href="dashboard.php">Dashboard</a>
        <a href="view_items.php">Marketplace</a>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="view_items.php">Browse</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    <?php endif; ?>
</nav>

<!-- ── Hero Section ── -->
<section class="hero">
    <div class="hero-bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>
    <div class="hero-content">
        <div class="hero-badge">✨ Web Technology Project</div>
        <h1 class="hero-title">
            Your Local<br>
            <span class="gradient-text">Marketplace</span><br>
            Reimagined
        </h1>
        <p class="hero-subtitle">
            Buy, sell, and discover amazing items from your community. 
            List your products in seconds and reach buyers instantly.
        </p>
        <div class="hero-actions">
            <a href="register.php" class="btn-hero btn-hero-primary">
                🚀 Start Selling Free
            </a>
            <a href="view_items.php" class="btn-hero btn-hero-outline">
                🔍 Browse Items
            </a>
        </div>
        <div class="hero-stats">
            <div class="stat">
                <span class="stat-icon">📦</span>
                <div>
                    <strong>Simple Listings</strong>
                    <span>Add items in seconds</span>
                </div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <span class="stat-icon">🔒</span>
                <div>
                    <strong>Secure Accounts</strong>
                    <span>Session-based auth</span>
                </div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <span class="stat-icon">🖼️</span>
                <div>
                    <strong>Image Uploads</strong>
                    <span>Show off your products</span>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-visual">
        <div class="mockup-card">
            <div class="mockup-img">🎧</div>
            <div class="mockup-info">
                <div class="mockup-title">Wireless Headphones</div>
                <div class="mockup-price">Rs. 4,500</div>
                <div class="mockup-tag">Electronics</div>
            </div>
        </div>
        <div class="mockup-card mockup-card-2">
            <div class="mockup-img">👟</div>
            <div class="mockup-info">
                <div class="mockup-title">Nike Air Max</div>
                <div class="mockup-price">Rs. 8,200</div>
                <div class="mockup-tag">Fashion</div>
            </div>
        </div>
        <div class="mockup-card mockup-card-3">
            <div class="mockup-img">📚</div>
            <div class="mockup-info">
                <div class="mockup-title">Programming Books</div>
                <div class="mockup-price">Rs. 1,200</div>
                <div class="mockup-tag">Education</div>
            </div>
        </div>
    </div>
</section>

<!-- ── Features Section ── -->
<section class="features">
    <div class="features-inner">
        <div class="section-label">Why Mini Marketplace?</div>
        <h2 class="section-title">Everything you need to sell online</h2>
        <p class="section-sub">A complete platform built with PHP & MySQL — simple, fast, and functional.</p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="background:#ede9ff; color:#6C63FF;">📝</div>
                <h3>Easy Registration</h3>
                <p>Sign up in moments and get your own personal seller dashboard to manage all your listings.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:#dcfce7; color:#16a34a;">📸</div>
                <h3>Image Uploads</h3>
                <p>Showcase your products with photos. Upload images directly when you create a listing.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:#fef3c7; color:#d97706;">✏️</div>
                <h3>Edit & Delete</h3>
                <p>Full control over your listings — update prices, descriptions, or remove items anytime.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:#fee2e2; color:#dc2626;">🛒</div>
                <h3>Browse All Items</h3>
                <p>Explore everything listed on the marketplace. Click any item to see full details and the seller.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:#e0f2fe; color:#0284c7;">🔒</div>
                <h3>Secure Sessions</h3>
                <p>Your account is protected with PHP session management. Only you can manage your listings.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:#f3e8ff; color:#9333ea;">⚙️</div>
                <h3>Admin Panel</h3>
                <p>Admins get special privileges to manage users and oversee all marketplace activity.</p>
            </div>
        </div>
    </div>
</section>

<!-- ── How It Works ── -->
<section class="how-it-works">
    <div class="hiw-inner">
        <div class="section-label">Simple Process</div>
        <h2 class="section-title">Get started in 3 easy steps</h2>
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <h4>Create Account</h4>
                <p>Register with your name, email, and password. It's completely free.</p>
            </div>
            <div class="step-arrow">→</div>
            <div class="step">
                <div class="step-num">2</div>
                <h4>List Your Item</h4>
                <p>Add a title, description, price, category, and upload a photo.</p>
            </div>
            <div class="step-arrow">→</div>
            <div class="step">
                <div class="step-num">3</div>
                <h4>Reach Buyers</h4>
                <p>Your item goes live instantly for everyone on the marketplace to see.</p>
            </div>
        </div>
    </div>
</section>

<!-- ── CTA Banner ── -->
<section class="cta-banner">
    <div class="cta-inner">
        <div class="cta-blob"></div>
        <h2>Ready to start selling?</h2>
        <p>Join Mini Marketplace today — it's free, fast, and built for you.</p>
        <div class="cta-actions">
            <a href="register.php" class="btn-hero btn-hero-white">Create Free Account</a>
            <a href="login.php" class="btn-hero btn-hero-outline-white">Already have an account? Login →</a>
        </div>
    </div>
</section>

<!-- ── Footer ── -->
<footer class="footer">
    <p>© <?= date('Y') ?> <strong>Mini Marketplace</strong> — Built with HTML, CSS, PHP & MySQL &nbsp;|&nbsp; Web Technology Project</p>
</footer>

</body>
</html>