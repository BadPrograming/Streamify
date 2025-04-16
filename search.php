<?php 
include('db.php');

session_start();
if($_SESSION['id'] != true) {
    echo '<script>window.location="login.php"</script>';
}

// Ambil id pengguna dari sesi
$user_id = $_SESSION['id'];

// Query untuk mengambil data pengguna
$sql = "SELECT email, username, password FROM user WHERE id = ?"; // Sesuaikan query sesuai kebutuhan
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($email, $username, $password);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

// Sensor password (tampilkan huruf pertama dan sensor sisanya)
function sensor_password($password) {
    return substr($password, 0, 1) . str_repeat('*', strlen($password) - 1);
}

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $sql = "SELECT * FROM film WHERE title LIKE '%$query%'";
    $result = $conn->query($sql);
} else {
    $result = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Homepage</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="img/Streamify.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body>
    <header>
        <div class="navbar">
            <div class="left-section">
                <div class="logo">S</div>
                <nav>
                    <ul>
                        <li><a href="home.php">Home</a></li>
                        <li><a href="#">TV Show</a></li>
                        <li><a href="#">Genre</a></li>
                        <li><a href="mylist.php">My List</a></li>
                        <li><a href="history.php">History</a></li>
                    </ul>
                </nav>
            </div>
            <div class="right-section">
                <div class="search-bar">
                <form action="" method="GET">
                    <input type="text" name="query" placeholder="Search...">
                    <button type="submit">🔍</button>
                </form>
                </div>
                <div class="user-icon" onclick="toggleProfileModal()">👤</div>
            </div>
        </div>
    </header>
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="toggleProfileModal()">&times;</span>
            <h2>Profile</h2>
            <hr>
            <br>
            <h3>Account</h3>
            <div class="info">
            <p>Email: <?php echo htmlspecialchars($email); ?></p>
            <p>Username: <?php echo htmlspecialchars($username); ?></p>
            <p>Password: <?php echo htmlspecialchars(sensor_password($password)); ?></p>
        </div>
        <br>
            <hr>
            <br>
            <div class="section">
                <h3>About</h3>
                <p>Streamify</p>
                <p>by Streamify Inc.</p>
                <p>Version: 9.11.22</p>
            </div>
            <a href="logout.php" class="signout">SIGN OUT</a>
        </div>
    </div>
    <div id="results">
    <h1>Results found: <?php echo isset($query) ? $query : ''; ?></h1>
    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="video">';
            echo '<a href="movie_detail.php?id=' . $row["id_film"] . '">';
            echo '<img src="' . $row["poster_path"] . '" alt="' . $row["title"] . '">';
            echo '<div class="video-details">';
            echo '<h2>' . $row["title"] . '</h2>';
            echo '</a>';
            echo '<p>' . $row["release_date"] . '</p>';
            echo '<p>' . $row["description"] . '</p>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>No results found</p>';
    }
    $conn->close();
    ?>
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
<script src="script.js"></script>
<script src="float.js"></script>
</body>
</html>