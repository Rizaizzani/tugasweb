<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="number"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .book {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .book img {
            max-width: 100px;
            height: auto;
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>Form Upload Buku</h2>
    <form action="?action=upload" method="post" enctype="multipart/form-data">
        <label for="judul">Judul Buku:</label>
        <input type="text" id="judul" name="judul" required>
        
        <label for="pengarang">Pengarang:</label>
        <input type="text" id="pengarang" name="pengarang" required>
        
        <label for="tahun_terbit">Tahun Terbit:</label>
        <input type="number" id="tahun_terbit" name="tahun_terbit" required>
        
        <label for="gambar">Gambar Sampul:</label>
        <input type="file" id="gambar" name="gambar" required>
        
        <input type="submit" value="Upload">
    </form>

    <h2>Form Pencarian Buku</h2>
    <form action="?action=search" method="get">
        <label for="judul">Judul Buku:</label>
        <input type="text" id="judul" name="judul" required>
        
        <input type="submit" value="Cari">
    </form>

    <?php
    include 'koneksi.php';

    if (isset($_GET['action']) && $_GET['action'] == 'search' && isset($_GET['judul'])) {
        $judul = $_GET['judul'];
        $sql = "SELECT * FROM buku WHERE judul LIKE '%$judul%'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2>Hasil Pencarian</h2>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='book'>";
                echo "Judul: " . $row["judul"] . "<br>";
                echo "Pengarang: " . $row["pengarang"] . "<br>";
                echo "Tahun Terbit: " . $row["tahun_terbit"] . "<br>";
                echo "<img src='uploads/" . $row["gambar"] . "' alt='Gambar Sampul'><br>";
                echo "</div>";
            }
        } else {
            echo "0 results";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']) && $_GET['action'] == 'upload') {
        $judul = $_POST['judul'];
        $pengarang = $_POST['pengarang'];
        $tahun_terbit = $_POST['tahun_terbit'];
        $gambar = $_FILES['gambar']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($gambar);

        // Memindahkan file gambar ke folder uploads
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            $sql = "INSERT INTO buku (judul, pengarang, tahun_terbit, gambar) VALUES ('$judul', '$pengarang', '$tahun_terbit', '$gambar')";
            
            if ($conn->query($sql) === TRUE) {
                echo "Buku berhasil diupload.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Gagal mengupload gambar.";
        }
    }

    $conn->close();
    ?>
</body>
</html>
