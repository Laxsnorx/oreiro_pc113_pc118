document.addEventListener("DOMContentLoaded", () => {
  const token = localStorage.getItem("token");
  const tbody = document.getElementById("userTableBody");

  if (!token) {
    window.location.href = "login.php";
    return;
  }

  fetch("http://127.0.0.1:8000/api/user", {
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: "application/json",
    },
  })
    .then((res) => res.json())
    .then((data) => {
      console.log("Users:", data); // Optional: debug

      if (!Array.isArray(data)) {
        tbody.innerHTML = `<tr><td colspan="4">Invalid data format</td></tr>`;
        return;
      }

      // Clear previous rows if any
      tbody.innerHTML = "";

      // Populate table rows
      data.forEach((user) => {
        const row = `
          <tr>
            <td>${user.id}</td>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${user.role || "N/A"}</td>
          </tr>
        `;
        tbody.insertAdjacentHTML("beforeend", row);
      });
    })
    .catch((error) => {
      console.error("Error fetching users:", error);
      tbody.innerHTML = `<tr><td colspan="4">Error loading user data.</td></tr>`;
    });
});
