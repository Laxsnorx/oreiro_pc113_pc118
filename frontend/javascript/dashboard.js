$(document).ready(function () {
  $("#logoutButton").on("click", function () {
    localStorage.removeItem("token");

    $.get("logout.php", function () {
      window.location.href = "login.php";
    });
  });
});
