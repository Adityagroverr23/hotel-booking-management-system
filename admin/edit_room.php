<?php
session_start();
include('db_connect.php');

// Redirect if admin not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if room ID is passed
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_rooms.php");
    exit();
}

$room_id = $_GET['id'];

// Fetch room details
$sql = "SELECT * FROM rooms WHERE id = '$room_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Room not found!'); window.location='manage_rooms.php';</script>";
    exit();
}

$room = mysqli_fetch_assoc($result);

// Update room details on form submission
if (isset($_POST['update_room'])) {
    $room_type = mysqli_real_escape_string($conn, $_POST['room_type']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $update = "UPDATE rooms SET 
                room_type = '$room_type', 
                price = '$price', 
                status = '$status',
                description = '$description'
               WHERE id = '$room_id'";

    if (mysqli_query($conn, $update)) {
        echo "<script>alert('Room updated successfully!'); window.location='manage_rooms.php';</script>";
    } else {
        echo "<script>alert('Error updating room!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ======= Navbar ======= -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 py-3">
  <a class="navbar-brand" href="admin_dashboard.php">🏨 Admin Panel</a>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a class="nav-link" href="manage_rooms.php">Manage Rooms</a></li>
      <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
    </ul>
  </div>
</nav>

<!-- ======= Edit Room Form ======= -->
<div class="container mt-5">
    <h2 class="text-center mb-4 fw-bold">✏️ Edit Room</h2>
    
    <div class="card shadow p-4 mx-auto" style="max-width: 600px;">
        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Room Type</label>
                <input type="text" name="room_type" class="form-control" value="<?php echo $room['room_type']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Price (₹)</label>
                <input type="number" name="price" class="form-control" value="<?php echo $room['price']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="Available" <?php if($room['status']=='Available') echo 'selected'; ?>>Available</option>
                    <option value="Booked" <?php if($room['status']=='Booked') echo 'selected'; ?>>Booked</option>
                    <option value="Maintenance" <?php if($room['status']=='Maintenance') echo 'selected'; ?>>Maintenance</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo $room['description']; ?></textarea>
            </div>

            <div class="text-center">
                <button type="submit" name="update_room" class="btn btn-primary px-4">Update Room</button>
                <a href="manage_rooms.php" class="btn btn-secondary px-4">Cancel</a>
            </div>
        </form>
    </div>
</div>

<!-- ======= Scripts ======= -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>
