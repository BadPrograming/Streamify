<?php
session_start(); // Mulai sesi

include('db.php');

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan untuk memeriksa keberadaan nilai sebelum mengaksesnya
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($email) && !empty($password)) {
        $sql = "SELECT * FROM user WHERE email = ? ";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    // Login berhasil, set sesi pengguna
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['role'] = $row['role'];

                    // Redirect ke halaman yang sesuai dengan peran (role)
                    if ($_SESSION['role'] == 'admin') {
                        header("Location: read.php");
                    } elseif($_SESSION['role'] == 'user') {
                        header("Location: home.php");
                    exit();
                } else {
                    $error_message = "Invalid email or password! Try Again!";
                }
            } else {
                $error_message = "Invalid email or password! Try Again!";
            }

            $stmt->close();
        } else {
            $error_message = "Error preparing statement: " . $conn->error;
        }
    } else {
        $error_message = "Please fill in both fields!";
    }

    $conn->close();
}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Streamify</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="img/Streamify.png">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Streamify</div>
        <div class="kotak">
            <div class="container">    
                <form action="" method="POST" class="registration-form">
                    <h2>Sign In</h2>
                    <?php
    if ($error_message) {
        echo "<p class='error'>$error_message</p>";
    }
    ?>
            
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" required>
                    
                        <label for="password">Password  </label>
                        <input type="password" name="password" id="password" required>
                    

                        <button type="submit">Sign in</button>

                        <div class="forgot">
                        <label for="forgot">New to Streamify? <a href="registrasi.php"><strong>Sign up now!</strong></a></label>

                        </div>
                </form>
            </div>
        </div>
    </div>

    <div class="end">
        <div class="footer-content">
            <div class="social-media">
                <p>Connect with Us <a href="#"><i class="fa fa-instagram" style="color: #d9d9d9;"></i></a></p>
            </div>
                <div class="footer-links">
                    <a href="#">Help</a>
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                </div>
        </div>
    </div>
<footer>
    <p>&copy; 2024 Streamify. All rights reserved.</p>
</footer>
</body>
</html>