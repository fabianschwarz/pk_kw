<?php
$starttime = microtime(true);//fuer Skriptlaufzeit
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UFT-8">
<title><?php if ($title){echo $tt;}echo $site_title;/* Seitentitel*/ ?></title>
<meta name="viewport" content="width=device-width" />
<link rel="stylesheet" href="style.css">
<link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
</head>
<body><div id=main><div id=innen>
<?php echo $site_head; /* Ueberschrift */?>
<a href="index.php" class=menu >Start</a>  <a href="bewertung.php" class=menu >Bewerten</a>  <a href="ergebnisse.php" class=menu >Ergebnisse</a>  <a href='hilfe.php' class=menu >Hilfe&nbsp;&&nbsp;Kontakt</a>
<?php
if (cl()){
    if (!$dbok){//Datenbankverbindung pruefen
    echo "<b>Es gibt ein Problem mit der Datenbankverbindung!</b>";
    }
}
?>
<br/>
