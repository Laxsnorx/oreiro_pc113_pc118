<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Update Instructor Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    body {
      background: #eaeef6;
      font-family: 'Open Sans', sans-serif;
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .form-container {
      background: #fff;
      border-radius: 15px;
      padding: 2rem;
      width: 700px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      font-size: 40px;
      color: #406ff3;
      margin-bottom: 1.5rem;
    }

    form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem 2rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    label {
      margin-bottom: 0.5rem;
      font-weight: bold;
    }

    input,
    select {
      padding: 0.8rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
    }

    .full-width {
      grid-column: span 2;
    }

    button {
      grid-column: span 2;
      background-color: #406ff3;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 1rem;
    }

    button:hover {
      background-color: #3050c4;
    }

    #profileImagePreview {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      display: block;
      margin: 0 auto 20px auto;
      border: 2px solid #ccc;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <?php include 'sidebar.php'; ?>

  <div class="form-container">
    <h2>Update Instructor Profile</h2>

    <!-- Profile Image Preview -->
    <img
      id="profileImagePreview"
      src="http://127.0.0.1:8000/storage/default.png"
      alt="Profile Image"
    />

    <form id="instructorUpdateForm" enctype="multipart/form-data">
      <div class="form-group">
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter your full name" />
      </div>

      <div class="form-group">
        <label for="email">Email Address:</label>
        <input
          type="email"
          id="email"
          name="email"
          placeholder="Enter your email"
        />
      </div>

      <div class="form-group">
        <label for="contact_number">Phone Number:</label>
        <input
          type="text"
          id="contact_number"
          name="contact_number"
          placeholder="Enter your phone number"
        />
      </div>

      <div class="form-group">
        <label for="password">New Password:</label>
        <input
          type="password"
          id="password"
          name="password"
          placeholder="Enter new password"
        />
      </div>

      <div class="form-group">
        <label for="image">Profile Image:</label>
        <input type="file" id="image" name="image" accept="image/*" />
      </div>

      <button type="submit" class="full-width">Update Profile</button>
    </form>
  </div>

  <script>
    const token = localStorage.getItem('token');
    const instructorId = localStorage.getItem('edit_instructor_id');

    if (!instructorId || !token) {
      Swal.fire({
        icon: 'error',
        title: 'Missing Information',
        text: 'Instructor ID or token is not set in localStorage.',
      });
    } else {
      // Load instructor data
      fetch(`http://127.0.0.1:8000/api/instructors/${instructorId}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.message === 'Instructor not found') {
            Swal.fire('Error', 'Instructor not found.', 'error');
            return;
          }

          document.getElementById('name').value = data.name || '';
          document.getElementById('email').value = data.email || '';
          document.getElementById('contact_number').value = data.contact_number || '';
          document.getElementById('role').value = data.role || 'Instructor';

          // Use storage path for image or fallback to default image
          const imageUrl = data.image
            ? `http://127.0.0.1:8000/storage/${data.image}`
            : 'http://127.0.0.1:8000/storage/default.png';
          document.getElementById('profileImagePreview').src = imageUrl;
        })
        .catch((err) => {
          Swal.fire('Error', 'Failed to load instructor details.', 'error');
        });
    }

    // Preview the new uploaded image immediately
    document.getElementById('image').addEventListener('change', function(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = e => {
          document.getElementById('profileImagePreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    });

    // Handle form submission
    document
      .getElementById('instructorUpdateForm')
      .addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('_method', 'PUT'); // Laravel expects _method=PUT for updates

        fetch(`http://127.0.0.1:8000/api/instructors/${instructorId}`, {
          method: 'POST', // Use POST with _method=PUT for Laravel
          headers: {
            Authorization: `Bearer ${token}`,
            // Do NOT set Content-Type header when using FormData
          },
          body: formData,
        })
          .then((res) => res.json())
          .then((response) => {
            if (response.message === 'Instructor updated successfully') {
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message,
              });

              // Update profile image preview if new URL returned
              if (response.instructor && response.instructor.image_url) {
                document.getElementById('profileImagePreview').src =
                  response.instructor.image_url;
              }
            } else {
              Swal.fire('Notice', response.message || 'Instructor updated.', 'info');
            }
          })
          .catch((err) => {
            Swal.fire('Error', 'Failed to update instructor.', 'error');
          });
      });
  </script>
</body>
</html>
