<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
  <link rel="icon" type="image/png" href="public/images/user.png" />
  <link rel="stylesheet" href="styles/dashboard.css?v=1.0.2" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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


</body>
</html>
