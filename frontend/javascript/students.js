$(document).ready(function () {
  const token = localStorage.getItem("token");
  const $tbody = $("#studentTableBody");


  if (!token) {
    window.location.href = "login.php";
    return;
  }


  $("#logoutButton").on("click", function () {
    localStorage.removeItem("token");

    $.get("logout.php", function () {
      window.location.href = "login.php";
    });
  });


  $.ajax({
    url: "http://127.0.0.1:8000/api/students",
    method: "GET",
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: "application/json",
    },
    success: function (data) {
      console.log("Students:", data);

      if (!Array.isArray(data)) {
        $tbody.html(`<tr><td colspan="5">Invalid data format</td></tr>`);
        return;
      }

      $tbody.empty();

      data.forEach(function (student) {
        const row = `
          <tr>
            <td>${student.id}</td>
            <td>${student.name}</td>
            <td>${student.age}</td>
            <td>${student.email}</td>
            <td>${student.course || "N/A"}</td>
          </tr>
        `;
        $tbody.append(row);
      });
    },
    error: function (xhr, status, error) {
      console.error("Error fetching students:", error);
      $tbody.html(`<tr><td colspan="5">Error loading student data.</td></tr>`);
    },
  });
});
