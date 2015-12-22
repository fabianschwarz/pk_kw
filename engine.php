<?php

/*
Enthaelt nur Funktionen, bei Aufruf wuerde eine leere Seite erscheinen. Sollte in jedem anderem Skript eingebunden sein.
*/

session_start();
ini_set("display_errors", "off");
ini_set("display_startip_errors", "off");
include("config.php");
      if (isset($_POST['stop'])){
      $datei = fopen('status.php',w);
      fwrite($datei, "<?php \$b_offen = FALSE; ?>");
      fclose($datei);
      cachen();
      }else if(isset($_POST['start'])){
      $datei = fopen('status.php',w);
      fwrite($datei, "<?php \$b_offen = TRUE; ?>");
      fclose($datei);
      }

include("status.php");

//MySQL Verbindung aufbauen    
$db = mysql_connect($mysql_server,$mysql_user,$mysql_pw);
    if (!$db){
        $dbok = FALSE;
    }else{
        $sdb = mysql_select_db($mysql_db);
        if (!$sdb){
            $dbok = FALSE;
        }else{
            $dbok = TRUE;
        }
    }
    

//Testabfrage   
if (mysql_query("SELECT * FROM gruppen")){    
$dbok = TRUE;    
}else{
$dbok = FALSE;
}



function cl(){
    /*
    Prueft, ob man als Admin angemeldet ist und gibt Boolean zurueck.
    */
    if (isset($_SESSION['login'])){ 
        if ($_SESSION['login']==TRUE){
            return TRUE;
        }else{
        return FALSE;
        }
    }else{
    return FALSE;
    }
}    


function codeeingabe(){
    /*
    Gibt Formular zur Codeeingabe aus.
    Keine Rueckgabe
    */
    echo "<form action=bewertung.php method=GET>
    Bewertungs-Code: <input type=number name=code />
    <input type=submit class=but    value=Bewerten  />
    </form>";
}

function code_pruefen($code){
    /*
    Prueft, ob der ubergebene Code schon benutzt wurde.
    Gibt Boolean zurueck.
    */
    $request = "SELECT * FROM codes WHERE code = ".strip_tags($code)."";
    $result = mysql_query($request);
    if(mysql_num_rows($result)==1){
        $request = "SELECT * FROM codes WHERE code = ".strip_tags($_GET['code'])."";
        $result = mysql_query($request);
        while($row = mysql_fetch_object($result)){ 
            if ($row->benutzt == 0){
                return TRUE;
            }else{
                echo "".$row->inhaber.", du hast deine Bewertung schon abgegeben.";
            }
        }
    }else{
        echo "Der eingegebene Code existiert leider nicht. Versuche es nochmal:<br />";
        codeeingabe();
    }
}


