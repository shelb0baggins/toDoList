<?php
$dbconnecterror = FALSE;
$dbh = NULL;

require_once 'credentials.php';

try{
	$conn_string = "mysql:host=".$dbserver.";dbname=".$db;
	$dbh= new PDO($conn_string, $dbusername, $dbpassword);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(Exception $e){
	http_response_code(504);
	exit();
}

//update task
if ($_SERVER['REQUEST_METHOD'] == "PUT") {
	if(array_key_exists('listID', $_GET)){
		$listID = $_GET['listID'];	
	} else {
		http_response_code(404);
		exit();

	}
//decode json body from the reques
$task = json_decode(file_get_contents('php://input'), true);

if (array_key_exists('complete', $task)) {
		$complete = $task["complete"];
	} else {
		http_response_code(400);
		echo "missing complete";
		exit();

	}
if (array_key_exists('listItem', $task)) {
		$listItem = $task["listItem"];
	} else {
		http_response_code(400);
		echo "missing listItem";
		exit();

	}
if (array_key_exists('finishDate', $task)) {
		$finBy = $task["finishDate"];
	} else {
		http_response_code(400);
		echo "missing date";
		exit();

	}

if (!$dbconnecterror) {
	try {
		$sql = "UPDATE doList SET complete=:complete, listItem=:listItem, finishDate=:finishDate WHERE listID=:listID";
		$stmt = $dbh->prepare($sql);			
		$stmt->bindParam(":complete", $complete);
		$stmt->bindParam(":listItem", $listItem);
		$stmt->bindParam(":finishDate", $finBy);
		$stmt->bindParam(":listID", $listID);

		$response = $stmt->execute();	
		http_response_code(204);


	
	} catch (PDOException $e) {
		http_response_code(501);
		echo "db shit";
		exit();

	}
} else {
	http_response_code(501);
	echo "db shit 2";
	exit();
	

}

} else if($_SERVER['REQUEST_METHOD'] == "POST") {
$task = json_decode(file_get_contents('php://input'), true);
	if (array_key_exists('complete', $task)) {
		$complete = $task["complete"];
	} else {
		http_response_code(400);
		echo "missing complete";
		exit();

	}
	if (array_key_exists('listItem', $task)) {
		$listItem = $task["listItem"];
	} else {
		http_response_code(400);
		echo "missing listItem";
		exit();

	}
	if (array_key_exists('finishDate', $task)) {
		$finBy = $task["finishDate"];
	} else {
		http_response_code(400);
		echo "missing date";
		exit();

	}

	if (!$dbconnecterror) {
        try {
            $sql = "INSERT INTO doList (complete, listItem, finishDate)
		VALUES(:complete, :listItem, :finishDate)";
            $stmt = $dbh->prepare($sql);			
            $stmt->bindParam(":complete", $complete);
            $stmt->bindParam(":listItem", $listItem);
            $stmt->bindParam(":finishDate", $finBy);    
            $response = $stmt->execute();	    
    		$listID = $dbh->lastInsertId();
		http_response_code(201);
	echo json_encode(['listID' => $listID]);
	exit();
        
        } catch (PDOException $e) {
           http_response_code(501);
           echo "db shit";
           exit();
    
        }
    }

} else if($_SERVER['REQUEST_METHOD'] == "DELETE") {
	if (array_key_exists('listID', $_GET)) {
		$listID = $_GET["listID"];
	} else {
		http_response_code(400);
		echo "missing ID";
		exit();

	}

	if (!$dbconnecterror) {
        try {
            $sql = "DELETE FROM doList WHERE listID=:listID";
            $stmt = $dbh->prepare($sql);			
		$stmt->bindParam(":listID", $listID);
            $stmt->execute();
			http_response_code(201);
			echo "deleted";
			exit();
        
        } catch (PDOException $e) {
           http_response_code(501);
           echo "db shit";
           exit();
    
        }
    }
	

} else {
	http_response_code(501);
	echo "db shit 2";
	exit();
}





?>
