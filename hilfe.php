<?php 
include("engine.php");
include("1.php"); 
?>
<h4>Hilfe & Kontakt</h4>
<p>
<u>Warum kann ich keine Bewertung abgeben?</u><br />Es gibt 2 m&ouml;gliche Probleme:<li>Die Bewertung wurde noch nicht freigegeben oder bereits beendet. Du kannst den Administrator kontaktieren, dieser kann die Bewertung freigeben und stoppen.</li><li>Dein Code wurde bereits verwendet. Falls nicht durch dich, kontaktiere den Administrator.</li><br /><br />

<u>Kann ich meine Bewertung &auml;ndern?</u><br />Prinzipiell ist die Bewertung verbindlich und eine &Auml;nderung nicht m&ouml;glich. Nat&uuml;rlich kann die Bewertung im Fall von Missbrauch etc. durch den Administrator zur&uuml;ckgesetzt werden.<br /><br />

<u>Was sind die Kommentare?</u><br />Du kannst zu jeder Gruppe einen Kommentar abgeben. Dieser z&auml;hlt nicht zur Bewertung, ist aber bei der Auswertung sichtbar und zeigt die St&auml;rken und Schw&auml;chen der entschprechenden Gruppe.<br /><br />

<u>Warum werden die Ergbnisse nicht angezeigt?</u><br />Die Ergebnisse werden erst angezeigt, wenn der Administrator die Bewertung beendet hat. Somit ist sichergestellt, dass nur die wirklich endg&uuml;ltigen Ergebnisse angezeigt werden.<br /><br />

<u>Wie kommen die Ergebnisse zustande?</u><br /><u>Kurz:</u> Punkte von 0 bis 2; Vor- und Nachspeise sowie Kosten z&auml;hlen einfach, die Hauptspeise doppelt, also max. 10 Punkt insgesamt.<br /><br /><u>Lang:</u> Die vergebenen Pl&auml;tze werden in Punkte umgerechnet. Bei vier Gruppen (deine eigene Gruppe kannst du nicht bewerten) werden 3 Pl&auml;tze vergeben. Der 3. Platz erh&auml;lt 0 Punkte, der 2. Platz 1 Punkt und der erste Platz 2 Punkte. Bei den Schiedsrichtern, die alle Gruppen bewerten d&uuml;rfen, wird der Schl&uuml;ssel entschprechend angepasst (0/0,666/1,333/2 Punkte).<br />Die Kosten werden vom Administrator eingegeben und dann ebenfalls verteilt: g&uuml;nstigste Gruppe 2 Punkte, 2. g&uuml;nstigste Gruppe 1,333 Punkte, ..., teuerste Gruppe 0 Punkte. <br />Dann wird der Durchschnitt aller Bewertungen f&uuml;r Vor-, Haupt- und Nachspeise gebildet. Der Hauptspeise wird verdoppelt. Die Summe gibt dann die Anzahl der Punkte, welche den Sieger bestimmt.<br /><br />

</p><hr />
<?php

if(isset($_POST['go'])){
$empfaenger = "ip138@t-online.de";
$betreff = "Nachricht bzgl. Kochwettbewerb";
$from = "From: Kochwettbewerb<ip138@t-online.de>";


$text = "Nachricht:\n\n".$_POST['text']."\n\nKontakt: ".$_POST['kontakt']."\nZeit: ".date('d.m.Y H:i')."";
    if(mail($empfaenger, $betreff, $text, $from)){
    echo "Gesendet.<br />";
    }else{
    echo "Fehler.<br />";
    }
}

echo "E-mail ";
echo "<a href='mailto:ip138@t-online.de'>ip138@t-online.de</a><br />";
echo "<hr /><br />
<form action=hilfe.php method=POST >
Nachricht an den Administrator:<br />
<textarea name=text>
</textarea><br />
Kontaktm&ouml;glichkeit: <input type=text name=kontakt /><br />";
echo "<input type=submit value=Absenden name=go />
</form>
";

?>

<hr />

<?php include("2.php"); 
?>
