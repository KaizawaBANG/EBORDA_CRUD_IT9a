<?php
session_start();
include "db.php";

$id = $_GET["id"];
$result = $conn->query("SELECT * FROM pcs WHERE id = $id");
$pc = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand = $_POST["brand"];
    $model = $_POST["model"];
    $price = $_POST["price"];
    $image_name = $pc["image"]; // Keep the existing image by default

    // Check if a new image is uploaded
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $image_name = uniqid() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;

        // Move the uploaded file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Delete old image if a new one is uploaded
            if (!empty($pc["image"]) && file_exists("uploads/" . $pc["image"])) {
                unlink("uploads/" . $pc["image"]);
            }
        }
    }

    $stmt = $conn->prepare("UPDATE pcs SET brand=?, model=?, image=?, price=? WHERE id=?");
    $stmt->bind_param("sssdi", $brand, $model, $image_name, $price, $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit PC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg p-4 mx-auto" style="max-width: 500px;">
            <h3 class="text-center">Edit PC</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Brand</label>
                    <input type="text" name="brand" class="form-control" value="<?= $pc["brand"] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control" value="<?= $pc["model"] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Current Image</label><br>
                    <img src="uploads/<?= $pc["image"] ?>" alt="PC Image" width="150" class="mb-2"><br>
                    <input type="file" name="image" class="form-control">
                    <small class="text-muted">Leave empty to keep the current image.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price (PHP)</label>
                    <input type="number" name="price" class="form-control" value="<?= $pc["price"] ?>" step="0.01" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Update PC</button>
                <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
