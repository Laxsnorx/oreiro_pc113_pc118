<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Sidebar</title>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <style>
    body {
      background: #eaeef6;
      font-family: 'Open Sans', sans-serif;
      margin: 0;
      padding: 0;
    }
    .navbar {
      position: fixed;
      top: 1rem;
      left: 1rem;
      background: #fff;
      border-radius: 15px;
      padding: 1rem 0;
      box-shadow: 0 0 40px rgba(0, 0, 0, 0.05);
      height: calc(100vh - 2rem);
      width: 270px; /* was 250px, increased to 270px */
      overflow: hidden;
    }
    .navbar__menu {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .navbar__item {
      margin-bottom: 1rem;
    }
    .navbar__link {
      display: flex;
      align-items: center;
      padding: 1rem;
      text-decoration: none;
      color: #333333;
      font-size: 16px;
      transition: all 300ms ease;
      position: relative;
      border-radius: 12px;
      font-family: 'Poppins', sans-serif;
    }
    .navbar__link:hover {
      background-color: #406ff3;
      color: #fff;
      transform: scale(1.05);
      box-shadow: 0 8px 20px rgba(64, 111, 243, 0.3);
    }
    .navbar__link i {
      min-width: 24px;
      height: 24px;
    }
    .navbar__link span {
      margin-left: 12px;
    }
    body {
      font-family: 'Poppins', sans-serif;
    }
    button {
      background-color: #4A90E2;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    button:hover {
      background-color: #357ABD;
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    /* Logo CSS */
    .logo {
      display: flex;
      align-items: center;
      justify-content: flex-start;
      padding: 20px;
      font-family: 'Poppins', sans-serif;
    }

    .logo-icon {
      width: 60px;
      height: 60px;
      margin-right: 0;
    }

    .logo-text {
      font-size: 28px;
      font-weight: 800;
      color: #406ff3;
      margin-left: 0;
    }

    /* Header CSS */
    .header {
      position: fixed;
      top: 1rem;
      right: 1rem;
      background-color: #fff;
      border-radius: 15px;
      padding: 0.8rem 1.2rem;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: auto;
      z-index: 10;
      font-family: 'Poppins', sans-serif;
    }

    .header__user {
      display: flex;
      align-items: center;
      position: relative;
      color: #333333;
    }

    .header__user img {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      margin-right: 10px;
    }

    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #fff;
      min-width: 160px;
      box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
      z-index: 1;
      right: 0;
      border-radius: 10px;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    .dropdown-content a {
      color: #333333;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }

    .dropdown-content a:hover {
      background-color: #406ff3;
      color: #fff;
    }

    /* Media Queries for Responsiveness */
    @media (max-width: 768px) {
      .navbar {
        width: 200px;
      }

      .logo-text {
        font-size: 22px;
      }

      .header {
        top: 0.5rem;
        right: 0.5rem;
        padding: 0.5rem;
      }

      .header__user span {
        display: none;
      }

      .navbar__link {
        font-size: 14px;
      }
    }

    @media (max-width: 480px) {
      .navbar {
        width: 180px;
        top: 0.5rem;
        left: 0.5rem;
      }

      .logo-text {
        font-size: 18px;
      }

      .header__user {
        display: none;
      }

      .dropdown-button {
        display: block;
      }

      .navbar__link {
        padding: 0.8rem;
        font-size: 12px;
      }

      .dropdown-content {
        min-width: 120px;
      }
    }

    @media (min-width: 1024px) {
      .navbar {
        width: 270px;
      }

      .logo-text {
        font-size: 28px;
      }

      .header {
        top: 1rem;
        right: 1rem;
        padding: 0.8rem 1.2rem;
      }
    }
  </style>
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>

<!-- Header -->
<div class="header">
  <div class="header__user">
    <!-- <img src="profile-pic.jpg" alt="User Profile"> -->
    <span>John Doe</span>
    <div class="dropdown">
      <button class="dropdown-button">☰</button>
      <div class="dropdown-content">
        <a href="#" onclick="redirectToProfileManagement()">Profile</a>
        <button id="logoutButton">Logout</button>
      </div>
    </div>
  </div>
</div>

<!-- Sidebar -->
<nav class="navbar">
  <ul class="navbar__menu">
    <div class="logo">
      <img src="../public/images/logo.png" alt="PerdSheet Logo" class="logo-icon">
      <span class="logo-text">PerdSheet</span>
    </div>
    <li class="navbar__item">
      <a href="#" class="navbar__link">
        <i data-feather="grid"></i><span>Dashboard</span>
      </a>
    </li>
    <li class="navbar__item">
      <a href="#" class="navbar__link" onclick="redirectToGradesManagement()">
        <i data-feather="book-open"></i><span>Grades Management</span>
      </a>
    </li>
    <li class="navbar__item">
      <a href="#" class="navbar__link">
        <i data-feather="file-text"></i><span>Reports Management</span>
      </a>
    </li>
  </ul>
</nav>

<script>
    feather.replace();
    
    document.getElementById('logoutButton').addEventListener('click', function() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, log out!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            localStorage.removeItem('authToken');  
            sessionStorage.removeItem('authToken');  
            localStorage.clear();
            sessionStorage.clear();
            
            // Show a "logged out successfully" SweetAlert
            Swal.fire({
                title: 'Logged out successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                // Redirect to the login page after showing the success message
                window.location.href = '/oreiro-reden/system-frontend/login.php';
            });
        }
    });
});


  function redirectToGradesManagement() {
    window.location.href = 'grade-management.php';
  }
  function redirectToProfileManagement() {
    window.location.href = 'profile.php';
  }
</script>

</body>
</html>
