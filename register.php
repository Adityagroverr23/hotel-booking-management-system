<?php
require 'includes/header.php';
require 'db_connect.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone = trim($_POST['phone']);

    // Validation
    if (!$fullname || !$email || !$password) $errors[] = 'Please fill all required fields.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssss', $fullname, $email, $hash, $phone);

        if (mysqli_stmt_execute($stmt)) {
            $success = 'Registration successful! <a href="login.php">Click here to login</a>.';
        } else {
            $errors[] = 'Email already exists or an error occurred.';
        }
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="text-center text-primary mb-4">Create Your Account</h3>

            <?php if ($success): ?>
                <div class="alert alert-success text-center"><?= $success; ?></div>
            <?php endif; ?>

            <?php foreach ($errors as $e): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($e); ?></div>
            <?php endforeach; ?>

            <form method="post" class="shadow p-4 bg-white rounded">
                <div class="mb-3">
                    <label>Full Name</label>
                    <input class="form-control" name="fullname" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <div class="mb-3">
                    <label>Phone</label>
                    <input class="form-control" name="phone">
                </div>

                <button class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
