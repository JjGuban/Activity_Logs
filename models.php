<?php

function getAllApplicants($pdo, $search = null) {
    $sql = "SELECT * FROM applicants";
    if ($search) {
        $sql .= " WHERE CONCAT_WS(' ', first_name, last_name, email, phone, address, job_title, skills, status) LIKE ?";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($search ? ["%$search%"] : []);
    return [
        'message' => 'Applicants fetched successfully.',
        'statusCode' => 200,
        'querySet' => $stmt->fetchAll()
    ];
}



function updateApplicant($pdo, $first_name, $last_name, $email, $phone, $address, $job_title, $skills, $status, $added_by, $last_updated, $id) {
    $response = array();

    // Check for duplicate email
    $checkDuplicateEmailSQL = "SELECT id FROM applicants WHERE email = ? AND id != ?";
    $stmtCheckDuplicate = $pdo->prepare($checkDuplicateEmailSQL);
    $stmtCheckDuplicate->execute([$email, $id]);
    $duplicateApplicant = $stmtCheckDuplicate->fetch();

    if ($duplicateApplicant) {
        $response = array(
            "status" => "400",
            "message" => "The email address is already used by another applicant."
        );
        return $response;
    }

    // Proceed with the update
    $sql = "UPDATE applicants
            SET first_name = ?,
                last_name = ?,
                email = ?, 
                phone = ?, 
                address = ?,
                job_title = ?,
                skills = ?,
                status = ?,
                added_by = ?,
                last_updated = ?
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $updateApplicant = $stmt->execute([$first_name, $last_name, $email, $phone, $address, $job_title, $skills, $status, $added_by, $last_updated, $id]);

    if ($updateApplicant) {
        // Log the update operation in activity_logs
        $logSQL = "INSERT INTO activity_logs (operation, id, first_name, last_name, email, phone, address, username, date_added)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtLog = $pdo->prepare($logSQL);
        $stmtLog->execute([
            "Update",
            $id,
            $first_name,
            $last_name,
            $email,
            $phone,
            $address,
            $added_by,
            $last_updated
        ]);

        $response = array(
            "status" => "200",
            "message" => "Updated the applicant successfully and logged the activity!"
        );
    } else {
        $response = array(
            "status" => "400",
            "message" => "An error occurred during the update."
        );
    }

    return $response;
}




function deleteApplicant($pdo, $id) {
    try {
        // Retrieve applicant's details
        $stmt = $pdo->prepare("SELECT * FROM applicants WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $applicant = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$applicant) {
            return ['status' => false, 'message' => 'Applicant not found'];
        }

        // Insert into activity logs before deletion
        $logStmt = $pdo->prepare("INSERT INTO activity_logs 
            (operation, id, first_name, last_name, email, phone, address, job_title, skills, status, username, date_added) 
            VALUES 
            (:operation, :id, :first_name, :last_name, :email, :phone, :address, :job_title, :skills, :status, :username, NOW())");

        $logStmt->execute([
            'operation' => 'Delete',
            'id' => $applicant['id'],
            'first_name' => $applicant['first_name'],
            'last_name' => $applicant['last_name'],
            'email' => $applicant['email'],
            'phone' => $applicant['phone'],
            'address' => $applicant['address'],
            'job_title' => $applicant['job_title'],
            'skills' => $applicant['skills'],
            'status' => $applicant['status'],
            'username' => 'Admin', // Replace with session username if applicable
        ]);

        // Perform the deletion
        $deleteStmt = $pdo->prepare("DELETE FROM applicants WHERE id = :id");
        $deleteStmt->execute(['id' => $id]);

        return ['status' => true, 'message' => 'Applicant deleted successfully'];
    } catch (PDOException $e) {
        return ['status' => false, 'message' => 'Error deleting applicant: ' . $e->getMessage()];
    }
}

function checkIfUserExists($pdo, $username) {
	$response = array();
	$sql = "SELECT * FROM user_accounts WHERE username = ?";
	$stmt = $pdo->prepare($sql);

	if ($stmt->execute([$username])) {

		$userInfoArray = $stmt->fetch();

		if ($stmt->rowCount() > 0) {
			$response = array(
				"result"=> true,
				"status" => "200",
				"userInfoArray" => $userInfoArray
			);
		}

		else {
			$response = array(
				"result"=> false,
				"status" => "400",
				"message"=> "User doesn't exist from the database"
			);
		}
	}

	return $response;

}

function insertNewUser($pdo, $username, $first_name, $last_name, $password) {
	$response = array();
	$checkIfUserExists = checkIfUserExists($pdo, $username); 

	if (!$checkIfUserExists['result']) {

		$sql = "INSERT INTO user_accounts (username, first_name, last_name, password) 
		VALUES (?,?,?,?)";

		$stmt = $pdo->prepare($sql);

		if ($stmt->execute([$username, $first_name, $last_name, $password])) {
			$response = array(
				"status" => "200",
				"message" => "User successfully inserted!"
			);
		}

		else {
			$response = array(
				"status" => "400",
				"message" => "An error occured with the query!"
			);
		}
	}

	else {
		$response = array(
			"status" => "400",
			"message" => "User already exists!"
		);
	}

	return $response;
}

function getAllUsers($pdo) {
	$sql = "SELECT * FROM user_accounts";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}



function getAllApplicantsBySearch($pdo, $search_query) {
	$sql = "SELECT * FROM applicants WHERE 
			CONCAT(first_name,last_name,
            email,phone,address,job_title,skills,
            status,added_by,last_updated) 
			LIKE ?";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute(["%".$search_query."%"]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getApplicantsByID($pdo, $id) {
	$sql = "SELECT * FROM applicants WHERE id = ?";
	$stmt = $pdo->prepare($sql);
	if ($stmt->execute([$id])) {
		return $stmt->fetch();
	}
}

function insertAnActivityLog($pdo, $operation, $id, $first_name, $last_name, $email,
    $phone, $address, $job_title, $skills, $status, $username) {

	$sql = "INSERT INTO activity_logs (operation, id, first_name, last_name, email,
    phone, address, job_title, skills, status, username) VALUES(?,?,?,?,?,?,?,?,?,?,?)";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$operation, $id, $first_name, $last_name, $email,
    $phone, $address, $job_title, $skills, $status, $username]);

	if ($executeQuery) {
		return true;
	}

}

