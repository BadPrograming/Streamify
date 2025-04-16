<?php
include 'db.php';

session_start();
if($_SESSION['id'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$user_id = $_SESSION['id'];

$sql = "SELECT film.* FROM film
        JOIN watchlist ON film.id_film = watchlist.id_film
        WHERE watchlist.id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $movies = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

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

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My List</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="img/Streamify.png">
</head>
<body>
<header>
        <div class="navbar">
            <div class="left-section">
                <div class="logo">S</div>
                <nav>
                    <ul>
                        <li><a href="home.php">Home</a></li>
                        <li><a href="genre.php">Genre</a></li>
                        <li><a href="mylist.php">My List</a></li>
                        <li><a href="history.php">History</a></li>
                    </ul>
                </nav>
            </div>
            <div class="right-section">
                <div class="search-bar">
                <form action="search.php" method="GET">
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
<main>
    <section class="carousel-section">
            <h2>My List</h2>
            <div class="carousel-container">
                <button class="carousel-button left">&lt;</button>
                <div class="carousel">
    <?php if (!empty($movies)): ?>
            <?php foreach ($movies as $movie): ?>
                <div class="carousel-item">
                <a href="movie_detail.php?id=<?php echo $movie['id_film']; ?>">
                    <img src="<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                </a>
                    <div><?php echo htmlspecialchars($movie['title']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-button right">&gt;</button>
        <?php else: ?>
            <p>You have no movies in your watchlist.</p>
        <?php endif; ?>
    </div>
</section>
        </main>
    <br><br>
<footer>
    <p>&copy; 2024 Streamify. All rights reserved.</p>
</footer>
<script src="script.js"></script>
<script src="float.js"></script>
</body>
</html>
