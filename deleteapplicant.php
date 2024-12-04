<?php
require_once './core/dbConfig.php';
require_once './core/models.php';

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $result = deleteApplicant($pdo, $id);

    // Provide feedback via an alert and redirect back to the index
    if ($result['status']) {
        echo "<script>alert('{$result['message']}'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Error: {$result['message']}'); window.location.href = 'index.php';</script>";
    }
} else {
    // Redirect to index if accessed without 'delete' parameter
    header("Location: index.php");
    exit;
}
?>
