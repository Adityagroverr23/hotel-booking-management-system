<?php
session_start();
include('db_connect.php');

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch statistics
$total_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM rooms"))['total'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$total_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings"))['total'];
$total_payments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) AS total FROM payments"))['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Hotel Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ======= Admin Navbar ======= -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 py-3">
  <a class="navbar-brand" href="admin_dashboard.php">🏨 Admin Panel</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <div class="collapse navbar-collapse" id="adminNavbar">
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a class="nav-link" href="manage_rooms.php">Manage Rooms</a></li>
      <li class="nav-item"><a class="nav-link" href="manage_bookings.php">Bookings</a></li>
      <li class="nav-item"><a class="nav-link" href="manage_users.php">Customers</a></li>
      <li class="nav-item"><a class="nav-link" href="view_reports.php">Reports</a></li>
      <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
    </ul>
  </div>
</nav>

<!-- ======= Dashboard Content ======= -->
<div class="container mt-5">
    <h2 class="text-center mb-4 fw-bold">📊 Admin Dashboard</h2>
    <div class="row g-4">

        <!-- Rooms -->
        <div class="col-md-3">
            <div class="card shadow text-center border-0 rounded-4 p-3 bg-primary text-white">
                <h5>Total Rooms</h5>
                <h3><?php echo $total_rooms; ?></h3>
            </div>
        </div>

        <!-- Users -->
        <div class="col-md-3">
            <div class="card shadow text-center border-0 rounded-4 p-3 bg-success text-white">
                <h5>Total Customers</h5>
                <h3><?php echo $total_users; ?></h3>
            </div>
        </div>

        <!-- Bookings -->
        <div class="col-md-3">
            <div class="card shadow text-center border-0 rounded-4 p-3 bg-warning text-dark">
                <h5>Total Bookings</h5>
                <h3><?php echo $total_bookings; ?></h3>
            </div>
        </div>

        <!-- Payments -->
        <div class="col-md-3">
            <div class="card shadow text-center border-0 rounded-4 p-3 bg-danger text-white">
                <h5>Total Revenue</h5>
                <h3>₹<?php echo number_format($total_payments, 2); ?></h3>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <div class="text-center">
        <h4>Welcome, Admin 👋</h4>
        <p class="text-muted">Use the navigation bar above to manage rooms, view bookings, and track customers.</p>
        <a href="manage_rooms.php" class="btn btn-outline-primary btn-lg mt-2">Manage Rooms</a>
    </div>
</div>

<!-- ======= Scripts ======= -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>
