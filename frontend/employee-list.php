<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Employee List</title>

  <!-- Bootstrap CSS for styling (optional but recommended) -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
  />

  <!-- Link to your JavaScript file -->
  <script src="http://127.0.0.1/oreiro-reden/frontend/javascript/employees.js?v=1.0.3" defer></script>
</head>
<body>
  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Employee List</h2>
    </div>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Employee ID</th>
          <th>Full Name</th>
          <th>Department</th>
          <th>Email</th>
        </tr>
      </thead>
      <tbody id="employeeTableBody">
        <!-- Employee data will be inserted here dynamically -->
      </tbody>
    </table>
  </div>
</body>
</html>
