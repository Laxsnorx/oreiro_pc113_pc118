<?php
session_start();

// Example login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Example check (replace with actual DB/API validation)
  if ($username === 'admin' && $password === 'password123') {
    $_SESSION['token'] = 'your_generated_token_here';
    header('Location: dashboard.php');
    exit();
  } else {
    $error = "Invalid login credentials!";
  }
}
?>

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="http://127.0.0.1/oreiro-reden/frontend/styles/login.css">
    <script src="http://127.0.0.1/oreiro-reden/frontend/javascript/login.js" defer></script>
    
</head>
<body>
    <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100 bg-light">
        <div>
            <h2 class="fw-bold text-center">USER<span class="bg-danger text-white px-2 rounded">LOGIN</span></h2>
        </div>
        <div class="card shadow mt-4 w-100" style="max-width: 24rem;">
            <div class="card-body">
                <form id="loginForm">
                    <div class="text-center py-3">
                        <span class="h5 fw-semibold">Log In</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-group">
                            <input id="password" type="password" name="password" placeholder="Password" class="form-control" required>
                            <button type="button" id="togglePassword" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-eye" fill="currentColor" viewBox="0 0 16 16" width="16" height="16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zm-8 4.5a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9zm0-1A3.5 3.5 0 1 0 8 3a3.5 3.5 0 0 0 0 7zm0-5.5a2 2 0 1 1 0 4 2 2 0 0 1 0-4z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" id="remember_me" name="remember" class="form-check-input">
                        <label for="remember_me" class="form-check-label">Remember Me</label>
                    </div>
                    <div class="text-danger mb-3 d-none" id="errorMessage"></div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" id="loginButton" class="btn btn-danger text-white fw-semibold">Sign In</button>
                    </div>
                    <div class="mt-3 text-center">
                        <div id="loader" class="spinner-border text-danger" role="status" style="display: none;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </form>                
            </div>
        </div>
    </div> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php if (isset($error)) echo "<p>$error</p>"; ?>