document.addEventListener("DOMContentLoaded", () => {
  const token = localStorage.getItem("token");
  const tbody = document.getElementById("studentTableBody");

  // Logout button functionality
  const logoutBtn = document.getElementById("logoutButton");
  if (logoutBtn) {
    logoutBtn.addEventListener("click", () => {
      localStorage.removeItem("token");
      fetch("logout.php").then(() => {
        window.location.href = "login.php";
      });
    });
  }

  if (!token) {
    window.location.href = "login.php";
    return;
  }

  fetch("http://127.0.0.1:8000/api/students", {
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: "application/json",
    },
  })
    .then((res) => res.json())
    .then((data) => {
      console.log("Students:", data);

      if (!Array.isArray(data)) {
        tbody.innerHTML = `<tr><td colspan="5">Invalid data format</td></tr>`;
        return;
      }

      tbody.innerHTML = ""; // Clear any existing rows

      data.forEach((student) => {
        const row = `
          <tr>
            <td>${student.id}</td>
            <td>${student.name}</td>
            <td>${student.age}</td>
            <td>${student.email}</td>
            <td>${student.course || "N/A"}</td>
          </tr>
        `;
        tbody.insertAdjacentHTML("beforeend", row);
      });
    })
    .catch((error) => {
      console.error("Error fetching students:", error);
      tbody.innerHTML = `<tr><td colspan="5">Error loading student data.</td></tr>`;
    });
});
