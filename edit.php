<?php
include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $release_date = $_POST['release_date'];
    $cast = $_POST['cast'];
    $director = $_POST['director'];
    
    if (!empty($_FILES['video']['name'])) {
        $video_path = 'uploads/film/' . basename($_FILES['video']['name']);
        move_uploaded_file($_FILES['video']['tmp_name'], $video_path);
    } else {
        $video_path = $_POST['current_video'];
    }

    if (!empty($_FILES['poster']['name'])) {
        $poster_path = 'uploads/poster/' . basename($_FILES['poster']['name']);
        move_uploaded_file($_FILES['poster']['tmp_name'], $poster_path);
    } else {
        $poster_path = $_POST['current_poster'];
    }

    $sql = "UPDATE film SET title='$title', description='$description', release_date='$release_date', cast='$cast', director='$director', video_path='$video_path', poster_path='$poster_path' WHERE id_film=$id";

    if ($conn->query($sql) === TRUE) {
        $conn->query("DELETE FROM moviegenre WHERE id_film = $id");
        foreach ($_POST['genre'] as $genre_id) {
            $conn->query("INSERT INTO moviegenre (id_film, genre_id) VALUES ('$id', '$genre_id')");
        }
        header("Location: read.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM film WHERE id_film = $id";
$result = $conn->query($sql);
$video = $result->fetch_assoc();

$genres_result = $conn->query("SELECT * FROM genre");
$video_genres_result = $conn->query("SELECT genre_id FROM moviegenre WHERE id_film = $id");
$video_genres = [];
while ($row = $video_genres_result->fetch_assoc()) {
    $video_genres[] = $row['genre_id'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Video</title>
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

.button2 {
  background-color: white; 
  color: black; 
  border: 2px solid #008CBA;
}

.button2:hover {
  background-color: #008CBA;
  color: white;
}

input[type=text], select, textarea {
  width: 500px;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}
</style>
</head>
<body>
    <h1>Edit Video</h1>
    <form action="edit.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($video['title']); ?>"><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description"><?php echo htmlspecialchars($video['description']); ?></textarea><br>

        <label for="release_date">Release Year:</label>
        <input type="date" id="release_date" name="release_date" value="<?php echo htmlspecialchars($video['release_date']); ?>"><br>

        <label for="cast">Cast:</label>
        <input type="text" id="cast" name="cast" value="<?php echo htmlspecialchars($video['cast']); ?>"><br>

        <label for="director">Director:</label>
        <input type="text" id="director" name="director" value="<?php echo htmlspecialchars($video['director']); ?>"><br>

        <label for="film">Video:</label>
        <input type="file" id="film" name="film"><br>
        <input type="hidden" name="current_video" value="<?php echo htmlspecialchars($video['video_path']); ?>">

        <label for="poster">Poster:</label>
        <input type="file" id="poster" name="poster"><br>
        <input type="hidden" name="current_poster" value="<?php echo htmlspecialchars($video['poster_path']); ?>">

        <label for="genre">Genres:</label>
        <select id="genre" name="genre[]" multiple>
            <?php while ($genre = $genres_result->fetch_assoc()) { ?>
                <option value="<?php echo $genre['genre_id']; ?>" <?php if (in_array($genre['genre_id'], $video_genres)) echo 'selected'; ?>>
                    <?php echo $genre['genre_nama']; ?>
                </option>
            <?php } ?>
        </select><br>

        <button class="button button1"><input type="submit" value="Update Video"></button>
    </form>
    <a href="read.php"><button class="button button2">Back to List</button></a>
</body>
</html>
