<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Employee List</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="styles/employee-list.css?v=1.0.1" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="http://127.0.0.1/oreiro-reden/frontend/javascript/employees.js?v=1.0.3" defer></script>
</head>
<body>
  <?php include 'partials/sidebar.php'; ?>

  <div class="main-content">
    <div class="container mt-5">
      <h2 class="mb-4">Employee List</h2>
      <table class="table table-hover table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Employee ID</th>
            <th>Full Name</th>
            <th>Department</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody id="employeeTableBody">
          <!-- Populated by JS -->
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>
