<?php
require 'includes/header.php';
require 'db_connect.php';
session_start();

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 🔹 Check admin login first
    $admin_check = mysqli_prepare($conn, "SELECT id, username, password FROM admin WHERE username=? LIMIT 1");
    mysqli_stmt_bind_param($admin_check, 's', $email);
    mysqli_stmt_execute($admin_check);
    $resA = mysqli_stmt_get_result($admin_check);

    if ($rowA = mysqli_fetch_assoc($resA)) {
        if (password_verify($password, $rowA['password'])) {
            $_SESSION['admin_id'] = $rowA['id'];
            header('Location: admin/admin_dashboard.php');
            exit;
        }
    }

    // 🔹 Check customer login
    $stmt = mysqli_prepare($conn, "SELECT id, password, fullname FROM users WHERE email=? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($res)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['fullname'];
            header('Location: index.php');
            exit;
        }
    }

    $err = 'Invalid credentials.';
}
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <h3>Login</h3>
        <?php if ($err) echo '<div class="alert alert-danger">' . htmlspecialchars($err) . '</div>'; ?>

        <form method="post">
            <div class="mb-3">
                <label>Email / Admin Username</label>
                <input type="text" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100">Login</button>
        </form>

        <p class="mt-3">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