function gruppe_loeschen($id){
    /*
    Loescht Gruppe mit der uebergebenen ID.
    Kein Rueckgabewert.
    */
    $request = "DELETE FROM gruppen WHERE id=".strip_tags($id)."";
    $result = mysql_query($request);
        if (!$result){
            die("MySQL-Error: " . mysql_error());
        }else{
            echo "Gruppe gel&ouml;scht.";
        }    
}
function gruppen_anzeigen($url){
    /*
    Zeigt alle vorhandenen Gruppen als Tabelle an.
    Enthaelt Buttons zum bearbeiten (POST form)
    Als action ist $url eingetragen.
    */
    $request = "SELECT * FROM gruppen";
    $result = mysql_query($request);
        if (!$result){
            die("MySQL-Error: " . mysql_error());
        }                           
    echo "<table align='center'><tr><th>ID</th><th>Name</th><th>Mitglieder</th><th>Ausgleich</th></tr>";
    while($row = mysql_fetch_object($result)){ 
        if(($row->ausgleich)!=0){
        $text9 = " (".$row->ak.") ";
        }else{
        $text9 = "";
        }
        echo "<tr><td>".$row->id."</td><td>".$row->name."</td><td>".$row->mitglieder."</td><td>".$row->ausgleich."".$text9."</td>";
        
        $request5 = "SELECT * FROM bewertungen WHERE gruppen_id = ".$row->id."";
    $result5 = mysql_query($request5);
$request6 = "SELECT * FROM codes WHERE gruppen_id = ".$row->id."";
    $result6 = mysql_query($request6);
   $request7 = "SELECT * FROM kosten WHERE gruppen_id = ".$row->id."";
    $result7 = mysql_query($request7);
        
        if ((mysql_num_rows($result5)==0)AND(mysql_num_rows($result6)==0)AND(mysql_num_rows($result7)==0)){
      echo "  <td>
        <form action=".$url." method=POST>
        <input type=hidden name=id value=".$row->id." />
        <input type=submit class=but   style='background-color:#BDBDBD;'   name=go7 value=L&ouml;schen id='but3".$row->id."' disabled />
        </form>
        </td>
        
        <td><input type=submit class=but    value='Button freigeben' onClick=\"document.getElementById('but3".$row->id."').disabled = false;document.getElementById('but3".$row->id."').style.backgroundColor='white';\" /></td>";
        }else{
        echo "<td>Nicht l&ouml;schbar.</td>";
        }
        echo "
        <td>
        <form action=".$url." method=POST>
        <input type=hidden name=id value=".$row->id." />
        <input type=submit class=but   name=go20 value=Bearbeiten  />
        </form>
        </td>";
       echo " </tr>";
    }
    echo "</table><br /><noscript>Bitte JavaScript aktivieren, um die Funktionen nutzen zu k&ouml;nnen.</noscript>";
}

function gruppe_erstellen($name,$mitglieder){
    /*
    Erstellt Gruppe mit namen ($name) und mitgliedern ($mitglieder).
    Keine Rueckgabe.
    */
    if ($name == "" ){
        echo "Bitte Gruppennamen w&auml;hlen";
    }else{
        $request = "INSERT INTO `gruppen` (`id`, `name`, `mitglieder`) VALUES (NULL, '".strip_tags($name)."', '".strip_tags($mitglieder)."');";
        $result = mysql_query($request);
            if (!$result){
                die("MySQL-Error: " . mysql_error());
            }else{
                echo "Gruppe hinzugef&uuml;gt.";
            }         
    }
}

function code_loeschen($id){
    /*
    Loescht einen Code moit der ID $id.
    Keine Rueckgabe.
    */
    $request = "DELETE FROM codes WHERE id=".strip_tags($id)."";
    $result = mysql_query($request);
    if (!$result){
        die("MySQL-Error: " . mysql_error());
    }else{
        echo "Gel&ouml;scht.";
    }     
    
}

function code_erstellen($gruppe,$inhaber){
    /*
    Erstellt Code mit Gruppenzugehoerigkeit und Inhabernamen.
    Keine Rueckgabe.
    */
    if ($gruppe==""){
        echo "Bitte Gruppe w&auml;hlen.";
    }else{
        $stop = FALSE;
        while(!$stop){
            $code = mt_rand(1,9999);
            $request = "SELECT * FROM codes WHERE code = ".strip_tags($code)."";
            $result = mysql_query($request);
            if (!$result){
                $anzahl = 0;
            }else{
                $anzahl = mysql_num_rows($result); 
            }
            if ($anzahl == 0) {
                $stop = TRUE;                    
            }
        }
        $request = "INSERT INTO `codes` (`id`, `code`, `benutzt`, `inhaber`, `gruppen_id`) VALUES (NULL, '".strip_tags($code)."', '0', '".strip_tags($inhaber)."', '".strip_tags($gruppe)."');";
        $result = mysql_query($request);
        if (!$result){
            die("MySQL-Error: " . mysql_error());
        }else{
            echo "Code-Nummer: <b><u>".$code."</u></b>";
        }         
    }
}

function kosten_loeschen($id){
    /*
    Loescht einen Kostenpunkt.
    */
    $request = "DELETE FROM kosten WHERE id=".strip_tags($id)."";
    $result = mysql_query($request);
    if (!$result){
        die("MySQL-Error: " . mysql_error());
    }else{
        echo "Gel&ouml;scht.";
    }                       
}