function getAllActivityLogs($pdo) {
	$sql = "SELECT * FROM activity_logs 
			ORDER BY date_added DESC";
	$stmt = $pdo->prepare($sql);
	if ($stmt->execute()) {
		return $stmt->fetchAll();
	}
}

function insertAnApplicant($pdo, $first_name, $last_name, $email, $phone, $address, $job_title, $skills, $added_by) {
    $response = array();
    try {
        // Insert applicant data
        $sql = "INSERT INTO applicants (first_name, last_name, email, phone, address, job_title, skills, added_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $insertApplicant = $stmt->execute([$first_name, $last_name, $email, $phone, $address, $job_title, $skills, $added_by]);

        if ($insertApplicant) {
            // Fetch the last inserted applicant's details
            $findInsertedItemSQL = "SELECT * FROM applicants ORDER BY id DESC LIMIT 1";
            $stmtFindInsertedItemSQL = $pdo->prepare($findInsertedItemSQL);
            $stmtFindInsertedItemSQL->execute();
            $getApplicant = $stmtFindInsertedItemSQL->fetch();

            if ($getApplicant) {
                // Insert activity log
                $insertAnActivityLog = insertAnActivityLog(
                    $pdo,
                    "INSERT",
                    $getApplicant['id'],
                    $getApplicant['first_name'],
                    $getApplicant['last_name'],
                    $getApplicant['email'],
                    $getApplicant['phone'],
                    $getApplicant['address'],
                    $getApplicant['job_title'],
                    $getApplicant['skills'],
                    "Active", // Assuming default status as 'Active'
                    $added_by
                );

                if ($insertAnActivityLog) {
                    $response = array(
                        "status" => "200",
                        "message" => "Applicant added successfully with activity log!"
                    );
                } else {
                    $response = array(
                        "status" => "400",
                        "message" => "Applicant added, but activity log insertion failed!"
                    );
                }
            } else {
                $response = array(
                    "status" => "400",
                    "message" => "Applicant added, but unable to fetch inserted record."
                );
            }
        } else {
            $response = array(
                "status" => "400",
                "message" => "Insertion of applicant data failed!"
            );
        }
    } catch (PDOException $e) {
        $response = array(
            "status" => "400",
            "message" => "Error: " . $e->getMessage()
        );
    }

    return $response;
}


?>