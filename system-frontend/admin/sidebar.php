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
.navbar__item.dropdown:hover .dropdown-content {
  display: block;
}

.navbar__item.dropdown .dropdown-content {
  display: none;
  list-style: none;
  padding-left: 1rem;
  margin-top: 0.2rem;
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
/* --- Header --- */
.header {
  position: fixed;
  top: 1rem;
  right: 1rem;
  background-color: #fff;
  border-radius: 15px;
  padding: 0.8rem 1.2rem;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  display: flex;
  justify-content: flex-end;
  align-items: center;
  width: auto;
  z-index: 10;
  font-family: 'Poppins', sans-serif;
}

/* Header user container */
.header__user {
  display: flex;
  align-items: center; /* vertically center avatar and text */
  cursor: pointer;
  position: relative;
  gap: 10px;
  color: #333;
  font-weight: 600;
  user-select: none;
}

.header__user img {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #406ff3;
  background-color: #ddd;
  vertical-align: middle;
}

.header__user span {
  font-size: 16px;
  line-height: 36px; /* align text vertically with avatar */
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
<!-- Header -->
<div class="header">
  <div class="header__user dropdown" aria-label="User menu">
    <img src="https://ui-avatars.com/api/?name=User&background=406ff3&color=fff&rounded=true" alt="User Avatar" id="userAvatar" />
    <span id="userName">Loading...</span>
    <button class="dropdown-button" aria-haspopup="true" aria-expanded="false" aria-controls="userDropdown" id="dropdownButton">▼</button>
    <div class="dropdown-content" role="menu" aria-hidden="true" id="userDropdown">
      <a href="#" onclick="redirectToProfileManagement()" role="menuitem" tabindex="0">Profile</a>
      <a href="#" id="logoutButton" role="menuitem" tabindex="0">Logout</a>
    </div>
  </div>
</div>



  <!-- Sidebar -->
  <nav class="navbar" role="navigation" aria-label="Sidebar navigation">
    <ul class="navbar__menu">
      <div class="logo">
        <img src="../public/images/logo.png" alt="PerdSheet Logo" class="logo-icon" />
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
      <li class="navbar__item dropdown">
        <a href="#" class="navbar__link">
          <i data-feather="users"></i><span>User Management ▾</span>
        </a>
        <ul class="dropdown-content" style="margin-left: 2.5rem; position: static; background: none; box-shadow: none; padding-left: 1rem;">
          <li><a href="admin-management.php" class="navbar__link">Admins</a></li>
          <li><a href="#" class="navbar__link" onclick="redirectToInstructorManagement()">Instructors</a></li>
          <li><a href="#" class="navbar__link" onclick="redirectToStudentManagement()">Students</a></li>
        </ul>
      </li>
      <li class="navbar__item">
        <a href="#" class="navbar__link" onclick="redirectToReportsManagement()">
          <i data-feather="file-text"></i><span>Reports Management</span>
        </a>
      </li>
    </ul>
  </nav>
<script>
// Activate feather icons
feather.replace();

// Get user from localStorage (expected format: { name: "...", avatar: "url" })
const user = JSON.parse(localStorage.getItem('user'));

if (user && user.name) {
  document.getElementById('userName').textContent = user.name;

  if (user.avatar) {
    document.getElementById('userAvatar').src = user.avatar;
  } else {
    // fallback: initials avatar
    const initials = user.name.split(' ').map(n => n[0]).join('');
    document.getElementById('userAvatar').src = `https://ui-avatars.com/api/?name=${initials}&background=406ff3&color=fff&rounded=true`;
  }
} else {
  // guest fallback
  document.getElementById('userName').textContent = 'Guest';
  document.getElementById('userAvatar').src = `https://ui-avatars.com/api/?name=Guest&background=999&color=fff&rounded=true`;
}

// Logout button with SweetAlert2 confirmation
document.getElementById('logoutButton').addEventListener('click', function (e) {
  e.preventDefault();
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
      localStorage.clear();
      sessionStorage.clear();
      Swal.fire({
        title: 'Logged out successfully!',
        icon: 'success',
        confirmButtonText: 'OK'
      }).then(() => {
        window.location.href = '/oreiro-reden/system-frontend/login.php';
      });
    }
  });
});

function redirectToGradesManagement() {
    window.location.href = 'grade-management.php';
}

function redirectToUserManagement() {
    window.location.href = 'user-management.php';
}

function redirectToProfileManagement() {
    window.location.href = 'profile.php';
}

function redirectToReportsManagement() {
    window.location.href = 'reports-management.php';
}
function redirectToStudentManagement() {
    window.location.href = 'student-management.php';
}
function redirectToInstructorManagement() {
    window.location.href = 'instructor-management.php';
}

</script>

</body>
</html>
