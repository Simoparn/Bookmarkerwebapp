
<div id="pagecontent">

<span class="paragraphtitle">Welcome to your user profile.</span>
<p>
<br>Username: <?php echo $_SESSION['username'] ?>
<br>User creation date: <?php echo $_SESSION['creation_date'] ?>
<br>Last modification date for the user: <?php echo $_SESSION['last_modified'] ?>
<br>First name: <?php echo $_SESSION['first_name'] ?>
<br>Surname: <?php echo $_SESSION['surname'] ?>
<br>Phone number: <?php echo $_SESSION['phone_number'] ?>
<br>Email address: <?php echo $_SESSION['email'] ?>
<br>Address: <?php echo $_SESSION['address'] ?>
<br>Postal code: <?php echo $_SESSION['postal_code'] ?>
<br>Municipality: <?php echo $_SESSION['municipality'] ?>
<br>Country: <?php echo $_SESSION['country'] ?>
<br>Province: <?php echo $_SESSION['province'] ?>
<br>State: <?php echo $_SESSION['state'] ?>
</p>

<?php
if($_SESSION['staff_status']==1){
    echo "<a href=\"./index.php?page=user_list\">See all users in the service</a>";
}
?>
</div>







