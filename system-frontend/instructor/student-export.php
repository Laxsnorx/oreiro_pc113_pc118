<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Export Students with grades</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-12 col-md-6 text-center">
      <button class="btn btn-success w-100" onclick="exportExcel()">
        <i class="bi bi-file-earmark-excel"></i> Export Excel
      </button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<script>
  function exportExcel() {
    Swal.fire({
      title: 'Exporting...',
      text: 'Preparing your Excel file...',
      icon: 'info',
      showConfirmButton: false,
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });

    fetch('http://127.0.0.1:8000/api/grade/export', {
      method: 'GET',
      headers: {
    'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
  }
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Export failed');
      }
      return response.blob();
    })
    .then(blob => {
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = 'grades.xlsx';
      document.body.appendChild(a);
      a.click();
      a.remove();
      window.URL.revokeObjectURL(url);

      Swal.fire({
        title: 'Success!',
        text: 'Excel file downloaded.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
      });
    })
    .catch(() => {
      Swal.fire({
        title: 'Error!',
        text: 'Could not export the file.',
        icon: 'error',
        confirmButtonText: 'OK'
      });
    });
  }
</script>

</body>
</html>
