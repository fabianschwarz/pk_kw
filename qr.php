<?php 
include("engine.php");
if (isset($_GET['code'])){
    $request = "SELECT * FROM codes WHERE code = ".$_GET['code'];
    $result = mysql_query($request);
        if (!$result){
            die("MySQL-Error: " . mysql_error());
        }          
    if (mysql_num_rows($result)==0){ 
        echo "Code existiert nicht. <a href=index.php >Startseite</a>";
    }else{                
        while($row = mysql_fetch_object($result)){ 
            $inhaber = $row->inhaber;
            $code = $_GET['code'];
            $gruppen_id = $row->gruppen_id;
        }
        //------------------------------------------------------------------------------------------------------------

 function short($url){
    $res = "";
    $handle = @fopen("http://tinyurl.com/api-create.php?url=".urlencode($url), "rb");
    if($handle){
      while (!feof($handle)) {
        $res .= fgets($handle,2000);
      }
      fclose($handle);
    }
    else{
      return "Aufgrund eines Fehlers steht diese M&ouml;glichkeit nicht zur Verf&uuml;gung.";
    }
    return $res;
  }

//__________________________________________________________________________________________________________
    
?>
<style>
tr,th{
border: 1px solid black;
}
#rechts{
float:right;
width: 30%;
}
#links{
float:left;
width: 65%;
}
</style>
<h4>Deine Bewertung</h4>
<p>Hallo 
<?php echo $inhaber;  ?>
!
<br />
<p>Dein Bewertungscode ist <b><u>
<?php echo $code;  ?>
</u></b>.</p>
<p>Folgende M&ouml;glichkeiten stehen dir zur Verf&uuml;gung:</p><div><div id=links>
<ul>
<li style='list-style-position: inside;'>Scanne diesen QR-Code mit deinem Smartphone. Der Bewertungsprozess wurde so gestaltet, dass er auch mobil benutzt werden kann.<br />

<?php 
 $url2 = urlencode("http://".$siteurl."/bewertung.php?code=".$code."");
 echo 
 
 "<img style='max-width: 95%;' src='http://chart.apis.google.com/chart?chs=500x500&cht=qr&chld=L&chl=".$url2."' />";
 ?>


</li>

<li style='list-style-position: inside;'>
<u>
<?php
echo str_replace ( "http://" , "" ,short("http://".$siteurl."/bewertung.php?code=".$code."")); 
?>
</u>
</li><br />

<li style='list-style-position: inside;'>
<u><?php 
 
 echo $siteurl;
 ?>/bewertung.php</u> + dein Code <?php echo $code;  ?></li><br />


</ul><br/><b>Danke f&uuml;r deine Bewertung!</b></div>
<div id=rechts>
<p>Falls du die Bewertung nicht digital ausf&uuml;llen kannst, f&uuml;lle bitte diese Tabelle aus und gib sie ab.</p>
<table style='border:1px solid black;'>
<tr><th>Kategorie</th><th>Platz</th><th>Gruppe</th></tr>
<?php
$request = "SELECT * FROM gruppen WHERE id <> ".$gruppen_id."";
    $result = mysql_query($request);
        if (!$result){
            die("MySQL-Error: " . mysql_error());
        }             
        
    $durchlauf = 0;                
    while($row=mysql_fetch_object($result)){
        if ($durchlauf==0){
        echo "<tr><th>Vorspeise</th><td style='background-color:#D6D6D6;height:2em;'></td><td>".$row->name."</td></tr>";
        }else{
        echo "<tr><td></td><td style='background-color:#D6D6D6;height:2em;'></td><td>".$row->name."</td></tr>";
        }
        $durchlauf++;
    }
    mysql_data_seek($result,0);
    
        $durchlauf = 0;                
    while($row=mysql_fetch_object($result)){
        if ($durchlauf==0){
        echo "<tr><th>Hauptspeise</th><td style='background-color:#D6D6D6;height:2em;'></td><td>".$row->name."</td></tr>";
        }else{
        echo "<tr><td></td><td style='background-color:#D6D6D6;height:2em;'></td><td>".$row->name."</td></tr>";
        }
        $durchlauf++;
    }
    mysql_data_seek($result,0);
    
        $durchlauf = 0;                
    while($row=mysql_fetch_object($result)){
        if ($durchlauf==0){
        echo "<tr><th>Nachspeise</th><td style='background-color:#D6D6D6;height:2em;'></td><td> ".$row->name."</td></tr>";
        }else{
        echo "<tr><td></td><td style='background-color:#D6D6D6;height:2em;'></td><td>".$row->name."</td></tr>";
        }
        $durchlauf++;
    }
    mysql_data_seek($result,0);
    
?>
</table>
<p>Die Abgabe von Kommentaren in hierbei leider nicht m&ouml;glich.</p>
</div></div>

<?php 
}
}else{
echo "Kein Code angegeben. <a href=index.php >Startseite</a>";
}
?>
<?php
/*Copyright Fabian Schwarz. Diese Software wurde entwickelt von Fabian Schwarz. Es ist erlaubt, die Software unter angabe des Urhebers privat zu nutzen. Diese Notiz darf nicht entfernt werden. F&uuml;r private Nutzung derf der Code ver&auml;ndert und/oder ver&ouml;ffentlicht werden. Vielen Dank f&uuml;r die Benutzung dieser Software. Bei Fragen und Problemen: ip138@t-online.de*/
?>