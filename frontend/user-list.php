<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User List</title>

  <!-- Bootstrap for styling -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
  />

  <!-- Your users.js file -->
  <script src="http://127.0.0.1/oreiro-reden/frontend/javascript/users.js?v=1.0.3" defer></script>
</head>
<body>
  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>User List</h2>
    </div>

    <table class="table table-hover table-bordered">
      <thead class="table-dark">
        <tr>
          <th>User ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
        </tr>
      </thead>
      <tbody id="userTableBody">
        <!-- API data will be inserted here -->
      </tbody>
    </table>
  </div>
</body>
</html>
