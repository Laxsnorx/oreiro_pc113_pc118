<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PerdSheet Login</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Google Font & Font Awesome -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <style>
    @keyframes gradientMove {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      min-height: 100vh;
      background: linear-gradient(270deg, #e0f2fe, #f0f9ff, #e0f2fe, #f0f9ff);
      background-size: 600% 600%;
      animation: gradientMove 40s ease infinite;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      position: relative;
      color: #344054;
      padding: 20px;
    }

    .form_main {
      width: 100%;
      max-width: 420px;
      background: rgba(255 255 255 / 0.97);
      padding: 48px 40px;
      box-shadow: 0 12px 48px rgba(149 157 165 / 0.25);
      border-radius: 28px;
      backdrop-filter: blur(24px);
      position: relative;
      z-index: 1;
      text-align: center;
      color: #334155;
      user-select: none;
      transition: box-shadow 0.3s ease;
    }
    .form_main:hover {
      box-shadow: 0 16px 64px rgba(149 157 165 / 0.35);
    }

    .logo {
  font-size: 3.2rem;
  font-weight: 700;
  color: #3b82f6; /* solid blue */
  margin-bottom: 26px;
  letter-spacing: 2px;
  user-select: text;
}


    .heading {
      font-size: 1.3rem;
      color: #64748b;
      margin-bottom: 34px;
      font-weight: 600;
    }

    .input-group-text {
      background-color: transparent;
      border: none;
      color: #60a5fa;
      font-size: 1.25rem;
      user-select: none;
      min-width: 48px;
      justify-content: center;
    }

    .form-control {
      border: none;
      border-bottom: 2.5px solid #cbd5e1;
      border-radius: 0;
      background-color: transparent;
      box-shadow: none;
      color: #475569;
      font-weight: 600;
      font-size: 1.1rem;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
      padding-left: 0.8rem;
      user-select: text;
      min-height: 44px;
    }

    .form-control::placeholder {
      color: #94a3b8;
      font-weight: 400;
      user-select: none;
    }

    .form-control:focus {
      border-color: #6366f1;
      box-shadow: 0 2px 8px rgba(99,102,241,0.3);
      background-color: rgba(255 255 255 / 0.95);
      outline: none;
    }

    .btn-primary {
      background-color: #6366f1;
      border: none;
      font-weight: 700;
      letter-spacing: 0.08em;
      transition: all 0.35s ease;
      box-shadow: 0 6px 24px rgba(99 102 241 / 0.45);
      padding: 15px;
      font-size: 1.2rem;
      border-radius: 14px;
      user-select: none;
      cursor: pointer;
      margin-top: 12px;
      min-height: 48px;
    }

    .btn-primary:hover,
    .btn-primary:focus {
      background-color: #4f46e5;
      transform: translateY(-3px);
      box-shadow: 0 10px 32px rgba(79 70 229 / 0.7);
      outline: none;
    }

    .forgotLink {
      display: block;
      text-align: center;
      margin-top: 26px;
      font-size: 1rem;
      color: #7c3aed;
      text-decoration: none;
      font-weight: 700;
      user-select: none;
      transition: color 0.3s ease;
    }

    .forgotLink:hover,
    .forgotLink:focus {
      text-decoration: underline;
      color: #5b21b6;
      outline: none;
    }

    /* Responsive tweaks */
    @media (max-width: 480px) {
      .form_main {
        padding: 36px 28px;
        max-width: 100%;
      }
      .logo {
        font-size: 2.8rem;
      }
      .heading {
        font-size: 1.15rem;
      }
      .btn-primary {
        font-size: 1.1rem;
        padding: 14px;
      }
    }

  </style>
</head>
<body>
  <form class="form_main" id="loginForm" autocomplete="off" aria-label="Login Form">
    <div class="logo" aria-label="PerdSheet logo">PerdSheet</div>
    <p class="heading">Welcome back! Login to your account</p>

    <div class="mb-3 input-group">
      <span class="input-group-text" id="email-addon"><i class="fas fa-envelope" aria-hidden="true"></i></span>
      <input type="email" class="form-control" id="email" placeholder="Email" aria-describedby="email-addon" required autocomplete="off" />
    </div>

    <div class="mb-3 input-group">
      <span class="input-group-text" id="password-addon"><i class="fas fa-lock" aria-hidden="true"></i></span>
      <input type="password" class="form-control" id="password" placeholder="Password" aria-describedby="password-addon" required autocomplete="off" />
    </div>

    <button type="submit" class="btn btn-primary w-100 mt-2" aria-label="Login Button">Login</button>
    <a href="#" class="forgotLink" tabindex="0">Forgot password?</a>
  </form>

  <script>
    document.getElementById("loginForm").addEventListener("submit", async (e) => {
      e.preventDefault();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();

      try {
        const response = await fetch("http://127.0.0.1:8000/api/login", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
          },
          body: JSON.stringify({ email, password }),
        });

        if (!response.ok) throw new Error("Invalid credentials");

        const data = await response.json();

        localStorage.setItem("token", data.token);
        localStorage.setItem("token_type", "");
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
            window.location.href = "instructor/instructordashboard.php";
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
