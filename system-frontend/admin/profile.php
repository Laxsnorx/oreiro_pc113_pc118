<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Admin Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
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

    input, select {
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
  </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="form-container">
  <h2>Update Admin Profile</h2>
  <form id="adminUpdateForm" enctype="multipart/form-data">
    <div class="form-group">
      <label for="name">Full Name:</label>
      <input type="text" id="name" name="name" placeholder="Enter your full name">
    </div>

    <div class="form-group">
      <label for="email">Email Address:</label>
      <input type="email" id="email" name="email" placeholder="Enter your email">
    </div>

    <div class="form-group">
      <label for="phone_number">Phone Number:</label>
      <input type="text" id="phone_number" name="phone_number" placeholder="Enter your phone number">
    </div>

    <div class="form-group">
      <label for="role">Role:</label>
      <select id="role" name="role">
        <option value="admin">Admin</option>
        <option value="teacher">Teacher</option>
        <option value="student">Student</option>
      </select>
    </div>

    <div class="form-group">
      <label for="password">New Password:</label>
      <input type="password" id="password" name="password" placeholder="Enter new password">
    </div>

    <div class="form-group">
      <label for="image">Profile Image:</label>
      <input type="file" id="image" name="image">
    </div>

    <button type="submit" class="full-width">Update Profile</button>
  </form>
</div>

</body>
</html>
