<?php 
include("engine.php");
include("1.php"); 
if (cl()){
?>
<h4>Abgegebene Bewertungen</h4>
<?php
if((isset($_POST['go10']))OR(isset($_POST['go11']))){
$request = "DELETE FROM bewertungen WHERE code_id = ".strip_tags($_POST['id']).";";
    $result = mysql_query($request);
        if (!$result){
            die("MySQL-Error: " . mysql_error());
        }else{   
          cachen();
          }
    if(isset($_POST['go11'])){
         $request = "UPDATE `codes` SET `benutzt` = '0' WHERE `code` = ".strip_tags($_POST['id']).";";
            $result = mysql_query($request);
            if (!$result){
            die("MySQL-Error: " . mysql_error());
                      
            }else{
                echo "Der Code <b>".$_POST['id']."</b> wurde zur&uuml;ckgesetzt und kann erneut eingesetzt werden.";
                cachen();
            }
    }else{
                 $request = "DELETE FROM codes WHERE `code` = ".strip_tags($_POST['id']).";";
            $result = mysql_query($request);
            if (!$result){
            die("MySQL-Error: " . mysql_error());
                      
            }else{
                echo "Gel&ouml;scht.";
                cachen();
            }
    }
}
    if(isset($_POST['go12'])){
         $request = "UPDATE `bewertungen` SET `kommentar` = '' WHERE `id` = ".strip_tags($_POST['id']).";";
            $result = mysql_query($request);
            if (!$result){
            die("MySQL-Error 1: " . mysql_error());
                      die("MySQL-Error: " . mysql_error());
            }else{
                echo "Der Kommentar wurde entfernt.";
                cachen();
            }
    }

?>
<p>L&ouml;schen -> alle Bewertungen dieses Codes werden gel&ouml;scht, Code kann nicht nocheinmal verwendet werden</p>
<p>Zur&uuml;cksetzetn -> alle Bewertungen dieses Codes werden gel&ouml;scht, der Code wird wieder zur&uuml;ckgesetzt, d. h. er kann nocheinmal verwendet werden</p>
<p>Kommentar l&ouml;schen -> der Kommentar wird gel&ouml;scht, die Bewertung bleibt erhalten</p>
<?php 
    $request = "SELECT * FROM bewertungen";
    $result = mysql_query($request);
        if (!$result){
            die("MySQL-Error: " . mysql_error());
        }                           
    echo "<table align='center'><tr><th>ID</th><th>Gruppe</th><th>Code</th><th>Inhaber</th> <th>Vor<span style='font-size:0px;'> </span>speise</th><th>Haupt<span style='font-size:0px;'> </span>speise</th><th>Nach<span style='font-size:0px;'> </span>speise</th><th>Kom<span style='font-size:0px;'> </span>men<span style='font-size:0px;'> </span>tar</th></tr>";
    while($row = mysql_fetch_object($result)){ 
          $request2 = "SELECT * FROM gruppen WHERE id = ".$row->gruppen_id."";
          $result2 = mysql_query($request2);                         
          while($row2 = mysql_fetch_object($result2)){ 
              $gruppenname = $row2->name;
          }
              
          $request3 = "SELECT * FROM codes WHERE code = ".$row->code_id."";
          $result3 = mysql_query($request3);                         
          while($row3 = mysql_fetch_object($result3)){ 
              $inhaber = $row3->inhaber;
          }
    
    
    
        echo "<tr><td>".$row->id."</td><td>".$gruppenname."</td><td>".$row->code_id."</td><td>".$inhaber."</td><td>".$row->vorspeise."</td><td>".$row->hauptspeise."</td><td>".$row->nachspeise."</td><td>".$row->kommentar."</td><td>
<form action=bewertungsuebersicht.php method=POST>
<input type=hidden value=".$row->code_id." name=id />
<input type=submit class=but  style='background-color:#BDBDBD;'   value='L&ouml;schen' name=go10 id=but1".$row->id." disabled />

</form>
<form action=bewertungsuebersicht.php method=POST>
<input type=hidden value=".$row->code_id." name=id />
<input type=submit class=but  style='background-color:#BDBDBD;'    value='Zur&uuml;cksetzen' name=go11 id=but2".$row->id." disabled />
</form>
<form action=bewertungsuebersicht.php method=POST>
<input type=hidden value=".$row->id." name=id />
<input type=submit class=but   style='background-color:#BDBDBD;'    value='Kmtr. l&ouml;sn.' name=go12 id=but3".$row->id." disabled />
</form>

<input type=submit class=but        value='Buttons freigeben' onClick=\"document.getElementById('but1".$row->id."').disabled = false;document.getElementById('but2".$row->id."').disabled = false;document.getElementById('but3".$row->id."').disabled = false;document.getElementById('but1".$row->id."').style.backgroundColor='white';document.getElementById('but2".$row->id."').style.backgroundColor='white';document.getElementById('but3".$row->id."').style.backgroundColor='white';

 \" />

</td>

</td></tr>";
    }
    echo "</table><br /><noscript>Bitte JavaScript aktivieren, um die Funktionen nutzen zu k&ouml;nnen.</noscript>";
   


}else{
echo "Bitte melde dich an!";
}
include("2.php"); ?>
