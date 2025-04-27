<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<style>
/* 🌟 General styles for all screens */
body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
}

aside {
    width: 250px;
    background-color: #50586C;
    height: 100vh;
    display: flex;
    flex-direction: column;
    padding-top: 20px;
    position: fixed;
}

aside a {
    color: #DCE2F0;
    padding: 15px 20px;
    text-decoration: none;
    font-size: 18px;
    transition: background 0.3s;
}

aside a:hover {
    background-color: #697089;
    border-left: 5px solid #DCE2F0;
}

#logoutButton {
    margin-top: auto;
    margin-bottom: 20px;
    margin-left: 20px;
    margin-right: 20px;
    padding: 10px;
    font-size: 16px;
    background-color: #DCE2F0;
    color: #50586C;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s;
}

#logoutButton:hover {
    background-color: #697089;
    color: #DCE2F0;
}

main {
    margin-left: 250px;
    padding: 20px;
    flex-grow: 1;
}

/* 📱 Responsive styles for screens smaller than 992px */
@media (max-width: 992px) {
    aside {
        left: -250px;
        transition: left 0.3s;
        z-index: 1000;
    }

    aside.open {
        left: 0;
    }

    main {
        margin-left: 0;
        padding: 20px;
    }
}

}
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
