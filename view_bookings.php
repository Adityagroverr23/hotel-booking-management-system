<?php
session_start();
include_once('db_connect.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle booking cancellation
if (isset($_GET['cancel_id'])) {
    $cancel_id = intval($_GET['cancel_id']);
    $sql = "DELETE FROM bookings WHERE id = $cancel_id AND user_id = $user_id";
    if ($conn->query($sql)) {
        $message = "Booking cancelled successfully!";
    } else {
        $message = "Error cancelling booking: " . $conn->error;
    }
}

// Fetch user's bookings
$sql = "SELECT b.id AS booking_id, 
               r.room_type, 
               r.price, 
               b.check_in, 
               b.check_out, 
               b.status, 
               b.amount
        FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        WHERE b.user_id = $user_id
        ORDER BY b.id DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings - Hotel Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f7f9fc;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin-top: 70px;
        }
        .table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #0d6efd;
            font-weight: 600;
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="container">
    <h2 class="mb-4 text-center">My Bookings</h2>

    <?php if (isset($message)) { ?>
        <div class="alert alert-info text-center"><?= $message; ?></div>
    <?php } ?>

    <table class="table table-striped table-hover text-center">
        <thead class="table-primary">
            <tr>
                <th>Booking ID</th>
                <th>Room Type</th>
                <th>Price</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['booking_id']; ?></td>
                    <td><?= ucfirst($row['room_type']); ?></td>
                    <td>₹<?= number_format($row['price'], 2); ?></td>
                    <td><?= $row['check_in']; ?></td>
                    <td><?= $row['check_out']; ?></td>
                    <td>
                        <?php if ($row['status'] == 'Confirmed') { ?>
                            <span class="badge bg-success">Confirmed</span>
                        <?php } elseif ($row['status'] == 'Pending') { ?>
                            <span class="badge bg-warning text-dark">Pending</span>
                        <?php } else { ?>
                            <span class="badge bg-secondary"><?= $row['status']; ?></span>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="view_bookings.php?cancel_id=<?= $row['booking_id']; ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to cancel this booking?');">
                            Cancel
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-muted">No bookings found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="text-center mt-3">
        <a href="index.php" class="btn btn-primary">Back to Home</a>
    </div>
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>
