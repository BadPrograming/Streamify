<?php
include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT f.id_film AS id_film, f.title, f.description, f.release_date, f.cast, f.director, f.video_path, f.poster_path, GROUP_CONCAT(g.genre_nama SEPARATOR ', ') AS genre
        FROM film f
        LEFT JOIN moviegenre mg ON f.id_film = mg.id_film
        LEFT JOIN genre g ON mg.genre_id = g.genre_id
        WHERE f.id_film = $id
        GROUP BY f.id_film";

$result = $conn->query($sql);

if ($result === FALSE) {
    echo "Error: " . $conn->error;
    exit;
}

if ($result->num_rows == 0) {
    echo "<p>No videos found.</p>";
    exit;
}

$video = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Video List</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .button {
  border: none;
  color: white;
  padding: 5px 15px;
  align-items: start;
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

table {
    width: 90%;
    margin: 0 auto;
    border-collapse: collapse;
    color: white;
}

th {
    width: 20%;
    text-align: left;
    padding: 10px;
    vertical-align: top;
}

td {
    width: 80%;
    padding: 10px;
    vertical-align: top;
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
    <main>
    <h1>View Video</h1>
    <br>
    <table border="1">
        <tr>
            <th>Title</th>
            <td><?php echo htmlspecialchars($video['title']); ?></td>
        </tr>
        <tr>
            <th>Description</th>
            <td><?php echo htmlspecialchars($video['description']); ?></td>
        </tr>
        <tr>
            <th>Release Date</th>
            <td><?php echo htmlspecialchars($video['release_date']); ?></td>
        </tr>
        <tr>
            <th>Cast</th>
            <td><?php echo htmlspecialchars($video['cast']); ?></td>
        </tr>
        <tr>
            <th>Director</th>
            <td><?php echo htmlspecialchars($video['director']); ?></td>
        </tr>
        <tr>
            <th>Video</th>
            <td><video width="320" height="240" controls>
                <source src="<?php echo htmlspecialchars($video['video_path']); ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video></td>
        </tr>
        <tr>
            <th>Poster</th>
            <td><img src="<?php echo htmlspecialchars($video['poster_path']); ?>" width="100"></td>
        </tr>
        <tr>
            <th>Genres</th>
            <td><?php echo htmlspecialchars($video['genre']); ?></td>
        </tr>
    </table>
    <br>
    <a href="read.php"><button class="button button1">Back to Video List</button></a>
</main>
</body>
</html>