function kosten_erstellen($beschreibung,$preis,$gruppe){
    /*
    Fuegt einen Kostenpunkt hinzu mit $beschreibung, $preis (mit punkt od. komma), $gruppe (Gruppenzugehoerigkeit)
    */
    if ($gruppe == ""){
        echo "Bitte Gruppe w&auml;hlen";
    }else{
        $preis =  str_replace ( "," , "." , $preis );
        $request = "INSERT INTO `kosten` (`id`, `name`, `preis`, `gruppen_id`) VALUES (NULL, '".strip_tags($beschreibung)."', '".strip_tags($preis)."', '".strip_tags($gruppe)."');";
        $result = mysql_query($request);
        if (!$result){
            die("MySQL-Error: " . mysql_error());
        }else{
            echo "Kosten hinzugef&uuml;gt.";
        }         
    }
}

function ergebnisse(){
/*
Gibt die Ergebnisse aus.
Liest die HTML-Datei aus (falls vorhanden), bzw. cached die Ergebnisse in die Datei.
*/
$sieger = array();
include('status.php');
if (cl()){
cachen();
}
    if((!$b_offen)OR(cl())){
      //-----------------------------------------------------------------------------------------------
      if ($b_offen){
      echo "Diese Seite ist noch unvollst&auml;ndig! Bewertung l&auml;uft.<br />";
      }
      
      
if(!file_exists("cache.html")) {
  cachen();
}
echo file_get_contents("cache.html");

    }else{
      echo "Die Bewertung l&auml;uft noch. Bitte noch etwas Geduld.";
    }
}



