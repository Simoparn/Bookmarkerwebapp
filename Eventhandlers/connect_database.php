<?php






$servername = "localhost";
$username = $DOTENVDATA['DATABASEUSERNAME'];
$password = $DOTENVDATA['DATABASEPASSWORD'];
$db="puutarhaneilikka";

if(!isset($_SESSION)){
    session_start();
}

// Luo connect_database
$connection = new mysqli($servername, $username, $password, $db);
$tietokantakysely = new mysqli_stmt($connection);
// Tarkista connect_database
if ($connection->connect_error) {
    die("connect_database epäonnistui: " . $connection->connect_error);
} 

?>

<?php
/* ETÄVERSIO
ob_start();

$servername = "localhost";
$username = $DATABASEUSERNAME;
$password = $DATABASEPASSWORD;
$db="sakila";



// Luo connect_database
$connection = new db.connect($servername, $username, $password, $db);

} 

*/

?>