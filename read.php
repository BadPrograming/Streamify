<?php
include 'db.php';

session_start();
if ($_SESSION['email'] != true) {
    header("Location: login.php");
    exit();
}

$sql_admin = "SELECT f.id_film, f.title, f.description, f.release_date, f.poster_path, 
              GROUP_CONCAT(g.genre_nama SEPARATOR ', ') AS genre
              FROM film f
              LEFT JOIN moviegenre mg ON f.id_film = mg.id_film
              LEFT JOIN genre g ON mg.genre_id = g.genre_id
              GROUP BY f.id_film";


// Ambil id pengguna dari sesi
$user_id = $_SESSION['id'];

$result_admin = $conn->query($sql_admin);

// Cek apakah query berhasil
if (!$result_admin) {
    die("Query error: " . $conn->error);
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
  border: 2px solid #04AA6D;
}

.button1:hover {
  background-color: #04AA6D;
  color: white;
}

.button2 {
  background-color: white; 
  color: black; 
  border: 2px solid #008CBA;
}

.button2:hover {
  background-color: #008CBA;
  color: white;
}

.button3 {
  background-color: white; 
  color: black; 
  border: 2px solid red;
}

.button3:hover {
  background-color: red;
  color: white;
}

</style>
</head>
<body>
<header>
        <div class="navbar">
            <div class="left-section">
                <div class="logo">S</div>
            </div>
            <div class="right-section">
                <div class="user-icon" onclick="toggleProfileModal()">👤</div>
            </div>
        </div>
    </header>
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="toggleProfileModal()">&times;</span>
            <h2>Profile Admin</h2>
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
    <h2 class="animated pulse">Welcome, Admin <?php echo $_SESSION['username']; ?>!</h2> 
    <div class="head-list">
    <h2>Video List</h2>
    <div class="add-movie">
    <a href="upload.php"><button class="button button1">Add New Video</button></a>
</div>
</div>
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Release Date</th>
            <th>Poster</th>
            <th>Genres</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result_admin->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><?php echo htmlspecialchars($row['release_date']); ?></td>
            <td><img src="<?php echo htmlspecialchars($row['poster_path']); ?>" width="100"></td>
            <td><?php echo htmlspecialchars($row['genre']); ?></td>
            <td>
    <a class="button button2" href="view.php?id=<?php echo $row['id_film']; ?>">View</a>
    <a class="button button1" href="edit.php?id=<?php echo $row['id_film']; ?>">Edit</a>
    <a class="button button3" href="delete.php?id=<?php echo $row['id_film']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
</td>

        </tr>
        <?php } ?>
    </table>
        </main>
<footer>
    <p>&copy; 2024 Streamify. All rights reserved.</p>
</footer>
<script src="script.js"></script>
<script src="float.js"></script>
</body>
</html>