<?php
// Koneksi database
$host = "localhost";
$user = "root";
$pass = "";
$db = "dbstreamify"; // Ganti dengan nama database kamu
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$status_message = "";

// Handle submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $release_date = mysqli_real_escape_string($conn, $_POST['release_date']);

    $video_target_dir = "uploads/film/";
    $poster_target_dir = "uploads/poster/";

    if (!is_dir($video_target_dir)) mkdir($video_target_dir, 0777, true);
    if (!is_dir($poster_target_dir)) mkdir($poster_target_dir, 0777, true);

    $video_name = basename($_FILES["video"]["name"]);
    $poster_name = basename($_FILES["poster"]["name"]);

    $video_path = $video_target_dir . $video_name;
    $poster_path = $poster_target_dir . $poster_name;

    $videoFileType = strtolower(pathinfo($video_path, PATHINFO_EXTENSION));
    $posterFileType = strtolower(pathinfo($poster_path, PATHINFO_EXTENSION));

    $uploadOk = 1;

    // Validasi video
    if (strpos(mime_content_type($_FILES["video"]["tmp_name"]), "video") === false) {
        $status_message .= "File bukan video. ";
        $uploadOk = 0;
    }

    if ($_FILES["video"]["size"] > 50000000) {
        $status_message .= "Ukuran video terlalu besar. ";
        $uploadOk = 0;
    }

    $allowed_video = ["mp4", "avi", "mov", "wmv", "flv"];
    if (!in_array($videoFileType, $allowed_video)) {
        $status_message .= "Format video tidak didukung. ";
        $uploadOk = 0;
    }

    // Validasi poster
    if (getimagesize($_FILES["poster"]["tmp_name"]) === false) {
        $status_message .= "File bukan gambar. ";
        $uploadOk = 0;
    }

    if ($_FILES["poster"]["size"] > 5000000) {
        $status_message .= "Ukuran poster terlalu besar. ";
        $uploadOk = 0;
    }

    $allowed_image = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($posterFileType, $allowed_image)) {
        $status_message .= "Format gambar tidak didukung. ";
        $uploadOk = 0;
    }

    // Upload jika semua valid
    if ($uploadOk) {
        if (move_uploaded_file($_FILES["video"]["tmp_name"], $video_path) &&
            move_uploaded_file($_FILES["poster"]["tmp_name"], $poster_path)) {

            $sql = "INSERT INTO film (title, description, video_path, release_date, poster_path)
                    VALUES ('$title', '$description', '$video_path', '$release_date', '$poster_path')";

            if ($conn->query($sql) === TRUE) {
                $video_id = $conn->insert_id;

                if (!empty($_POST['genre'])) {
                    foreach ($_POST['genre'] as $genre_id) {
                        $sql_genre = "INSERT INTO moviegenre (id_film, genre_id) VALUES ($video_id, $genre_id)";
                        $conn->query($sql_genre);
                    }
                }

                $status_message = "Video berhasil diupload!";
            } else {
                $status_message = "Error saat insert DB: " . $conn->error;
            }

        } else {
            $status_message = "Gagal mengupload file.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Video</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], textarea, input[type="date"], select {
            width: 300px; padding: 5px;
        }
        input[type="submit"] {
            margin-top: 15px; padding: 10px 20px;
        }
    </style>
    <script>
        function showAlert(msg) {
            if (msg) alert(msg);
        }
    </script>
</head>
<body onload="showAlert('<?php echo $status_message; ?>')">

<h1>Upload Video</h1>
<form action="" method="post" enctype="multipart/form-data">
    <label>Title:</label>
    <input type="text" name="title" required>

    <label>Description:</label>
    <textarea name="description" rows="4"></textarea>

    <label>Release Date:</label>
    <input type="date" name="release_date" required>

    <label>Select video to upload:</label>
    <input type="file" name="video" accept="video/*" required>

    <label>Select poster image to upload:</label>
    <input type="file" name="poster" accept="image/*" required>

    <label>Select genres:</label>
    <select name="genre[]" multiple size="5">
        <?php
        // Ambil genre dari DB
        $genre_query = "SELECT genre_id, genre_nama FROM genre";
        $genre_result = $conn->query($genre_query);
        if ($genre_result->num_rows > 0) {
            while ($row = $genre_result->fetch_assoc()) {
                echo '<option value="'.$row['genre_id'].'">'.$row['genre_nama'].'</option>';
            }
        }
        ?>
    </select>

    <br>
    <input type="submit" value="Upload Video">
    <a href="read.php">Back to List</a>
</form>

</body>
</html>
