<?php
echo "<br /><br />";
if (cl()){//Unterschiedliche Menus fuer Admin / User
?>
<a href="bewertungsuebersicht.php" class=menu >Abgegebene&nbsp;Bewertungen</a>  <a href="gruppen.php" class=menu >Gruppen&nbsp;verwalten</a>  <a href="kosten.php" class=menu >Kosten&nbsp;eingeben</a>  <a href="code.php" class=menu >Codes&nbsp;verwalten</a>  <a href='logout.php' class=menu >Logout</a><br />
<b>
<?php
//Buttons, um die Bewertung zu starten/stoppen
if($b_offen){
echo "Bewertung l&auml;uft. 
<form action=".$_SERVER['PHP_SELF']." method=POST >
<input type=submit class=but name=stop value='Stoppen' />
</form>
";
}else{
echo "Bewertung nicht m&ouml;glich. 
<form action=".$_SERVER['PHP_SELF']." method=POST >
<input type=submit class=but name=start value='Starten' />
</form>
";
cachen();//Ergebnisseite chachen
}
?>
</b>
<?php
}else{
echo"<a href='login.php'  class=menu >Login</a><br />"; 
}
//Skriptlaufzeit ausgeben
echo "<p style='color:black; font-size:0.8em;'>";
echo "Skriptlaufzeit: ".round((microtime(true)-$starttime),6)." Sekunden. ";
echo "</p>";
?>
</div></div>
</body>
</html>
