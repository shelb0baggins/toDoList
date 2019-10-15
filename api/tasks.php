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
if($_SERVER['REQUEST_METHOD'] == "GET") {
if (!$dbconnecterror) {
        try {
            $sql = "SELECT * FROM doList";
            $stmt = $dbh->prepare($sql);			
            $stmt->execute();
			$result= $stmt->fetchAll(PDO::FETCH_ASSOC);
    		$listID = $dbh->lastInsertId();
			http_response_code(200);
	echo json_encode($result);
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
