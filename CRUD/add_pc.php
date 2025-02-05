<?php
session_start();
include "db.php";


if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand = $_POST["brand"];
    $model = $_POST["model"];
    $price = $_POST["price"];

   
    if (!empty($_FILES["pc_picture"]["name"])) {
        $upload_dir = "uploads/"; 
        $file_name = uniqid() . "_" . basename($_FILES["pc_picture"]["name"]);  
        $target_file = $upload_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png", "gif"];

        
        if (!in_array($file_type, $allowed_types)) {
            $error = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        } elseif (move_uploaded_file($_FILES["pc_picture"]["tmp_name"], $target_file)) {
           
            $stmt = $conn->prepare("INSERT INTO pcs (brand, model, price, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $brand, $model, $price, $file_name);

            if ($stmt->execute()) {
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Error adding PC.";
            }
        } else {
            $error = "Failed to upload image.";
        }
    } else {
        $error = "Please select an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add PC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg p-4 mx-auto" style="max-width: 500px;">
            <h3 class="text-center">Add a New PC</h3>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Brand</label>
                    <input type="text" name="brand" class="form-control" placeholder="Enter PC brand" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control" placeholder="Enter PC model" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">PC Picture</label>
                    <input type="file" name="pc_picture" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price (PHP)</label>
                    <input type="number" name="price" class="form-control" placeholder="Enter price" step="0.01" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Add PC</button>
                <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
