$(document).ready(function () {
  const token = localStorage.getItem("token");
  if (!token) {
    window.location.href = "login.php";
    return;
  }

  function fetchEmployees() {
    $.ajax({
      url: "http://127.0.0.1:8000/api/employees",
      method: "GET",
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: "application/json",
      },
      success: function (response) {
        const employees = response.data || response;
        const tbody = $("#employeeTableBody");
        tbody.empty(); // Clear any existing rows

        employees.forEach(function (emp) {
          const row = `
            <tr>
              <td>${emp.id}</td>
              <td>${emp.name}</td>
              <td>${emp.department || "N/A"}</td>
              <td>${emp.email}</td>
            </tr>
          `;
          tbody.append(row);
        });
      },
      error: function (xhr, status, error) {
        console.error("Error loading employees:", error);
      },
    });
  }

  fetchEmployees();
});
