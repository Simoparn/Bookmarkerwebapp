<div id="pagecontent">
    <span class="paragraphtitle">Edit your user profile information</span>
    <form method="post" action="./Eventhandlers/User_management/handle_edit_user_information.php"> 
        <br><b>First name (at least 2 letters)</b><input type="text" name="first_name" id="first_name" value="<?php echo $_SESSION['first_name']; ?>" pattern="[a-öA-Ö]{2,}$"required></input><br>
        <br><b>Surname (at least 2 letters)</b><input type="text" name="surname" id="surname" value="<?php echo $_SESSION['surname']; ?>" pattern="[a-öA-Ö]{2,}$"required></input><br>
        <br><b>Phone number (only) numbers, atleast 8)</b><input type="text" name="phone_number" id="phone_number" value="<?php echo $_SESSION['phone_number']; ?>" pattern="[0-9]{8,}$"required><br>
        <br><b>Email (must be of the form name@address.com)</b><input type="text" name="email" id="email" value="<?php echo $_SESSION['email']; ?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"required><br>
        <br><b>Address (at least 8 letters, space, 1-3 numbers, and an optional space and a letter)</b><input type="text" name="address" id="address" value="<?php echo $_SESSION['address']; ?>" pattern="[a-öA-Ö]\w{8,}.[0-9]{1,3}.[a-öA-Ö\ ]\w{0,1}$"required><br>
        <br><b>Postal code (at least 5 numbers)</b><input type="text" name="postal_code" id="postal_code" value="<?php echo $_SESSION['postal_code']; ?>" pattern="[0-9]{5,}$"required><br>
        <br><b>Municipality (Capitalized initial letter, at least 4 letters total, spaces allowed)</b><input type="text" name="municipality" id="municipality" value="<?php echo $_SESSION['municipality']; ?>"  pattern="[A-Z]{1,1}.[a-zA-Z\ ]{3,}$"required><br>
        <select name="country" id="country" 
            <?php  
                //TODO: Automatic selection doesn't work this way, need a way to edit selected attribute after retrieving the countries.
                echo "selected="; 
                        $user_old_country_query=$connection->prepare("SELECT country FROM address WHERE address_id=(SELECT address_id FROM userprofile WHERE username=?)");
                        $user_old_country_query->bind_param("s", $_SESSION["username"]);
                        if($user_old_country_query->execute()){
                            $user_old_country_query->bind_result($old_country);
                            while($user_old_country_query->fetch()){
                                echo $old_country;
                            }
                            echo "<script>
                                document.addEventListener('DOMContentLoaded', function(){
                                    document.querySelector(\"#country\").onload=function()
                                    {
                                        document.querySelector(\"#country\").setAttribute(\"selected=\"".$old_country.")
                                    }
                                
                                }
                            </script>";
                        }
                        
                        
                        
                    
        ?>>
    <?php
            $country_query="SELECT country_name FROM country";
            if($query_result = $connection->query($country_query)){
                while(list($name)=$query_result->fetch_row()){
                    echo "<br><option value=\"$name\">$name</option>";
            
                }

            }
            else{
                header('Location: ./index.php?page=registration_form.php?countries_not_retrieved_error=true');
            }

    ?>
        </select>
        <br><b>Province (capitalized initial letter and atleast four letters in total, spaces allowed)</b><input type="text" name="province" id="province" value="<?php echo $_SESSION['province']; ?>" pattern="[A-Z]{1,1}.[a-zA-Z\ ]{3,}$"required><br>
        <br><b>State (can be empty)</b><input type="text" name="state" id="state" value="<?php echo $_SESSION['state']; ?>"><br>
       <input type="submit" name="edit_user_information" id="edit_user_information" value="Edit user information" onClick="buttonpressedtext=window.document.getElementById('buttonpressedtext'); buttonpressedtext.innerHTML='Please wait, handling user information modification, or please fix the errors';">
    </form>   

</div>