function cachen(){

      //------------------
      //cached die Ergebnisse
      //CACHE START
      ob_start();
      
      //-------------------
      //Datenbankabfragen
      $r1 = mysql_query("SELECT * FROM gruppen");
      $kosten = array();
      $anz = 0;
      $result= $result_gruppen;
      while($row = mysql_fetch_object($r1)){
          $request2 = "SELECT * FROM kosten WHERE gruppen_id = ".strip_tags($row->id)."";
          $result2 = mysql_query($request2);
          $summe = 0;
          while($row2 = mysql_fetch_object($result2)){
              $summe += $row2->preis;
          }
          $kosten[] = $summe;
          $anz++;
      }
      mysql_data_seek($r1,0);
      sort($kosten);  
      
      $ks = array();
      $kss = array();
      
      for($i=0;$i<$anz;$i++){

      while($row = mysql_fetch_object($r1)){
          $request2 = "SELECT * FROM kosten WHERE gruppen_id = ".strip_tags($row->id)."";
          $result2 = mysql_query($request2);
          $summe = 0;
          while($row2 = mysql_fetch_object($result2)){
              $summe += $row2->preis;
          }
          if($kosten[$i] == $summe){
               $ks["a".$row->id] = (($i-($anz-1))*(-1)*(($anz-2)/($anz-1)));
               $kss["a".$row->id] = $summe;
          }
      }
      mysql_data_seek($r1,0);
      
      }
      
      
      $request = "SELECT * FROM gruppen";
      $result = mysql_query($request);
      $anzahl = mysql_num_rows($result);
      $c2 = 0;
      echo "Die Hauptspeise wird doppelt gewertet; Vor- und Nachspeise, sowie die Kosten werden einfach gewertet.";
      while($row = mysql_fetch_object($result)){
                $vor = array();
                $haupt = array();
                $nach = array();
                $request2 = "SELECT * FROM bewertungen WHERE gruppen_id = ".strip_tags($row->id)."";
                $result2 = mysql_query($request2);
                $count = 0;
                while($row2 = mysql_fetch_object($result2)){
                    $vor[] = $row2->vorspeise;
                    $haupt[] = $row2->hauptspeise;
                    $nach[] = $row2->nachspeise;
                    $count++;
                }
                if(($row->ausgleich)>0){
                $vzeichen = "+";
                }else{
                $vzeichen = "";
                }
        if(($row->ausgleich)!=0){
        $text9 = " (".$row->ak.") ";
        }else{
        $text9 = "";
        }
                echo "<h4><u>".$row->name."</u></h4><p>".$row->mitglieder."</p><p>Strafpunkte oder Bonus: <b>".$vzeichen."".$row->ausgleich."</b> Punkt(e)".$text9."</p>
                <table align='center'>
                <tr><td></td><th>Vor<span style='font-size:0px;'> </span>speise</th><th>Haupt<span style='font-size:0px;'> </span>speise</th><th>Nach<span style='font-size:0px;'> </span>speise</th><th>Kosten: </th><td>&Sigma; ".$kss["a".$row->id]." &euro;</td></tr>
                
                
                ";
                $vs = 0;
                $hs = 0;
                $ns = 0;
                for($i=0; $i < $count; $i++)
                   {
                   echo "<tr><td></td><td>".$vor[$i]." Punkt(e) </td><td>".$haupt[$i]." Punkt(e) </td><td>".$nach[$i]." Punkt(e) </td></tr>";
                   $vs += $vor[$i];
                   $hs += $haupt[$i];
                   $ns += $nach[$i];
                   }
                   echo "<tr><th></th><th>----------</th><th>----------</th><th>----------</th></tr>";
                   $ps = round((($vs/$count)+($hs/$count)*2+($ns/$count)+$ks["a".$row->id]+$row->ausgleich),3);
                echo "<tr><td>&Oslash;</td><th> <b>".round(($vs/$count),3)."</b> Punkt(e) </th><th><b>".round(($hs/$count),3)."</b> Punkt(e) </th><th><b>".round(($ns/$count),3)."</b> Punkt(e) </th><th> ".round($ks["a".$row->id],3)." Punkt(e) </th><td style='border:1px solid black;'> GESAMT <b><u>".$ps."</u></b> Punkt(e) </td></tr>";
                echo "</table><br /><b>Eink&auml;ufe</b><br/>
                
                ";
                $sieger["a".$row->id] = $ps;
                // erledigt gez ip138
                $request2 = "SELECT * FROM kosten WHERE gruppen_id = ".strip_tags($row->id)."";
          $result2 = mysql_query($request2);
          echo "<table align='center' style='border: 0px;'>";
          while($row2 = mysql_fetch_object($result2)){
              echo "<tr><td>".$row2->name."</td><td>".$row2->preis." &euro;</td></tr>";
          }
                echo "</table><br />";
                
                $request2 = "SELECT * FROM bewertungen WHERE gruppen_id = ".strip_tags($row->id)."";
          $result2 = mysql_query($request2);
          echo "<b>Kommentare zu ".$row->name."</b><br/>";
          while($row2 = mysql_fetch_object($result2)){
              if($row2->kommentar!=""){
                  echo "<p><b>\"</b>".$row2->kommentar."<b>\"</b></p>";
              }
          }
                echo "<br /><hr />";
                $c2++;
      }
      $siegers = $sieger;
      sort($siegers);
      echo "<h3>Auswertung</h3><table align=center style='border:none;'>";
      
      for($i=0;$i<count($siegers);$i++){
          $request2 = "SELECT * FROM gruppen";
          $result2 = mysql_query($request2);
          while($row2 = mysql_fetch_object($result2)){
              if($siegers[$i]==$sieger["a".$row2->id]){
                  $platzname = $row2->name;
                  $platzmitglieder = $row2->mitglieder;
                  $gid = $row2->id;
              }
          }
          if ((count($siegers)-$i)==1){
          echo "</table><br /><div style='align:center;border:2px solid black;background-color:white;'><br /><b>Sieger ist </b><h1><u>".$platzname."</u></h1> <b>(".$platzmitglieder.") mit <u>".$sieger["a".$gid]."</u> Punte(n)</b><br /><h3>Herzlichen Gl&uuml;ckwunsch!</h3><hr />";
$request3 = "SELECT * FROM bewertungen WHERE gruppen_id = ".strip_tags($gid)."";
          $result3 = mysql_query($request3);
          while($row3 = mysql_fetch_object($result3)){
              if($row3->kommentar!=""){
                  echo "<p><b>\"</b>".$row3->kommentar."<b>\"</b></p>";
              }
          }


echo "</div>";
          }else{
          echo "<tr><th>".(count($siegers)-$i).". Platz </th><th> <u>".$platzname."</u> (".$platzmitglieder.")</th> <td>mit <u>".$sieger["a".$gid]."</u> Punte(n)</td></tr>";
          }
      }
      //-----------------------------------------------------------------------------------------------
      //CACHE ENDE
      $content = ob_get_clean();
      
unlink('cache.html');
      $fh = fopen("cache.html","w");
fputs($fh, $content);
fclose($fh);
      //------------------------

}



