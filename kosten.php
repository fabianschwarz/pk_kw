<?php 
include("engine.php");
include("1.php"); 
if (cl()){
?>
<h4>Kosteneingabe</h4>
<h5>Neue Kosten hinzuf&uuml;gen</h5>
<?php 
if (isset($_POST['go4'])){
kosten_erstellen($_POST['beschreibung'],$_POST['preis'],$_POST['gruppe']);
cachen();
}
?>
<form action=kosten.php method=POST>
Kostenbeschreibung: <input type=text name=beschreibung /><br />
Preis/Kosten in <u>Euro</u>: <input type=text name=preis /><br />
<fieldset> Kosten f&uuml;r: <br />
<?php
gruppen_auswahl();
?>
<input type=submit class=but        name=go4 value='Kosten hinzuf&uuml;gen' />
</fieldset>
</form>
<h5>Bereits erfasste Kosten</h5>
<?php 
if (isset($_POST['go5'])){
kosten_loeschen($_POST['id']);    
cachen();
}     
kosten_anzeigen("kosten.php");       
}else{
echo "Bitte melde dich an!";
}
include("2.php"); 
?>
