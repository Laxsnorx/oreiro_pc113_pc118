<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PerdSheet Login</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      background: #eaeef6;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      position: relative;
    }

    .form_main {
      width: 90%;
      max-width: 400px;
      background: rgba(255, 255, 255, 0.85);
      padding: 40px 30px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
      border-radius: 12px;
      position: relative;
      backdrop-filter: blur(10px);
      transition: all 0.3s ease;
      z-index: 1;
    }

    .logo {
      text-align: center;
      font-size: 2.75rem;
      font-weight: 800;
      background: linear-gradient(90deg, #4A90E2, #88C3F7);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 10px;
      font-family: 'Poppins', sans-serif;
      letter-spacing: 1px;
    }

    .heading {
      text-align: center;
      font-size: 1.1rem;
      color: #b0b0b0; /* Light gray */
      font-weight: 400;
      margin-bottom: 25px;
      font-style: italic;
      opacity: 0.95;
    }

    .inputContainer {
      width: 100%;
      margin-bottom: 20px;
      position: relative;
      z-index: 2;
    }

    .inputIcon {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      z-index: 3;
    }

    .inputField {
      width: 100%;
      height: 45px;
      padding-left: 40px;
      font-size: 1rem;
      border: none;
      border-bottom: 2px solid #ccc;
      background-color: transparent;
      color: #333;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .inputField:focus {
      outline: none;
      border-color: #66a3ff;
      box-shadow: 0 0 5px rgba(102, 163, 255, 0.6);
    }

    .inputField::placeholder {
      color: #888;
    }

    .inputField:hover {
      border-color: #66a3ff;
    }

    #button {
      width: 100%;
      height: 45px;
      background-color: #4A90E2;
      border: none;
      color: #fff;
      font-size: 1rem;
      font-weight: bold;
      margin-top: 10px;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.3s ease, box-shadow 0.3s ease;
      z-index: 2;
      position: relative;
    }

    #button:hover {
      background-color: #357ABD;
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    #button:active {
      transform: translateY(0);
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .forgotLink {
      display: block;
      text-align: center;
      margin-top: 15px;
      font-size: 0.85rem;
      color: #406ff3;
      text-decoration: none;
      z-index: 2;
      position: relative;
      transition: color 0.3s ease;
    }

    .forgotLink:hover {
      color: #2c1880;
      text-decoration: underline;
    }

    /* Floating Icons */
    .floating-icons {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -1;
    }

    .floating-icons img {
      position: absolute;
      width: 60px;
      height: 60px;
      opacity: 0.7;
      animation: floatIcons 12s infinite ease-in-out;
      animation-delay: calc(2s * var(--index)); /* Add delay based on index for staggered effect */
    }

    /* Randomize Icon Position */
    .floating-icons img:nth-child(1) {
      top: 5%;
      left: 8%;
    }

    .floating-icons img:nth-child(2) {
      top: 20%;
      left: 25%;
    }

    .floating-icons img:nth-child(3) {
      top: 35%;
      left: 50%;
    }

    .floating-icons img:nth-child(4) {
      top: 55%;
      left: 70%;
    }

    .floating-icons img:nth-child(5) {
      top: 10%;
      left: 85%;
    }

    .floating-icons img:nth-child(6) {
      top: 45%;
      left: 80%;
    }

    .floating-icons img:nth-child(7) {
      top: 60%;
      left: 30%;
    }

    .floating-icons img:nth-child(8) {
      top: 70%;
      left: 20%;
    }

    .floating-icons img:nth-child(9) {
      top: 15%;
      left: 60%;
    }

    .floating-icons img:nth-child(10) {
      top: 75%;
      left: 40%;
    }

    @keyframes floatIcons {
      0% { transform: translateY(0) rotate(0); }
      25% { transform: translateY(-20px) rotate(15deg); }
      50% { transform: translateY(0) rotate(0); }
      75% { transform: translateY(20px) rotate(-15deg); }
      100% { transform: translateY(0) rotate(0); }
    }

    @media (max-width: 480px) {
      .form_main {
        padding: 30px 20px;
      }

      .heading {
        font-size: 1.5rem;
      }

      #button {
        font-size: 0.95rem;
      }
    }
  </style>
</head>
<body>

  <!-- Floating Icons -->
  <div class="floating-icons">
    <img src="https://cdn-icons-png.flaticon.com/512/1046/1046784.png" alt="school" style="--index: 1;">
    <img src="https://cdn-icons-png.flaticon.com/512/206/206039.png" alt="gradebook" style="--index: 2;">
    <img src="https://cdn-icons-png.flaticon.com/512/126/126495.png" alt="certificate" style="--index: 3;">
    <img src="https://cdn-icons-png.flaticon.com/512/1007/1007891.png" alt="student" style="--index: 4;">
    <img src="https://cdn-icons-png.flaticon.com/512/349/349063.png" alt="paper" style="--index: 5;">
    <img src="https://cdn-icons-png.flaticon.com/512/1643/1643947.png" alt="books" style="--index: 6;">
    <img src="https://cdn-icons-png.flaticon.com/512/2872/2872805.png" alt="teacher" style="--index: 7;">
    <img src="https://cdn-icons-png.flaticon.com/512/2575/2575266.png" alt="assignment" style="--index: 8;">
  </div>

  <!-- Login Form -->
  <form class="form_main" id="loginForm">
    <div class="logo">PerdSheet</div>
    <p class="heading">Welcome back! Login to your account</p>
    <div class="inputContainer">
      <i class="fas fa-envelope inputIcon"></i> <!-- Font Awesome email icon -->
      <input type="text" id="email" class="inputField" placeholder="email" required />
    </div>
    <div class="inputContainer">
      <i class="fas fa-lock inputIcon"></i> <!-- Font Awesome lock icon -->
      <input type="password" id="password" class="inputField" placeholder="Password" required />
    </div>
    <button type="submit" id="button">Login</button>
    <a href="#" class="forgotLink">Forgot password?</a>
  </form>

<script>
  document.getElementById("loginForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    try {
      const response = await fetch("http://127.0.0.1:8000/api/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({ email, password }), // using username instead of email
      });

      if (!response.ok) throw new Error("Invalid credentials");

      const data = await response.json();

      localStorage.setItem("token", data.token);
      localStorage.setItem("user", JSON.stringify(data.user));

      Swal.fire({
        icon: "success",
        title: "Login Successful",
        showConfirmButton: false,
        timer: 1500,
      });

      setTimeout(() => {
        const role = data.user.role;
        if (role === "admin") {
          window.location.href = "admin/admindashboard.php";
        } else if (role === "teacher") {
          window.location.href = "teacher/teacherdashboard.php";
        } else if (role === "student") {
          window.location.href = "students/studentdashboard.php";
        } else {
          Swal.fire("Unknown role", "Redirect failed", "error");
        }
      }, 1600);

    } catch (err) {
      Swal.fire("Login Failed", err.message, "error");
    }
  });
</script>


</body>
</html>
