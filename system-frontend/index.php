<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PerdSheet School</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
    :root {
      --primary: #406ff3;
      --primary-dark: #234ad6;
      --gray-bg: #f8f9fa;
      --text-dark: #2c3e50;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--gray-bg);
      color: var(--text-dark);
    }

    h1, h2, h5 {
      font-weight: 600;
    }

    /* Navbar */
    .navbar-brand span {
      font-size: 1.4rem;
      font-weight: 700;
      color: var(--primary);
    }

    .nav-link {
      color: var(--primary) !important;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .nav-link:hover {
      color: var(--primary-dark) !important;
    }

    /* Hero */
    .hero {
      background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
        url('https://images.unsplash.com/photo-1588072432836-e10032774350?auto=format&fit=crop&w=1470&q=80') center/cover no-repeat;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: white;
      position: relative;
    }

    .hero h1 {
      font-size: 3.5rem;
    }

    .hero p {
      font-size: 1.3rem;
      margin-top: 15px;
      color: #eee;
    }

    .hero .btn {
      margin-top: 25px;
      padding: 12px 30px;
      font-size: 1rem;
      background-color: var(--primary);
      border: none;
      transition: 0.4s ease;
      border-radius: 50px;
    }

    .hero .btn:hover {
      background-color: var(--primary-dark);
      transform: scale(1.05);
    }

    /* Section Headings */
    section h2 {
      color: var(--primary);
      font-weight: 700;
      margin-bottom: 2rem;
    }

    /* Features */
    .card-feature {
      border: none;
      background: white;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s ease;
    }

    .card-feature:hover {
      transform: translateY(-8px);
    }

    .feature-icon {
      font-size: 2.5rem;
      color: var(--primary);
      margin-bottom: 15px;
    }

    /* About */
    #about img {
      width: 100%;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Divider */
    .divider {
      height: 2px;
      background: linear-gradient(to right, transparent, var(--primary), transparent);
      margin: 60px auto;
      width: 80%;
    }

    /* Footer */
    footer {
      background: white;
      padding: 50px 0;
      color: #555;
      font-size: 0.95rem;
      border-top: 1px solid #ccc;
    }

    footer h6 {
      color: var(--primary);
      font-weight: 600;
      margin-bottom: 1rem;
    }

    footer a {
      color: var(--primary);
      text-decoration: none;
    }

    footer a:hover {
      text-decoration: underline;
    }

    .social-icons a {
      font-size: 1.4rem;
      margin-right: 15px;
      color: var(--primary);
      transition: color 0.3s;
    }

    .social-icons a:hover {
      color: var(--primary-dark);
    }

    .footer-bottom {
      margin-top: 20px;
      font-size: 0.85rem;
      color: #999;
    }
  </style>
</head>
<body data-bs-spy="scroll" data-bs-target="#navbarNav" data-bs-offset="100">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm" data-aos="fade-down">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="public/images/logo.png" alt="Logo" width="40" class="me-2">
      <span>PerdSheet</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Keep everything the same as the previous version, but replace these parts -->

<!-- HERO -->
<section class="hero" id="home">
  <div class="content" data-aos="fade-up">
    <h1>Welcome to PerdSheet School Management System</h1>
    <p>An intelligent, secure, and efficient platform designed for academic institutions</p>
    <a href="login.php" class="btn btn-primary fw-semibold shadow">Get Started</a>
  </div>
</section>

<!-- FEATURES -->
<section class="container py-5" id="features">
  <h2 class="text-center" data-aos="fade-up">Key System Features</h2>
  <div class="row g-4">
    <div class="col-md-4" data-aos="fade-right">
      <div class="card-feature text-center">
        <div class="feature-icon"><i class="fas fa-tachometer-alt"></i></div>
        <h5>Comprehensive Dashboard</h5>
        <p>Gain a consolidated view of academic operations with real-time statistics and actionable insights.</p>
      </div>
    </div>
    <div class="col-md-4" data-aos="fade-up">
      <div class="card-feature text-center">
        <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
        <h5>Secure Multi-Role Access</h5>
        <p>Role-based authentication tailored for administrators, educators, and students, ensuring data confidentiality and system integrity.</p>
      </div>
    </div>
    <div class="col-md-4" data-aos="fade-left">
      <div class="card-feature text-center">
        <div class="feature-icon"><i class="fas fa-file-pdf"></i></div>
        <h5>Professional Report Generation</h5>
        <p>Generate downloadable academic documents and performance summaries in PDF format for official use.</p>
      </div>
    </div>
  </div>
</section>

<!-- ABOUT -->
<section class="bg-light py-5" id="about">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6 mb-4 mb-md-0" data-aos="fade-right">
        <img src="https://images.unsplash.com/photo-1584697964192-2978793e259c?auto=format&fit=crop&w=800&q=80" alt="Students">
      </div>
      <div class="col-md-6" data-aos="fade-left">
        <h2>About PerdSheet</h2>
        <p>PerdSheet is a robust academic management system developed to support schools in maintaining and evaluating academic performance seamlessly. With intuitive navigation and strict security protocols, it serves as an all-in-one tool for data-driven decisions.</p>
        <p>The platform empowers educators, streamlines student tracking, and simplifies administrative responsibilities—ensuring excellence in educational management.</p>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="container text-center">
    <div class="row mb-4">
      <div class="col-md-4" data-aos="fade-up">
        <h6>Quick Links</h6>
        <ul class="list-unstyled">
          <li><a href="#">Home</a></li>
          <li><a href="#features">Features</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="login.php">Portal Access</a></li>
        </ul>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
        <h6>Contact Information</h6>
        <p>Email: <a href="mailto:info@perdsheet.com">info@perdsheet.com</a></p>
        <p>Phone: +123 456 7890</p>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
        <h6>Connect With Us</h6>
        <div class="social-icons">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      &copy; 2025 PerdSheet School Management System. All rights reserved.
    </div>
  </div>
</footer>


<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,
    once: true
  });
</script>
</body>
</html>
