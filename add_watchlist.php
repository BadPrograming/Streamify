<?php
session_start();
include('db.php');

if($_SESSION['id'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$user_id = $_SESSION['id'];
$movie_id = $_POST['id_film']; // Movie ID dari form atau parameter POST

$sql = "INSERT INTO watchlist (id, id_film) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ii", $user_id, $movie_id);
    $stmt->execute();
    $stmt->close();

    // Set session message
    $_SESSION['message'] = "Film berhasil ditambahkan ke watchlist!";
} else {
    $_SESSION['message'] = "Error: " . $conn->error;
}

$conn->close();

header("Location: mylist.php");
exit();
?>
