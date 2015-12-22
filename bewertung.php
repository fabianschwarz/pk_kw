<?php 
include("engine.php");
include("1.php"); 
?>
<h4>Bewertungen abgeben</h4>
<?php
if (($b_offen)OR(cl())){
if (!$b_offen){
echo "Achtung! Die Bewertung l&auml;uft eigentlich nicht!<br/ >";
}
if (isset($_GET['code'])){//Code eingeben?
if(code_pruefen($_GET['code'])){//Code korrekt?
    $request = "SELECT * FROM codes WHERE code = ".strip_tags($_GET['code'])."";
    $result = mysql_query($request);
    if (!$result){
            die("MySQL-Error: " . mysql_error());
        }                           
    while($row = mysql_fetch_object($result)){ 
    $inhaber = $row->inhaber;
    $gruppen_id = $row->gruppen_id;
    }
    
      
    $request2 = "SELECT * FROM gruppen WHERE id <> '".strip_tags($gruppen_id)."'";
    $result2 = mysql_query($request2);
        if (!$result2){
            die("MySQL-Error: " . mysql_error());
        }                     
    $anzahl = mysql_num_rows($result2);    
    echo "Hallo ".$inhaber."!<br /><p>Bitte bewerte jetzt die verschiedenen Gruppen (deine Gruppe kannst du nicht bewerten), indem du die Pl&auml;tze 1 bis ".$anzahl." festlegst. Die Kategorie der Kosten wird automatisch ermittelt. Sp&auml;ter werden alle Bewertungen verrechnet und auf der Ergebnissseite angezeigt.</p>";
    echo "<hr />
    <form action=bewertung.php method=POST>
    <p><b>Vorspeise</b></p>
    <p>Bitte gib die Platzierungen f&uuml;r die <u>Vorspeise</u> ein.</p>
    ";

    while($row2 = mysql_fetch_object($result2)){ 
        echo "<input type=number name=vor[] min=1 max=".$anzahl." step=1 style='width:2em;' />. Platz f&uuml;r ".$row2->name."<br />";
    }
    
    
    echo "<p><b>Hauptspeise</b></p>
    <p>Bitte gib die Platzierungen f&uuml;r die <u>Hauptspeise</u> ein (<u>wird doppelt gez&auml;hlt</u>).</p>";
mysql_data_seek($result2,0);
    while($row3 = mysql_fetch_object($result2)){ 
        echo "<input type=number name=haupt[] min=1 max=".$anzahl." step=1 style='width:2em;' />. Platz f&uuml;r ".$row3->name."<br />";
    }
    
    
        echo "<p><b>Nachspeise</b></p>
    <p>Bitte gib die Platzierungen f&uuml;r die <u>Nachspeise</u> ein.</p>";
mysql_data_seek($result2,0);
    while($row3 = mysql_fetch_object($result2)){ 
        echo "<input type=number name=nach[] min=1 max=".$anzahl." step=1 style='width:2em;' />. Platz f&uuml;r ".$row3->name."<br />";
    }
    
    
    
    
    
        echo "<p><b>Anmerkungen</b></p>
    <p>Hier kannst du (musst aber nicht) Anmerkungen zu den Gruppen eingeben. Deine Kommentare bleiben <u>anonym</u>!</p>";
    $request3 = "SELECT * FROM gruppen WHERE id <> '".$gruppen_id."'";
    $result3 = mysql_query($request3);
        if (!$result3){
            die("MySQL-Error: " . mysql_error());
        }  
    while($row3 = mysql_fetch_object($result3)){ 
        echo "Kommentar zu ".$row3->name.":<input type=text size=100 style='max-width:95%;' name=anmerkungen[]  /><br />";
    }
    echo "
    <br />
    <input type=hidden value=".$_GET['code']." name=code_id />
    <input type=hidden value='".$gruppen_id."' name=gruppen_id />
    <input type=hidden value=".$anzahl." name=anzahl />
    <input type=submit class=but   value='Bewertung &#x00A;VERBINDLICH &#x00A;abschicken &#x00A;(nicht widerrufbar!)' name=go8 /></form>
    ";
    }
}else if(isset($_POST['go8'])){
$vor = $_POST['vor'];
$haupt = $_POST['haupt'];
$nach = $_POST['nach'];
$anmerkungen = $_POST['anmerkungen'];
//EIngaben des Users in Arrays umwandeln

$request = "SELECT * FROM gruppen WHERE id <> '".strip_tags($POST['gruppen_id'])."'";//Anzahl der Gruppen ermitteln (abgezogen die eigene)
    $result = mysql_query($request);
        if (!$result){
            die("MySQL-Error[3]: " . mysql_error());
        }            
    $anzahl2 = (mysql_num_rows($result));



//Summe der vergeben Pl&auml;tze ermitteln
$svor = 0;
for($i=0;$i<$anzahl2;$i++){
    $svor += $vor[$i];
}

$shaupt = 0;
for($i=0;$i<$anzahl2;$i++){
    $shaupt += $haupt[$i];
}

$snach = 0;
for($i=0;$i<$anzahl2;$i++){
    $snach += $nach[$i];
}

//Fakultaet ermitteln, um die Eingaben zu pr&uuml;fen
$fakul = 0;
for($i=1;$i<($anzahl2+1);$i++){
    $fakul += $i;
}

//pruefen ob die oben ermittelten Werte gleich sind
$fehlerhaft = FALSE;
if(($svor!=$fakul)OR($shaupt!=$fakul)OR($snach!=$fakul)){
$fehlerhaft = TRUE;
}
    //pruefen ob kein Platz doppelt vergeben wurde
    if((!(array_unique($vor)==$vor))OR(!(array_unique($haupt)==$haupt))OR(!(array_unique($nach)==$nach))OR($fehlerhaft)){
    echo "<b>Fehler:</b> <p>Bitte achte darauf, dass du <u>jeden Platz</u> vergibst und jeden Platz <u>nur einmal pro Kategorie</u> vergibst.</p>";
    echo "<br /><a href='bewertung.php?code=".$_POST['code_id']."'>Nochmal versuchen</a>";
    }else{
      
    //pruefen, ob Code schon verwendet wurde
    $request = "SELECT * FROM codes WHERE code = ".strip_tags($_POST['code_id'])." AND benutzt = 0";
    $result = mysql_query($request);
        if (!$result){
            die("MySQL-Error: " . mysql_error());
        }                     
     while($row = mysql_fetch_object($result)){ 
     //Gruppenzugehoerigkeit speichern
     $gruppen_id = $row->gruppen_id;
     }    
     
     
    if(mysql_num_rows($result)!=0){//Wenn Code frei, dann Punkte ermitteln
    $request = "SELECT * FROM gruppen WHERE id <> '".strip_tags($gruppen_id)."'";
    $result = mysql_query($request);
        if (!$result){
            die("MySQL-Error: " . mysql_error());
        }                     
        $fehler=FALSE;
        $count = 0;
     while($row = mysql_fetch_object($result)){ 
            $faktor=1;
            if($gruppen_id==(-1)){//falls keine Gruppenzugehoerigkeit Faktor fuer gerechte Bewertung verringern (z. B. von 3 auf 2)
            $faktor = ($anzahl2-2)/($anzahl2-1);
            }
            //Platzierungen werden in Punktzahlen umgewandelt
            $v = ((($vor[$count])-$anzahl2)*(-1)*$faktor);
            $h = ((($haupt[$count])-$anzahl2)*(-1)*$faktor);
            $n = ((($nach[$count])-$anzahl2)*(-1)*$faktor);
            
            //MySQL Zugriff
            $request2 = "INSERT INTO `bewertungen` (`id`, `gruppen_id`, `code_id`, `vorspeise`, `hauptspeise`, `nachspeise`, `kommentar`) VALUES (NULL, '".$row->id."', '".strip_tags($_POST['code_id'])."', '".$v."', '".$h."', '".$n."', '".strip_tags($anmerkungen[$count])."');";
            $result2 = mysql_query($request2);
            if (!$result2){
            die("MySQL-Error: " . mysql_error());
                $fehler = TRUE;       
            }
            $count++;
     //fuer jede Gruppe ein Datensatz       
     }    
            //code als benutzt markieren
            $request = "UPDATE `codes` SET `benutzt` = '1' WHERE `code` = ".strip_tags($_POST['code_id']).";";
            $result = mysql_query($request);
            if (!$result){
            die("MySQL-Error: " . mysql_error());
                      die("MySQL-Error: " . mysql_error());
            }
            
    //Fehler ausgeben falls vorhanden        
    if ($fehler){
    echo "Wenn du diese Meldung siehst, kontaktiere bitte den Administrator. Deine Bewertung wurde nicht oder nur teilweise gespeichert.";
    }else{
    
    cachen();//Die ver&auml;nderte Ergebnisseite wird gecached.

    echo "<h3>Vielen Dank!</h3><p>Deine Bewertung wurde erfasst. Bitte beachte, dass dein Code ab sofort nicht mehr zum Bewerten benutzt werden kann.</p>";}
    }else{
    echo "Fehler. Der Code wurde in der Zwischenzeit schon zur Abgabe einer Bewertung verwendet. Beachte, dass jeder Code nur <u>einmal</u> verwendet werden kann.";
    }
    
    
    }

}else{
codeeingabe();//code eingeben
}
}else{
echo "<p>Die Bewertung ist aktuell nicht m&ouml;glich. Der Administrator kann die Bewertung aktivieren.</p>";
}

include("2.php"); 
?>
