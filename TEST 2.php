<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stored XSS Example</title>
</head>
<body>
    <h1>Comment Section</h1>
    <form action="" method="post">
        <label for="comment">Your Comment:</label><br>
        <textarea id="comment" name="comment" rows="4" cols="50"></textarea><br>
        <input type="submit" value="Submit">
    </form>
    
    <h2>Comments</h2>
    <?php
    // Koneksi ke database
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Cek koneksi
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['comment'])) {
        $comment = $_POST['comment'];
        
        // Menyimpan komentar ke database dengan prepared statement untuk mencegah SQL Injection
        $stmt = $conn->prepare("INSERT INTO comments (comment) VALUES (?)");
        $stmt->bind_param("s", $comment);
        if ($stmt->execute()) {
            echo "New comment added successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    // Menampilkan komentar dari database
    $sql = "SELECT comment FROM comments";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Menampilkan komentar dengan sanitasi untuk mencegah XSS
            echo "<p>" . htmlspecialchars($row['comment'], ENT_QUOTES, 'UTF-8') . "</p>";
        }
    } else {
        echo "No comments yet.";
    }

    // Menutup koneksi
    $conn->close();
    ?>
</body>
</html>


    // Menutup koneksi
    $conn->close();
    ?>
</body>
</html>
