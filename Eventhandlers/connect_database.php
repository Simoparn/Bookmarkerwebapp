<?php






$servername = "localhost";
$db_username = $DOTENVDATA['DATABASEUSERNAME'];
$db_password = $DOTENVDATA['DATABASEPASSWORD'];
$db="bookmarkerwebapp";

if(!isset($_SESSION)){
    session_start();
}

// Create database connection
$connection = new mysqli($servername, $db_username, $db_password, $db);
$database_query = new mysqli_stmt($connection);
// Check database connection
if ($connection->connect_error) {
    die("connecting to database failed: " . $connection->connect_error);
} 

//echo "Database character set: ".$connection->character_set_name();
?>

<?php
/* REMOTE VERSION
ob_start();

$servername = "localhost";
$username = $DATABASEUSERNAME;
$password = $DATABASEPASSWORD;
$db="bookmarkerwebapp";




$connection = new db.connect($servername, $username, $password, $db);

} 

*/

?>