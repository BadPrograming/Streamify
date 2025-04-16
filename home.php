<?php
include('db.php');

session_start();
if ($_SESSION['email'] != true) {
    header("Location: login.php");
    exit();
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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
    <h2 class="animated pulse">Welcome, <?php echo $_SESSION['username']; ?>!</h2> 
        <section class="carousel-section">
            <h2>Action</h2>
            <div class="carousel-container">
                <button class="carousel-button left">&lt;</button>
                <div class="carousel">
        <?php
        $genre_id = 1;
        $genre_name = 'Action';
        $sql_movies = "SELECT f.*, g.genre_nama 
FROM film f
JOIN moviegenre mg ON f.id_film = mg.id_film
JOIN genre g ON g.genre_id = mg.genre_id
                       WHERE mg.genre_id = $genre_id OR g.genre_nama = '$genre_name'";
        $result_movies = $conn->query($sql_movies);
        if ($result_movies->num_rows > 0) {
            while($movie = $result_movies->fetch_assoc()) {
        ?>
                <div class="carousel-item">
                <a href="movie_detail.php?id=<?php echo $movie['id_film']; ?>">
                    <img src="<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                </a>
                    <div><?php echo htmlspecialchars($movie['title']); ?></div>
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

        <section class="carousel-section">
            <h2>Adventure</h2>
            <div class="carousel-container">
                <button class="carousel-button left">&lt;</button>
                <div class="carousel">
        <?php
        $genre_id = 2;
        $genre_name = 'Adventure';
        $sql_movies = "SELECT f.*, g.genre_nama 
FROM film f
JOIN moviegenre mg ON f.id_film = mg.id_film
JOIN genre g ON g.genre_id = mg.genre_id
                       WHERE mg.genre_id = $genre_id OR g.genre_nama = '$genre_name'";
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
        
        <section class="carousel-section">
            <h2>Romance</h2>
            <div class="carousel-container">
                <button class="carousel-button left">&lt;</button>
                <div class="carousel">
        <?php
        $genre_id = 3;
        $genre_name = 'Romance';
        $sql_movies = "SELECT f.*, g.genre_nama 
FROM film f
JOIN moviegenre mg ON f.id_film = mg.id_film
JOIN genre g ON g.genre_id = mg.genre_id
                       WHERE mg.genre_id = $genre_id OR g.genre_nama = '$genre_name'";
        $result_movies = $conn->query($sql_movies);
        if ($result_movies->num_rows > 0) {
            while($movie = $result_movies->fetch_assoc()) {
        ?>
                <div class="carousel-item">
                <a href="movie_detail.php?id=<?php echo $movie['id_film']; ?>">
                    <img src="<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                </a>
                    <div><?php echo htmlspecialchars($movie['title']); ?></div>
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

        <section class="carousel-section">
            <h2>Drama</h2>
            <div class="carousel-container">
                <button class="carousel-button left">&lt;</button>
                <div class="carousel">
        <?php
        $genre_id = 4;
        $genre_name = 'Drama';
        $sql_movies = "SELECT f.*, g.genre_nama 
FROM film f
JOIN moviegenre mg ON f.id_film = mg.id_film
JOIN genre g ON g.genre_id = mg.genre_id
                       WHERE mg.genre_id = $genre_id OR g.genre_nama = '$genre_name'";
        $result_movies = $conn->query($sql_movies);
        if ($result_movies->num_rows > 0) {
            while($movie = $result_movies->fetch_assoc()) {
        ?>
                <div class="carousel-item">
                <a href="movie_detail.php?id=<?php echo $movie['id_film']; ?>">
                    <img src="<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                </a>
                    <div><?php echo htmlspecialchars($movie['title']); ?></div>
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
        <section class="carousel-section">
            <h2>Comedy</h2>
            <div class="carousel-container">
                <button class="carousel-button left">&lt;</button>
                <div class="carousel">
        <?php
        $genre_id = 8;
        $genre_name = 'Comedy';
        $sql_movies = "SELECT f.*, g.genre_nama 
FROM film f
JOIN moviegenre mg ON f.id_film = mg.id_film
JOIN genre g ON g.genre_id = mg.genre_id
                       WHERE mg.genre_id = $genre_id OR g.genre_nama = '$genre_name'";
        $result_movies = $conn->query($sql_movies);
        if ($result_movies->num_rows > 0) {
            while($movie = $result_movies->fetch_assoc()) {
        ?>
                <div class="carousel-item">
                <a href="movie_detail.php?id=<?php echo $movie['id_film']; ?>">
                    <img src="<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                </a>
                    <div><?php echo htmlspecialchars($movie['title']); ?></div>
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
        <section class="carousel-section">
            <h2>Sci-fi</h2>
            <div class="carousel-container">
                <button class="carousel-button left">&lt;</button>
                <div class="carousel">
        <?php
        $genre_id = 6;
        $genre_name = 'Sci-fi';
        $sql_movies = "SELECT f.*, g.genre_nama 
FROM film f
JOIN moviegenre mg ON f.id_film = mg.id_film
JOIN genre g ON g.genre_id = mg.genre_id
                       WHERE mg.genre_id = $genre_id OR g.genre_nama = '$genre_name'";
        $result_movies = $conn->query($sql_movies);
        if ($result_movies->num_rows > 0) {
            while($movie = $result_movies->fetch_assoc()) {
        ?>
                <div class="carousel-item">
                <a href="movie_detail.php?id=<?php echo $movie['id_film']; ?>">
                    <img src="<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                </a>
                    <div><?php echo htmlspecialchars($movie['title']); ?></div>
                </div>
        <?php
            }
        } else {
            echo "<div>No movies found</div>";
        }
        $conn->close();
    ?>
                </div>
                <button class="carousel-button right">&gt;</button>
            </div>
        </section>

    </main>

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