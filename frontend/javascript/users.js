$(document).ready(function () {
  const token = localStorage.getItem("token");
  const $tbody = $("#userTableBody");

  if (!token) {
    window.location.href = "login.php";
    return;
  }

  $.ajax({
    url: "http://127.0.0.1:8000/api/user",
    method: "GET",
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: "application/json",
    },
    success: function (data) {
      if (!Array.isArray(data)) {
        $tbody.html(`<tr><td colspan="4">Invalid data format</td></tr>`);
        return;
      }

      $tbody.empty();
      data.forEach(function (user) {
        const row = `
          <tr>
            <td>${user.id}</td>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${user.role || "N/A"}</td>
          </tr>
        `;
        $tbody.append(row);
      });
    },
    error: function (xhr, status, error) {
      console.error("Error fetching users:", error);
      $tbody.html(`<tr><td colspan="4">Error loading user data.</td></tr>`);
    },
  });
});
