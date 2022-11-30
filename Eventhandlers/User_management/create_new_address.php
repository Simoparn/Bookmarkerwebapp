<?php
//If state is empty, separate query
if($_POST["state"]=="" || $_POST["state"]==NULL){
    $create_new_address_query=$connection->prepare("INSERT INTO address (address, postalcode, municipality, country, province, state) VALUES (?,?,?,?,?,?)");
    $create_new_address_query->bind_param("ssssss",$_POST["address"], $_POST["postal_code"],$_POST["municipality"],$_POST["country"],$_POST["province"],$_POST["state"]);
}
else{
    $create_new_address_query=$connection->prepare("INSERT INTO address (address, postalcode, municipality, country, province, state) VALUES (?,?,?,?,?,NULL");
    $create_new_address_query->bind_param("sssss",$_POST["address"], $_POST["postal_code"],$_POST["municipality"],$_POST["country"],$_POST["province"]);
}

?>