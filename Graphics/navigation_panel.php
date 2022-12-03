

<span id="pagelogo"/>
<span id="companytitle"><a href="./index.php?page=frontpage">Bookmarker app</a></span>


<div id="navbar">
      <div id="main-nav">
          <ul>
            <li><a href="./index.php?page=frontpage" <?php if($currentpage == 'frontpage'){echo 'id="here"';}?>>Frontpage</a></li>
            <li><a href="./index.php?page=categories" <?php if($currentpage == 'categories'){echo 'id="here"';}?>>Categories</a>
              <ul class="submenu">
                <li><a href="./index.php?page=categorypage_1" <?php if($currentpage == 'categorypage_1'){echo 'id="here"';}?>>Categorypage 1</a></li>
                <li><a href="./index.php?page=categorypage_2" <?php if($currentpage == 'categorypage_2'){echo 'id="here"';}?>>Categorypage 2</a></li>
                <li><a href="./index.php?page=categorypage_3" <?php if($currentpage == 'categorypage_3'){echo 'id="here"';}?>>Categorypage 3</a></li>
                <li><a href="./index.php?page=categorypage_4" <?php if($currentpage == 'categorypage_4'){echo 'id="here"';}?>>Categorypage 4</a></li>
              </ul>	
            </li>
            <!--<li><a href="./index.php?page=about_us" <?php if($currentpage == 'about_us'){echo 'id="here"';}?>>About us</a></li>-->
            <li><a href="./index.php?page=contact" <?php if($currentpage == 'contact'){echo 'id="here"';}?>>Contact</a></li>
          </ul>
      </div>
      <div id="loginandsignupbox">
        <ul>

<?php          
          if(isset($_SESSION['username'])){
            
              echo "<li>User: ".$_SESSION['username']."</li>";
              if($currentpage=="user_profile"){
                echo "<li><a href=\"./index.php?page=user_profile\" id=\"here\">User profile</a></li>";
              }
              else{
                echo "<li><a href=\"./index.php?page=user_profile\">User profile</a></li>";
              }
            
          }
          else{
            echo "<li style=\"color:grey; list-style-type:none\">Not logged in</li>";
          }
?>
<?php
          if(!isset($_SESSION['username'])){
            if($currentpage=="login_form"){
              echo "<li><a href=\"./index.php?page=login_form\" id=\"here\">Log in</a></li>";
            }
            else{
              echo "<li><a href=\"./index.php?page=login_form\">Log in</a></li>";
            }
          }
          else{
            if($currentpage=="bookmarks_page"){
              echo "<li><a href=\"./index.php?page=bookmarks_page\" id=\"here\">My bookmarks</a></li>";
            }
            else{
              echo "<li><a href=\"./index.php?page=bookmarks_page\">My bookmarks</a></li>";
            }
            echo "<li><a href=\"Eventhandlers/User_management/handle_logout.php?logout=yes\">Log out</a></li>";
          }
          if($currentpage=="registration_form"){
             echo "<li><a href=\"index.php?page=registration_form\" id=\"here\">Sign up</a></li>";
          }
          else{
           echo "<li><a href=\"index.php?page=registration_form\">Sign up</a></li>";
          }
?>
<?php
if(!isset($_SESSION['username'])){
  if($currentpage=="forgotten_password_form"){
    echo "<li><a href=\"./index.php?page=forgotten_password_form\" id=\"here\"><b>Forgot your password?</b></a></li>";
  }
  else{
    echo "<li><a href=\"./index.php?page=forgotten_password_form\"><b>Forgot your password?</b></a></li>";
  }
}
?>
        </ul>
      </div>
</div>
        
  

    
