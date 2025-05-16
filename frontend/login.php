<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>API Login</title>
    <link rel="icon" type="image/png" href="http://127.0.0.1/oreiro-reden/frontend/public/images/user.png">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="styles/login.css?v=1.0.1" />
    
</head>
<body>
    <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">
        <div>
            <h2 class="fw-bold text-center">USER<span class="px-2 rounded text-white" style="background-color: rgb(35, 39, 51);">LOGIN</span></h2>
        </div>
        <div class="card shadow mt-4 w-100 bg-white" style="max-width: 24rem;">
            <div class="card-body">
                <form id="loginForm">
                    <div class="text-center py-3">
                        <span class="h5 fw-semibold text-dark">LogIn</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-dark" for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-dark" for="password">Password</label>
                        <div class="input-group">
                            <input id="password" type="password" name="password" placeholder="Password" class="form-control" required>
                            <button type="button" id="togglePassword" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-eye" fill="currentColor" viewBox="0 0 16 16" width="16" height="16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zm-8 4.5a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9zm0-1A3.5 3.5 0 1 0 8 3a3.5 3.5 0 0 0 0 7zm0-5.5a2 2 2 0 1 1 0 4 2 2 0 0 1 0-4z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" id="remember_me" name="remember" class="form-check-input">
                        <label for="remember_me" class="form-check-label text-dark">Remember Me</label>
                    </div>
                    <div class="mb-3">
                        <div class="text-danger" id="errorMessage" style="display: none;"></div>
                        <div class="text-success" id="successMessage" style="display: none;"></div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div id="loader" class="spinner-border text-danger me-auto" role="status" style="display: none;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <button type="submit" id="loginButton" class="btn text-white fw-semibold ms-auto" style="background-color: #50586C;">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {

  if (localStorage.getItem("token")) {
    window.location.href = "dashboard.php";
    return;
  }

  const togglePassword = document.getElementById("togglePassword");
  const passwordInput = document.getElementById("password");
  const loginForm = document.getElementById("loginForm");
  const emailInput = document.getElementById("email");
  const loader = document.getElementById("loader");
  const loginButton = document.getElementById("loginButton");


  togglePassword.addEventListener("click", function () {
    const type =
      passwordInput.getAttribute("type") === "password" ? "text" : "password";
    passwordInput.setAttribute("type", type);
  });


  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const email = emailInput.value.trim();
    const password = passwordInput.value.trim();

    if (!email || !password) {
      Swal.fire({
        icon: "warning",
        title: "Missing Fields",
        text: "Both fields are required.",
      });
      return;
    }

    loader.style.display = "block"; // Show the loading spinner
    loginButton.disabled = true; // Disable the login button to prevent multiple clicks

    // Perform the login request
    fetch("http://127.0.0.1:8000/api/login", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({ email, password }),
    })
      .then((response) =>
        response.json().then((data) => ({
          status: response.status,
          body: data,
        }))
      )
      .then(({ status, body }) => {
        if (status >= 200 && status < 300) {
          localStorage.setItem("token", body.token);
          Swal.fire({
            icon: "success",
            title: "Success!",
            text: "Logged in successfully!",
            timer: 1500,
            showConfirmButton: false,
          });
          // Redirect to the dashboard after successful login
          setTimeout(() => {
            window.location.href = "dashboard.php";
          }, 1600);
        } else {
          throw new Error(body.message || "Login failed. Please try again.");
        }
      })
      .catch((error) => {
        Swal.fire({
          icon: "error",
          title: "Login Failed",
          text: error.message,
        });
      })
      .finally(() => {
        loader.style.display = "none"; // Hide the loading spinner
        loginButton.disabled = false; // Enable the login button again
      });
  });
});

    </script>
</body>
</html>
