<div id="pagecontent">

<?php
if(isset($_SESSION["username"])){
    $all_users_query=$connection->prepare("SELECT username, first_name, surname, phone_number, email, address_id, active, is_staff, creation_date, last_modified, address, postalcode, municipality, country, province, state FROM username, address INNER JOIN address ON username.address_id = address.address_id");
    try{    
        if($all_users_query->execute()){
            $all_users_query->store_result();
            if($useraddress)
            if($query_result = $connection->query($all_users_query)){
                echo "<table>";
                while(list($username, $first_name, $surname, $phone_number, $email, $address_id, $active, $is_staff, $creation_date, $last_modified, $address, $postalcode, $municipality, $country, $province, $state)=$query_result->fetch_row()){
                    echo "<tr>";
                    echo "<td>$username</td>";
                    echo "<td>$creation_date</td>";
                    echo "<td>$last_modified</td>";
                    echo "<td>$active</td>";
                    echo "<td>$is_staff</td>";
                    echo "<td>$first_name</td>";
                    echo "<td>$surname</td>";
                    echo "<td>$phone_number</td>";
                    echo "<td>$email</td>";
                    echo "<td>$address</td>";
                    echo "<td>$postalcode</td>";
                    echo "</tr>"
                }
                echo "</table>";

            }
        $all_users_query->free_result();
            
        }
    }catch(Exception $e){
        header('Location: ./index.php?page=frontpage&database_error=yes');
    }
}
?>


</div>