function kosten_anzeigen($url){
    /*
    Zeigt die Kostenpunkte nach Gruppe geordnet an.
    */
    $request = "SELECT * FROM gruppen ORDER BY name";
    $result = mysql_query($request);
    if (!$result){
        die("MySQL-Error: " . mysql_error());
    }                           
    while($row = mysql_fetch_object($result)){ 
        $request2 = "SELECT * FROM kosten WHERE gruppen_id = ".strip_tags($row->id)."";
        $result2 = mysql_query($request2);
        if (!$result2){
            die("MySQL-Error: " . mysql_error());
        }                           
        echo "<table align='center'><tr><th>".$row->name.":</th></tr><tr><th>Beschreibung</th><th>Preis</th></tr>";
        $summe = 0;
        while($row2 = mysql_fetch_object($result2)){ 
            echo "<tr><td>".$row2->name."</td><td>".($row2->preis)."&euro;</td><td>
            <form action=".$url." method=POST>
            <input type=hidden name=id value=".$row2->id." />
            <input type=submit class=but    name=go5 value=L&ouml;schen />
            </form>
            </td></tr>";
            $summe += $row2->preis;
        }
        echo "<tr><th></th><th>Summe: ".($summe)."&euro;</th></tr></table>";
    } 
}

function gruppen_auswahl(){
    /*
    Gibt ein Feld mit Radio-Buttons aus, um eine Gruppe auszuwaehlen.
    */
    $request = "SELECT * FROM gruppen";
    $result = mysql_query($request);
    if (!$result){
        die("MySQL-Error: " . mysql_error());
    }                           
    while($row = mysql_fetch_object($result)){ 
        echo "<input type='radio' id='".$row->id."' name='gruppe' value='".$row->id."'><label for='".$row->id."'>".$row->name." (".$row->mitglieder.")</label><br> ";
    }
}

function codes_anzeigen_loeschen($url){
    /*
    Gibt eine Tabelle der Codes aus und ermoeglicht eine Bearbeitung.
    */
    $request = "SELECT * FROM codes";
    $result = mysql_query($request);
    if (!$result){
        die("MySQL-Error: " . mysql_error());
    }                           
    echo "<table align='center'><tr><th>ID</th><th>Nummer</th><th>Be<span style='font-size:0px;'> </span>nutzt?</th><th>In<span style='font-size:0px;'> </span>ha<span style='font-size:0px;'> </span>ber</th><th>Grup<span style='font-size:0px;'> </span>pe</th></tr>";
    while($row = mysql_fetch_object($result)){ 
        echo "<tr><td>".$row->id."</td><td><a href='qr.php?code=".$row->code."'>".$row->code."</a></td><td>".$row->benutzt."</td><td>".$row->inhaber."</td><td>".$row->gruppen_id."</td><td>";
 $request2 = "SELECT * FROM bewertungen WHERE code_id = ".$row->code."";
    $result2 = mysql_query($request2);
    if ((mysql_num_rows($result2)==0)){
echo "
        <form action=".$url." method=POST>
        <input type=hidden name=id value=".$row->id." />
        <input type=submit class=but    name=go3 value=L&ouml;schen />
        </form>
        </td>";
        }else{
        echo "Nicht l&ouml;schbar.</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

?>
