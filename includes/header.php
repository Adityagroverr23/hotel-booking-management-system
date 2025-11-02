<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hotel Booking System</title>
<!-- Bootstrap CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
<div class="container">
<a class="navbar-brand" href="/index.php">HotelSys</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
<span class="navbar-toggler-icon"></span>
</button>


<div class="collapse navbar-collapse" id="navmenu">
<ul class="navbar-nav ms-auto">
<li class="nav-item"><a class="nav-link" href="/index.php">Home</a></li>
<?php if(isset($_SESSION['user_id'])): ?>
<li class="nav-item"><a class="nav-link" href="/book_room.php">Book Room</a></li>
<li class="nav-item"><a class="nav-link" href="/view_bookings.php">My Bookings</a></li>
<li class="nav-item"><a class="nav-link" href="/logout.php">Logout</a></li>
<?php else: ?>
<li class="nav-item"><a class="nav-link" href="/register.php">Register</a></li>
<li class="nav-item"><a class="nav-link" href="/login.php">Login</a></li>
<?php endif; ?>
<li class="nav-item"><a class="nav-link" href="/admin/admin_dashboard.php">Admin</a></li>
</ul>
</div>
</div>
</nav>
<div class="container mt-4">