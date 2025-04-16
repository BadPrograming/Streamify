<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    $sql = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$password')";


    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Registrasi Berhasil');
                window.location.href = 'login.php';
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi | Streamify</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="img/Streamify.png">
</head>
<body>
    <div class="header">
        <div class="logo">Streamify</div>
        <div class="kotak">
            <div class="container">    
                <form action="" method="post" class="registration-form">
                    <h2>Create Your Account</h2>

                        <label for="username">Username  :</label>
                        <input type="text" name="username" id="username" required>

                        <label for="email">Email Address  :</label>
                        <input type="text" name="email" id="email" required>
                    
                        <label for="password">Password  :</label>
                        <input type="password" name="password" id="password" required>
                    
                        <label for="password2">Confirm Password  :</label>
                        <input type="password" name="password2" id="password2" required>
                    
                        <div class="terms">
                        <label for="terms"><input type="checkbox" required>I agree to all <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a></label>

                        </div>

                        <button type="submit" name="register">Register</button>
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