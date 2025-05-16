<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PerdSheet School</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      font-family: 'Poppins', sans-serif;
      background-color: #f4f7fa;
    }

    .hero {
      background: url('https://images.unsplash.com/photo-1588072432836-e10032774350?auto=format&fit=crop&w=1470&q=80') no-repeat center center/cover;
      height: 100vh;
      position: relative;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
    }

    .overlay {
      background: rgba(0, 0, 0, 0.5);
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      z-index: 1;
    }

    /* Header Styles */
    .header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background-color: white; /* White background for header */
      padding: 20px 50px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 2;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional: to add a shadow under the header */
    }

    .header .logo {
      display: flex;
      align-items: center;
    }

    .header .logo img {
      width: 40px; /* Adjust logo size */
      margin-right: 10px; /* Space between logo and text */
    }

    .header .logo span {
      font-size: 1.8rem;
      font-weight: 600;
      color: #4A90E2; /* Blue color for text */
    }

    .header nav a {
      margin: 0 15px;
      color: #4A90E2; /* Blue text */
      text-decoration: none;
      font-weight: 300;
      transition: color 0.3s ease;
    }

    .header nav a:hover {
      color: #0066cc; /* Darker blue on hover */
    }

    .hero-text {
      z-index: 2;
      color: white;
    }

    .hero-text h1 {
      font-size: 3.5rem;
      margin-bottom: 15px;
      font-weight: 600;
      color: #4A90E2; /* Blue text */
    }

    .hero-text .meta {
      font-size: 1.1rem;
      color: #4A90E2; /* Blue text */
    }

    .cta-box {
      position: absolute;
      right: 20px;
      top: 30%;
      background: #fff;
      color: #333;
      padding: 20px;
      border-radius: 10px;
      width: 220px;
      font-size: 1rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      z-index: 2;
    }

    .cta-box h3 {
      font-size: 1.2rem;
      margin-bottom: 10px;
      font-weight: 600;
    }

    .cta-box p {
      margin-bottom: 15px;
      font-size: 0.9rem;
    }

    .cta-box a {
      display: inline-block;
      margin-top: 10px;
      color: #4A90E2;
      font-weight: bold;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .cta-box a:hover {
      color: #0066cc;
    }

    /* Fade-in effect for hero text */
    .hero-text {
      animation: fadeIn 2s ease-in;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    /* Hover effect for CTA box */
    .cta-box:hover {
      box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
      transform: translateY(-5px);
    }
  </style>
</head>
<body>

  <!-- Header Section -->
  <div class="header">
    <div class="logo">
      <!-- Correct image path -->
      <img src="public/images/logo.png" alt="Logo">
      <span>PerdSheet</span>
    </div>
    <nav>
      <a href="#">Home</a>
      <a href="#">About</a>
      <a href="login.php">Login</a>
    </nav>
  </div>

  <!-- Hero Section -->
  <section class="hero">
    <div class="overlay"></div>

    <div class="hero-text">
      <h1>The Best School Website For School Grading System</h1>
      <p class="meta">By Admin · Education</p>
    </div>
  </section>

</body>
</html>
