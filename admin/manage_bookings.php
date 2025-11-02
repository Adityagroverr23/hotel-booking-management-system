<?php
session_start();
include('db_connect.php');

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle booking delete
if (isset($_GET['delete'])) {
    $booking_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM bookings WHERE id='$booking_id'");
    echo "<script>alert('Booking deleted successfully!'); window.location='manage_bookings.php';</script>";
    exit();
}

// Fetch all bookings
$sql = "SELECT b.*, u.name AS customer_name, r.room_type 
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN rooms r ON b.room_id = r.id
        ORDER BY b.id DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Bookings | Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ======= Admin Navbar ======= -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 py-3">
  <a class="navbar-brand" href="admin_dashboard.php">🏨 Admin Panel</a>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a class="nav-link" href="manage_rooms.php">Manage Rooms</a></li>
      <li class="nav-item"><a class="nav-link active" href="manage_bookings.php">Bookings</a></li>
      <li class="nav-item"><a class="nav-link" href="manage_users.php">Customers</a></li>
      <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
    </ul>
  </div>
</nav>

<!-- ======= Manage Bookings ======= -->
<div class="container mt-5">
  <h2 class="text-center mb-4 fw-bold">📅 Manage Bookings</h2>

  <div class="table-responsive shadow p-3 bg-white rounded-4">
    <table class="table table-bordered table-hover align-middle text-center">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Customer</th>
          <th>Room</th>
          <th>Check-In</th>
          <th>Check-Out</th>
          <th>Status</th>
          <th>Amount</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
              <td><?php echo htmlspecialchars($row['room_type']); ?></td>
              <td><?php echo date('d M Y', strtotime($row['check_in'])); ?></td>
              <td><?php echo date('d M Y', strtotime($row['check_out'])); ?></td>
              <td>
                <span class="badge 
                  <?php 
                    if ($row['status'] == 'Confirmed') echo 'bg-success';
                    elseif ($row['status'] == 'Cancelled') echo 'bg-danger';
                    else echo 'bg-warning text-dark';
                  ?>">
                  <?php echo $row['status']; ?>
                </span>
              </td>
              <td>₹<?php echo number_format($row['amount'], 2); ?></td>
              <td>
                <a href="update_booking.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="manage_bookings.php?delete=<?php echo $row['id']; ?>" 
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8" class="text-muted">No bookings found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ======= Scripts ======= -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
