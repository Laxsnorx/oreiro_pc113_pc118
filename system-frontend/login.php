<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PerdSheet Login</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Google Font & Font Awesome -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
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
      width: 100%;
      max-width: 400px;
      background: rgba(255, 255, 255, 0.9);
      padding: 40px 30px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      border-radius: 15px;
      backdrop-filter: blur(12px);
      position: relative;
      z-index: 1;
    }

    .logo {
      text-align: center;
      font-size: 2.5rem;
      font-weight: 700;
      background: linear-gradient(90deg, #4A90E2, #88C3F7);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 10px;
    }

    .heading {
      text-align: center;
      font-size: 1rem;
      color: #777;
      margin-bottom: 25px;
    }

    .input-group-text {
      background-color: transparent;
      border: none;
    }

    .form-control {
      border: none;
      border-bottom: 2px solid #ccc;
      border-radius: 0;
      background-color: transparent;
      box-shadow: none;
    }

    .form-control:focus {
      border-color: #66a3ff;
      box-shadow: none;
    }

    .btn-primary {
      background-color: #4A90E2;
      border: none;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #357ABD;
      transform: translateY(-1px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .forgotLink {
      display: block;
      text-align: center;
      margin-top: 15px;
      font-size: 0.85rem;
      color: #406ff3;
      text-decoration: none;
    }

    .forgotLink:hover {
      text-decoration: underline;
      color: #2c1880;
    }

    .floating-icons {
      position: absolute;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 0;
    }

    .floating-icons img {
      position: absolute;
      width: 60px;
      height: 60px;
      opacity: 0.7;
      animation: floatIcons 12s infinite ease-in-out;
      animation-delay: calc(2s * var(--index));
    }

    .floating-icons img:nth-child(1) { top: 5%; left: 8%; }
    .floating-icons img:nth-child(2) { top: 20%; left: 25%; }
    .floating-icons img:nth-child(3) { top: 35%; left: 50%; }
    .floating-icons img:nth-child(4) { top: 55%; left: 70%; }
    .floating-icons img:nth-child(5) { top: 10%; left: 85%; }
    .floating-icons img:nth-child(6) { top: 45%; left: 80%; }
    .floating-icons img:nth-child(7) { top: 60%; left: 30%; }
    .floating-icons img:nth-child(8) { top: 70%; left: 20%; }

    @keyframes floatIcons {
      0% { transform: translateY(0) rotate(0); }
      25% { transform: translateY(-20px) rotate(15deg); }
      50% { transform: translateY(0) rotate(0); }
      75% { transform: translateY(20px) rotate(-15deg); }
      100% { transform: translateY(0) rotate(0); }
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

    <div class="mb-3 input-group">
      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
      <input type="email" class="form-control" id="email" placeholder="Email" required>
    </div>

    <div class="mb-3 input-group">
      <span class="input-group-text"><i class="fas fa-lock"></i></span>
      <input type="password" class="form-control" id="password" placeholder="Password" required>
    </div>

    <button type="submit" class="btn btn-primary w-100 mt-2">Login</button>
    <a href="#" class="forgotLink">Forgot password?</a>
  </form>

  <!-- Login Script -->
  <script>
    document.getElementById("loginForm").addEventListener("submit", async (e) => {
      e.preventDefault();
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;

      try {
        const response = await fetch("https://rgradebackend.bdedal.online/api/login", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
          body: JSON.stringify({ email, password }),
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
          } else if (role === "instructor") {
            window.location.href = "teacher/teacherdashboard.php";
          } else if (role === "student") {
            window.location.href = "student/studentdashboard.php";
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
