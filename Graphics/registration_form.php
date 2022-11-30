<div id="pagecontent">
  <span class="paragraphtitle">Register a new user</span>
   <p>
      <form method="post" action="./Eventhandlers/User_management/handle_register_user.php">
      <br><b>First name (at least 2 letters)</b><input type="text" name="first_name" id="first_name" pattern="[a-öA-Ö]{2,}$"required><br>
      <br><b>Surname (at least 2 letters)</b><input type="text" name="surname" id="surname" pattern="[a-öA-Ö]{2,}$"required><br>
      <br><b>Phone number (only) numbers, atleast 8)</b><input type="text" name="phone_number" id="phone_number" pattern="[0-9]{8,}$"required><br>
      <br><b>Email (must be of the form name@address.com)</b><input type="text" name="email" id="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"required><br>
      <br><b>Address (at least 8 letters, space and 1-3 numbers at the end)</b><input type="text" name="address" id="address" pattern="[a-öA-Ö]\w{8,}.[0-9]{1,3}$"required><br>
      <br><b>Postal code (at least 5 numbers)</b><input type="text" name="postal_code" id="postal_code" pattern="[0-9]{5,}$"required><br>
      <br><b>Municipality (Capitalized initial letter, at least 4 letters total, spaces allowed)</b><input type="text" name="municipality" id="municipality" pattern="[A-Z]{1,1}.[a-zA-Z\ ]{3,}$"required><br>
      <select name="country">
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
      <br><b>Province (capitalized initial letter and atleast four letters in total, spaces allowed)</b><input type="text" name="province" id="province" pattern="[A-Z]{1,1}.[a-zA-Z\ ]{3,}$"required><br>
      <br><b>State (can be empty)</b><input type="text" name="state" id="state"><br>
      <br><b>Username (at least 8 characters)</b> <input type="text" name="username" id="username" pattern="[a-öA-Ö0-9-]{8,}$"required>  <br>   
      <br><b>Password (at least one number and 8 characters in total)</b> <input type="password" name="password" id="password" pattern="(?=.*[0-9])(?=.*[a-zA-Z]).{8,}"required>
      <br><b>Confirm password</b> <input type="password" name="confirm_password" id="confirm_password" pattern="(?=.*[0-9])(?=.*[a-zA-Z]).{8,}"required>
      <input type="submit" name="register_user" id="register_user" value="Create user" onClick="buttonpressedtext=window.document.getElementById('buttonpressedtext'); buttonpressedtext.innerHTML='Please wait, handling user registration, or please fix the errors';">
      <span id="buttonpressedtext"></span>
      </form>
  </p>


</div>

