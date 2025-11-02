<?php
session_start();
include_once('db_connect.php');

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle booking form submission
if (isset($_POST['book_room'])) {
    $room_id = intval($_POST['room_id']);
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    // Validate date range
    if ($check_in >= $check_out) {
        $message = "❌ Check-out date must be after check-in date.";
    } else {
        // Check if room is available in this date range
        $check_sql = "SELECT * FROM bookings 
                      WHERE room_id = $room_id 
                      AND (
                          ('$check_in' BETWEEN check_in AND check_out)
                          OR ('$check_out' BETWEEN check_in AND check_out)
                          OR (check_in BETWEEN '$check_in' AND '$check_out')
                      )";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            $message = "⚠️ Sorry, this room is not available for the selected dates.";
        } else {
            // Insert booking
            $insert_sql = "INSERT INTO bookings (user_id, room_id, check_in, check_out, status)
                           VALUES ($user_id, $room_id, '$check_in', '$check_out', 'Confirmed')";
            if ($conn->query($insert_sql)) {
                $message = "✅ Room booked successfully!";
            } else {
                $message = "❌ Booking failed: " . $conn->error;
            }
        }
    }
}

// Fetch available rooms
$rooms = $conn->query("SELECT * FROM rooms WHERE status='Available' ORDER BY room_type ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Room - Hotel Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f7f9fc;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin-top: 70px;
            max-width: 700px;
        }
        .card {
            border: none;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        h2 {
            color: #0d6efd;
            font-weight: 600;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="container">
    <div class="card p-4">
        <h2 class="text-center">Book a Room</h2>

        <?php if (isset($message)) { ?>
            <div class="alert alert-info text-center"><?= $message; ?></div>
        <?php } ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="room_id" class="form-label">Select Room</label>
                <select name="room_id" id="room_id" class="form-select" required>
                    <option value="">-- Choose a Room --</option>
                    <?php while ($row = $rooms->fetch_assoc()) { ?>
                        <option value="<?= $row['room_id']; ?>">
                            <?= $row['room_number']; ?> - <?= ucfirst($row['room_type']); ?> (₹<?= $row['price']; ?>/night)
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="check_in" class="form-label">Check-In Date</label>
                <input type="date" name="check_in" id="check_in" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="check_out" class="form-label">Check-Out Date</label>
                <input type="date" name="check_out" id="check_out" class="form-control" required>
            </div>

            <div class="d-grid">
                <button type="submit" name="book_room" class="btn btn-primary btn-lg">Book Now</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a href="view_bookings.php" class="btn btn-outline-secondary">View My Bookings</a>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>
