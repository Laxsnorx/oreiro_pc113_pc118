document.addEventListener("DOMContentLoaded", () => {
  document.getElementById("logoutButton").addEventListener("click", () => {
    localStorage.removeItem("token");
    fetch("logout.php").then(() => {
      window.location.href = "login.php";
    });
  });

  // Your dashboard-specific logic here
  document.getElementById(
    "mainContent"
  ).innerHTML += `<p>Welcome to the Dashboard!</p>`;
});
