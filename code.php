<?php 
include("engine.php");
include("1.php"); 
if (cl()){
?>
<h4>Codes anzeigen und hinzuf&uuml;gen</h4>
<?php
if (isset($_POST['go'])){
code_erstellen($_POST['gruppe'],$_POST['inhaber']);
cachen();
}
?>
<h5>Code hinzuf&uuml;gen</h5>
<form action=code.php method=POST>
<fieldset> Inhaber: <input type=text name=inhaber /><br />Mitglied in <br />
<?php
gruppen_auswahl();
        echo "<br /><input type='radio' id='-1' name='gruppe' value='-1'><label for='-1'>Unabh&auml;ngiger Schiedsrichter</label><br> ";
?>
<input type=submit class=but        name=go value='Code erstellen' />
</fieldset>
</form>
<h5>Bestehende Codes</h5>
<?php
if (isset($_POST['go3'])){
code_loeschen($_POST['id']);         
cachen();             
}        
codes_anzeigen_loeschen("code.php");
}else{
echo "Du bist nicht berechtigt, diese Seite zu sehen!";
}
include("2.php"); 
?>
