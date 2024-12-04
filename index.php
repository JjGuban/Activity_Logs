<?php
require_once './core/dbConfig.php';
require_once './core/models.php';
require_once './core/handleForms.php';


$search = $_GET['search'] ?? null;
$applicants = getAllApplicants($pdo, $search)['querySet'];

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application System</title>
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 90%;
            margin: auto;
            padding: 20px;
        }
        .actions {
            margin-bottom: 20px;
        }
        .searchForm {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        button {
            padding: 10px 15px;
            margin: 5px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Job Application System</h1>
        <?php include 'navbar.php'; ?>

        <?php if (isset($_SESSION['message']) && isset($_SESSION['status'])): ?>
            <div class="message <?= $_SESSION['status'] == "200" ? 'success' : 'error' ?>">
                <?= htmlspecialchars($_SESSION['message']) ?>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['status']); ?>
        <?php endif; ?>

        <div class="actions">
            <form method="GET" action="" class="searchForm" style="display: inline-block;">
                <input type="text" name="search" placeholder="Search applicants" value="<?= htmlspecialchars($search ?? '') ?>">
                <button type="submit">Search</button>
            </form>
            <?php if ($search): ?>
                <a href="index.php"><button type="button">Back to Main</button></a>
            <?php endif; ?>
            <a href="addApplicant.php"><button type="button">Add Applicant</button></a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Job Title</th>
                    <th>Skills</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($applicants)): ?>
                    <?php foreach ($applicants as $applicant): ?>
                    <tr>
                        <td><?= htmlspecialchars($applicant['first_name']) ?></td>
                        <td><?= htmlspecialchars($applicant['last_name']) ?></td>
                        <td><?= htmlspecialchars($applicant['email']) ?></td>
                        <td><?= htmlspecialchars($applicant['phone']) ?></td>
                        <td><?= htmlspecialchars($applicant['address']) ?></td>
                        <td><?= htmlspecialchars($applicant['job_title']) ?></td>
                        <td><?= htmlspecialchars($applicant['skills']) ?></td>
                        <td><?= htmlspecialchars($applicant['status']) ?></td>
                        <td>
                            <a href="editApplicant.php?id=<?= $applicant['id'] ?>">Edit</a> | 
                            <a href="deleteapplicant.php?delete=<?= $applicant['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">No applicants found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
