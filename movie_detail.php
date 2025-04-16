<?php
include 'db.php';

session_start();
if($_SESSION['id'] != true) {
    echo '<script>window.location="login.php"</script>';
}

if (isset($_GET['id'])) {
    $movie_id = $_GET['id'];
} else {
    echo "No movie ID provided.";
    exit;
}
// Ambil id pengguna dari sesi
$user_id = $_SESSION['id'];

// Periksa apakah film sudah ada di watch_history
$query = "SELECT COUNT(*) as count FROM watch_history WHERE id = ? AND id_film = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $movie_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Jika film belum ada di riwayat, tambahkan entri baru
if ($row['count'] == 0) {
    $query = "INSERT INTO watch_history (id, id_film) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $movie_id);
    $stmt->execute();
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

// Query untuk mengambil detail film berdasarkan ID
$sql_movie_detail = "SELECT * FROM film WHERE id_film = $movie_id";
$result_movie_detail = $conn->query($sql_movie_detail);

// Memeriksa apakah film ditemukan
if ($result_movie_detail->num_rows > 0) {
    $movie = $result_movie_detail->fetch_assoc();
} else {
    echo "Movie not found";
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($movie['title']); ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="img/Streamify.png">
    <style>
                .button {
  border: none;
  color: white;
  padding: 5px 15px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  transition-duration: 0.4s;
  cursor: pointer;
}

.button1 {
  background-color: white; 
  color: black; 
  border: 2px solid #000;
}

.button1:hover {
  background-color: #000;
  color: white;
}
    </style>
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
    <div class="content">
        <div class="movie-container">
            <video controls>
                <source src="<?php echo htmlspecialchars($movie['video_path']); ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>

        <div class="movie-detail">
            <h2><?php echo htmlspecialchars($movie['title']); ?></h2>
            <p><strong>Streamify</strong> • <?php echo htmlspecialchars($movie['release_date']); ?> • <?php echo htmlspecialchars($movie['rating']); ?></p>
            <form action="add_watchlist.php" method="POST">
    <input type="hidden" name="id_film" value="<?php echo $movie_id; ?>">
    <button type="submit" class="button button1">Add to Watch List</button>
</form>
            <p><?php echo htmlspecialchars($movie['description']); ?></p>
            <hr>
            <p><strong>Cast:</strong> <?php echo htmlspecialchars($movie['cast']); ?></p>
            <hr>
            <p><strong>Director:</strong> <?php echo htmlspecialchars($movie['director']); ?></p>
        </div>

        <section class="carousel-section">
            <h2>You May Also Like</h2>
            <hr style="width:7%; height:4px;border-width:0;color:gray;background-color:gray"><br>
            <div class="carousel-container">
                <button class="carousel-button left">&lt;</button>
                <div class="carousel">
            <?php
            $genre_id = 1;
            $genre_name = 'Action';
            $sql_movies = "SELECT f.* FROM film f
                        JOIN moviegenre mg ON f.id_film = mg.id_film
                        JOIN genre g ON mg.genre_id = g.genre_id
                        WHERE mg.genre_id = $genre_id OR g.genre_nama = '$genre_name' LIMIT 7";
            $result_movies = $conn->query($sql_movies);
            if ($result_movies->num_rows > 0) {
                while($movie = $result_movies->fetch_assoc()) {
            ?>
                    <div class="carousel-item">
                    <a href="movie_detail.php?id=<?php echo $movie['id_film']; ?>">
                        <img src="<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                    </a>
                        <div><?php echo htmlspecialchars($movie['title']); ?></div>
                        <div><?php echo htmlspecialchars($movie['release_date']); ?></div>
                    </div>
            <?php
                }
            } else {
                echo "<div>No movies found</div>";
            }
            ?>
                    </div>
                <button class="carousel-button right">&gt;</button>
            </div>
        </section>
    </div>
    
<footer>
    <p>&copy; 2024 Streamify. All rights reserved.</p>
</footer>
<script src="script.js"></script>
<script src="float.js"></script>
</body>
</html>
