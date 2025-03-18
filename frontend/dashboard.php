<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/png" href="http://127.0.0.1/oreiro-reden/frontend/public/images/user.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="http://127.0.0.1/oreiro-reden/frontend/styles/dashboard.css">
    <script src="http://127.0.0.1/oreiro-reden/frontend/javascript/dashboard.js" defer></script>
</head>
<body class="bg-[#f8f4f3] font-sans text-gray-900 antialiased">
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar -->
        <div class="w-full md:w-64 bg-white shadow-lg p-6 flex flex-col justify-between">
            <div>
                <h2 class="font-bold text-3xl">USER<span class="bg-[#f84525] text-white px-2 rounded-md">LOGIN</span></h2>
                <nav class="mt-8">
                    <ul>
                        <li id="dashboard" class="p-3 text-lg font-medium text-gray-700 hover:bg-gray-200 rounded-md cursor-pointer flex items-center gap-2">
                            <span>&#127968;</span> Dashboard
                        </li>
                        <li id="showUsers" class="p-3 text-lg font-medium text-gray-700 hover:bg-gray-200 rounded-md cursor-pointer flex items-center gap-2">
                            <span>&#128101;</span> User's Lists</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div>
                <button id="logoutButton" class="w-full bg-[#f84525] text-white py-3 text-lg font-semibold rounded-md hover:bg-red-800 transition-all">
                    Logout
                </button>
            </div>
        </div>
        <!-- Main Content Area -->
        <div class="flex-1 p-6 md:p-10">
            <div id="mainContent" class="bg-white shadow-md p-6 rounded-lg">
                <p class="text-lg text-gray-700">Click "User's Lists" to view users.</p>
            </div>
        </div>
    </div>
</body>
</html>
