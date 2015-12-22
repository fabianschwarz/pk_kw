<?php 
include("engine.php");
include("1.php"); 
if (cl()){//pruefen ob Admin
?>
<h4>Gruppen bearbeiten</h4>
<?php
if (isset($_POST['go20'])){
$request7 = "SELECT * FROM gruppen WHERE id = ".$_POST['id']."";
$result7 = mysql_query($request7);
          while($row7 = mysql_fetch_object($result7)){ 
              $name = $row7->name;
              $mitglieder = $row7->mitglieder;
              $ausgleich = $row7->ausgleich;
              $ak = $row7->ak;
          }
echo "
<p>Bitte bearbeiten und speichern.</p>
<form method=POST action=gruppen.php >
Name: <input type=text value='".$name."' name=name /><br />
Mitglieder: <input type=text value='".$mitglieder."' name=mitglieder /><br />
Ausgleichspunkte: <input type=number value='".$ausgleich."' name=ausgleich /><br />
Begr&uuml;ndung (&ouml;ffentlich): <input type=text value='".$ak."' name=ak /><br />
<input type=hidden name=id value=".$_POST['id']." />
<input type=submit value=Speichern name=go21 />
</form>
";//Bearbeitungsformular

}else{
//Gruppen bearbeiten
if (isset($_POST['go21'])){
                    $request = "UPDATE `gruppen` SET `name` = '".strip_tags($_POST['name'])."', `mitglieder` = '".strip_tags($_POST['mitglieder'])."', `ausgleich` = '".strip_tags($_POST['ausgleich'])."', `ak` = '".strip_tags($_POST['ak'])."' WHERE `id` = ".strip_tags($_POST['id']).";";
            $result = mysql_query($request);
            if (!$result){
            die("MySQL-Error: " . mysql_error());
                      
            }else{
                echo "Die Gruppe wurde gespeichert.";
                cachen();
            }
           
}        
//Gr. loeschen und Ergebnisse aktualisieren
if (isset($_POST['go7'])){
gruppe_loeschen($_POST['id']);          
cachen();             
}        
?>
<h5>Neue Gruppe erstellen</h5>
<?php
if (isset($_POST['go6'])){
gruppe_erstellen($_POST['name'],$_POST['mitglieder']);
cachen();
}
?>
<form action=gruppen.php method=POST>
Gruppenname: <input type=text name=name /><br />
Gericht, Mitglieder etc.: <input type=text name=mitglieder /><br />
<input type=submit class=but  name=go6 value='Gruppe erstellen' />
</form>
<h5>Bestehende Gruppen</h5>
<?php
gruppen_anzeigen("gruppen.php");
}
}else{
echo "Du bist nicht berechtigt, diese Seite zu sehen!";
}
include("2.php");
?>
