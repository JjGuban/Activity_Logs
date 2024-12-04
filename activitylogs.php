<?php  
require_once 'core/models.php'; 
require_once 'core/handleForms.php'; 

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
	<title>Activity Logs</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
        <?php include 'navbar.php'; ?>

        <h1>Activity Logs</h1>
        <div class="tableClass">
            <table>
                <tr>
                    <th>Activity Log ID</th>
                    <th>Operation</th>
                    <th>Application ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Username</th>
                    <th>Date Added</th>
                </tr>
                <?php $getAllActivityLogs = getAllActivityLogs($pdo); ?>
                <?php foreach ($getAllActivityLogs as $row) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['activity_log_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['operation']); ?></td>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['date_added']); ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>
