<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM pcs");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>PC Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>PC Inventory</h2>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <a href="add_pc.php" class="btn btn-success mb-3">Add New PC</a>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Image</th> <!-- Replaced "Specifications" with "Image" -->
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row["brand"] ?></td>
                    <td><?= $row["model"] ?></td>
                    <td>
                        <img src="uploads/<?= $row["image"] ?>" alt="PC Image" width="100" height="100">
                    </td> <!-- Display uploaded image -->
                    <td>₱<?= number_format($row["price"], 2) ?></td>
                    <td>
                        <a href="edit_pc.php?id=<?= $row["id"] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_pc.php?id=<?= $row["id"] ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
