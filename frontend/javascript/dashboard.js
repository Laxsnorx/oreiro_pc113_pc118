     //! Show dashboard
    document.getElementById("dashboard").addEventListener("click", () => {
    let mainContent = document.getElementById("mainContent");

    mainContent.innerHTML = "";

    setTimeout(() => {
    
        mainContent.innerHTML = `
                <h2 class="text-2xl font-bold mb-4">Dashboard</h2>
                <p class="text-lg text-gray-700">Welcome to the dashboard. Select an option from the sidebar.</p>
            `;
    }, 300); 
    });
 //! Show users list
    document.getElementById("showUsers").addEventListener("click", fetchUsers);

    async function fetchUsers() {
    let token = localStorage.getItem("token");
    let mainContent = document.getElementById("mainContent");

    if (!token) {
    alert("You are not logged in. Please login first.");
    window.location.href = "login.php";
    return;
        }

    mainContent.innerHTML = "";

    try {
        let res = await fetch("http://127.0.0.1:8000/api/user", {
        method: "GET",
        headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
            Accept: "application/json",
        },
        });

        if (!res.ok) throw new Error("Failed to fetch users");

        let users = await res.json();

        mainContent.innerHTML = `
                <h2 class="text-2xl font-bold mb-4">User List</h2>
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-gray-300 p-2">ID</th>
                            <th class="border border-gray-300 p-2">Name</th>
                            <th class="border border-gray-300 p-2">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${users
                        .map(
                            (user) => `
                            <tr>
                                <td class="border border-gray-300 p-2">${user.id}</td>
                                <td class="border border-gray-300 p-2">${user.name}</td>
                                <td class="border border-gray-300 p-2">${user.email}</td>
                            </tr>
                        `).join("")}
                    </tbody>
                </table>
            `;
    } catch (error) {
        console.error("Error fetching users:", error);
        mainContent.innerHTML = `<p class="text-red-500 text-lg">Failed to load users. Please try again later.</p>`;
    }
    }

    //! Logout
    document.getElementById("logoutButton").addEventListener("click", async (event) => {event.preventDefault();
        let token = localStorage.getItem("token");

        if (!token) {
        alert("You are not logged in. Please login first.");
        window.location.href = "login.php";
        return;
        }

        document.getElementById("logoutButton").disabled = true;

        try {
        let res = await fetch("http://127.0.0.1:8000/api/logout", {
            method: "POST",
            headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
            Accept: "application/json",
            },
        });

        if (res.ok) {
            localStorage.removeItem("token");
            alert("Logged out successfully!");
            window.location.href = "login.php";
        } else {
            alert("Logout failed. Please try again.");
        }
        } catch (error) {
        console.error("Logout error:", error);
        alert("An error occurred. Please try again.");
        } finally {
        document.getElementById("logoutButton").disabled = false;
        }
    });
