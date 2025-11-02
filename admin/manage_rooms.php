<?php
session_start();
include('db_connect.php');

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle delete room
if (isset($_GET['delete'])) {
    $room_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM rooms WHERE id='$room_id'");
    echo "<script>alert('Room deleted successfully!'); window.location='manage_rooms.php';</script>";
    exit();
}

// Handle Add Room
if (isset($_POST['add_room'])) {
    $room_type = mysqli_real_escape_string($conn, $_POST['room_type']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $insert = "INSERT INTO rooms (room_type, price, status, description) 
               VALUES ('$room_type', '$price', '$status', '$description')";
    
    if (mysqli_query($conn, $insert)) {
        echo "<script>alert('New room added successfully!'); window.location='manage_rooms.php';</script>";
    } else {
        echo "<script>alert('Error adding room!');</script>";
    }
}

// Fetch all rooms
$result = mysqli_query($conn, "SELECT * FROM rooms ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Rooms | Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ======= Navbar ======= -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 py-3">
  <a class="navbar-brand" href="admin_dashboard.php">🏨 Admin Panel</a>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a class="nav-link active" href="manage_rooms.php">Manage Rooms</a></li>
      <li class="nav-item"><a class="nav-link" href="manage_bookings.php">Bookings</a></li>
      <li class="nav-item"><a class="nav-link" href="manage_users.php">Customers</a></li>
      <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
    </ul>
  </div>
</nav>

<!-- ======= Main Section ======= -->
<div class="container mt-5">
  <h2 class="text-center mb-4 fw-bold">🏠 Manage Rooms</h2>

  <!-- ======= Add New Room Form ======= -->
  <div class="card shadow p-4 mb-4 rounded-4">
    <h4 class="mb-3">➕ Add New Room</h4>
    <form method="POST">
      <div class="row">
        <div class="col-md-3 mb-3">
          <input type="text" name="room_type" class="form-control" placeholder="Room Type" required>
        </div>
        <div class="col-md-3 mb-3">
          <input type="number" name="price" class="form-control" placeholder="Price (₹)" required>
        </div>
        <div class="col-md-3 mb-3">
          <select name="status" class="form-select" required>
            <option value="Available">Available</option>
            <option value="Booked">Booked</option>
            <option value="Maintenance">Maintenance</option>
          </select>
        </div>
        <div class="col-md-3 mb-3">
          <button type="submit" name="add_room" class="btn btn-primary w-100">Add Room</button>
        </div>
      </div>
      <textarea name="description" class="form-control" placeholder="Room Description (optional)" rows="2"></textarea>
    </form>
  </div>

  <!-- ======= Room List ======= -->
  <div class="card shadow p-4 rounded-4">
    <h4 class="mb-3">🛏️ All Rooms</h4>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Price (₹)</th>
            <th>Status</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                <td><?php echo number_format($row['price'], 2); ?></td>
                <td>
                  <span class="badge 
                    <?php 
                      if ($row['status'] == 'Available') echo 'bg-success';
                      elseif ($row['status'] == 'Booked') echo 'bg-danger';
                      else echo 'bg-warning text-dark';
                    ?>">
                    <?php echo $row['status']; ?>
                  </span>
                </td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td>
                  <a href="edit_room.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                  <a href="manage_rooms.php?delete=<?php echo $row['id']; ?>" 
                     class="btn btn-sm btn-danger"
                     onclick="return confirm('Are you sure you want to delete this room?');">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-muted">No rooms found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ======= Scripts ======= -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
