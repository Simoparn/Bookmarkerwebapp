<div id="pagecontent">

<span class="paragraphtitle">User list</span>
<?php
if(isset($_SESSION["username"])){
    $all_users_query=$connection->prepare("SELECT username, first_name, surname, phone_number, email, userprofile.address_id, active, is_staff, creation_date, last_modified, address, postalcode, municipality, country, province, state FROM userprofile INNER JOIN address ON userprofile.address_id = address.address_id");
    try{    
        if($all_users_query->execute()){
            //$all_users_query->store_result();
            $all_users_query->bind_result($username, $first_name, $surname, $phone_number, $email, $address_id, $active, $is_staff, $creation_date, $last_modified, $address, $postalcode, $municipality, $country, $province, $state);
                echo "<table>";
                while($all_users_query->fetch()){
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
                    echo "</tr>";
                }
                echo "</table>";

            
        //$all_users_query->free_result();
            
        }
    }catch(Exception $e){
        header('Location: ./index.php?page=frontpage&database_error=yes');
    }
}
?>


</div>



