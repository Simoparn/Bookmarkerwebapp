
<div id="pagecontent">

<span class="paragraphtitle">Welcome to your user profile.</span>
<p>
<table>
<tr><td>Username</td><td><?php echo $_SESSION['username'] ?></td></tr>
<tr><td>User creation date</td><td><?php echo $_SESSION['creation_date'] ?></td></tr>
<tr><td>Last modification date for the user</td><td><?php echo $_SESSION['last_modified'] ?></td></tr>
<tr><td>First name</td><td> <?php echo $_SESSION['first_name'] ?></td></tr>
<tr><td>Surname</td><td> <?php echo $_SESSION['surname'] ?></td></tr>
<tr><td>Phone number</td><td><?php echo $_SESSION['phone_number'] ?></td></tr>
<tr><td>Email address</td><td><?php echo $_SESSION['email'] ?></td></tr>
<tr><td>Address</td><td><?php echo $_SESSION['address'] ?></td></tr>
<tr><td>Postal code</td><td><?php echo $_SESSION['postal_code'] ?></td></tr>
<tr><td>Municipality</td><td> <?php echo $_SESSION['municipality'] ?></td></tr>
<tr><td>Country</td><td><?php echo $_SESSION['country'] ?></td></tr>
<tr><td>Province</td><td> <?php echo $_SESSION['province'] ?></td></tr>
<tr><td>State</td><td> <?php echo $_SESSION['state'] ?></td></tr>
</table>
</p>
<a href="./index.php?page=edit_user_information_form.php">Edit your user profile information</a>

<?php
if($_SESSION['staff_status']==1){
    echo "<a href=\"./index.php?page=user_list\">See all users in the service</a>";
}
?>
</div>







