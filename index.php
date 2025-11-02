<?php
require 'includes/header.php';
require 'db_connect.php';


// fetch available rooms (limit)
$sql = "SELECT * FROM rooms WHERE status='available'";
$res = mysqli_query($conn, $sql);
?>


<div class="row">
<div class="col-md-8">
<h2>Available Rooms</h2>
<div class="row">
<?php while($room = mysqli_fetch_assoc($res)): ?>
<div class="col-md-6 mb-3">
<div class="card">
<?php if($room['image']): ?>
<img src="/assets/img/<?php echo htmlspecialchars($room['image']); ?>" class="card-img-top" alt="room">
<?php endif; ?>
<div class="card-body">
<h5 class="card-title"><?php echo htmlspecialchars($room['type']); ?> - <?php echo htmlspecialchars($room['room_number']); ?></h5>
<p class="card-text"><?php echo htmlspecialchars($room['description']); ?></p>
<p><strong>₹ <?php echo number_format($room['price'],2); ?></strong></p>
<a href="/book_room.php?room_id=<?php echo $room['id']; ?>" class="btn btn-primary">Book Now</a>
</div>
</div>
</div>
<?php endwhile; ?>
</div>
</div>


<div class="col-md-4">
<div class="card p-3">
<h5>About</h5>
<p>Simple college project demonstrating full stack CRUD and booking flow.</p>
</div>
</div>
</div>


<?php require 'includes/footer.php'; ?>