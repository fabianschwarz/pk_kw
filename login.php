<?php 
include("engine.php");
$login_failed=FALSE;
$login_form=FALSE;
$login_successful=FALSE;
$login_already=FALSE;
if (!cl()){
    if (isset($_POST['go'])){
        if ($_POST['pw'] == $admin_pw){
        $_SESSION['login'] = TRUE;
        $login_successful = TRUE;
        }else{
        $login_failed = TRUE;
        }
    }else{
$login_form=TRUE;
    }
}else{
$login_already=TRUE;
}
include("1.php"); 

if ($login_successful){
echo "Login erfolgreich!";
$login_successful = FALSE;
}      
        if (($login_failed) OR ($login_form)){
        ?>
        <h4>Login</h4>
            <?php 
            if ($login_failed){
            echo "Login fehlgeschlagen:<br />";
            $login_failed=FALSE;
            }
            ?>
        
        <form action=login.php method=POST >
        
        
       Passwort:<input type=password name=pw /><br />
        <input type=submit class=but        name=go value="Login" />
        </form> 
        
        <?php
        $login_form=FALSE;
        }
if ($login_already){
echo "Du bist bereits eingeloggt!";
$login_already = FALSE;
}
include("2.php"); 
?>