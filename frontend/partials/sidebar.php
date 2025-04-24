<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://127.0.0.1/oreiro-reden/frontend/javascript/employees.js?v=1.0.1" defer></script>
    <link rel="stylesheet" href="styles/sidebar.css?v=1.0.1" />
    <title>Document</title>
    <style>
        /* Add your styles here */
    </style>
</head>
<body>
    <aside>
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="profile.php">👤 Profile</a>
        <a href="user-list.php">👤 User's Lists</a>
        <a href="employee-list.php">👥 Employee's Lists</a>
        <a href="student-list.php">🎓 Student's Lists</a>
        <button id="logoutButton">Logout</button>
    </aside>

    <script>
    
        document.getElementById('logoutButton').addEventListener('click', function() {
            // Clear the session or token
            localStorage.removeItem('authToken');  
            sessionStorage.removeItem('authToken');  
            localStorage.clear();
            sessionStorage.clear();
            window.location.href = 'login.php';
        });
    </script>
</body>
</html>
