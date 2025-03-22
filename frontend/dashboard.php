<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
  <link rel="icon" type="image/png" href="public/images/user.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="http://127.0.0.1/oreiro-reden/frontend/styles/dashboard.css" />
  <script src="http://127.0.0.1/oreiro-reden/frontend/javascript/dashboard.js?v=1.0.2" defer></script>
</head>
<body>
  <aside>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="user-list.php">👤 User's Lists</a>
    <a href="employee-list.php">👥 Employee's Lists</a>
    <a href="student-list.php">🎓 Student's Lists</a>
    <button id="logoutButton">Logout</button>
  </aside>

  <main id="mainContent"></main>

  <script src="javascript/users.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
