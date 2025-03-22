document.addEventListener("DOMContentLoaded", () => {
  const token = localStorage.getItem("token");
  if (!token) return (window.location.href = "login.php");

  async function fetchEmployees() {
    try {
      const res = await fetch("http://127.0.0.1:8000/api/employees", {
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: "application/json",
        },
      });

      if (!res.ok) throw new Error("Failed to fetch employees");

      const data = await res.json();
      const employees = data.data || data; // Adjust if API wraps data

      const tbody = document.getElementById("employeeTableBody");
      tbody.innerHTML = ""; // Clear any existing rows

      employees.forEach((emp) => {
        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${emp.id}</td>
          <td>${emp.name}</td>
          <td>${emp.department || "N/A"}</td>
          <td>${emp.email}</td>
        `;
        tbody.appendChild(row);
      });
    } catch (e) {
      console.error("Error loading employees:", e);
    }
  }

  fetchEmployees();
});
