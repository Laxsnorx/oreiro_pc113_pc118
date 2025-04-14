<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
  <link rel="icon" type="image/png" href="public/images/user.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"/>
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css"/>
  <link rel="stylesheet" href="styles/dashboard.css?v=1.0.2" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="http://127.0.0.1/oreiro-reden/frontend/javascript/dashboard.js?v=1.0.2" defer></script>
</head>
<body>
  <?php include 'partials/sidebar.php'; ?>
    <main id="mainContent" class="flex-grow-1 p-4">
      <div class="container mt-4">
        <h2 class="mb-4">Dashboard Data</h2>
        <div class="table-responsive">
          <table id="dashboardTable" class="table table-hover table-bordered nowrap">
            <tbody id="dashboardTableBody">
            </tbody>
          </table>
        </div>
      </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
