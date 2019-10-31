<?php
  include("function.php");

  $db = new DBInterface();

$vorname = "Erwin";
$name = "Testuser";
$strasse = "Robert-Bosch-Str.";
$hausnr = "141";
$plz = "86554";
$ort = "Testheim";
$land = "Deutschland";

$db->saveKunde($vorname,$name,$strasse,$hausnr,$plz,$ort,$land);



  print_r($db);

  $zahl1 = $_POST['zahl1'];
  $zahl2 = $_POST['zahl2'];
  $operand = $_POST['operand'];


switch( $operand ) {
  case "+": $ergebnis = $zahl1 + $zahl2; break;
  case "-": $ergebnis = $zahl1 - $zahl2; break;
  case "*": $ergebnis = $zahl1 * $zahl2; break;
  case "/": $ergebnis = $zahl1 / $zahl2; break;
}

echo "Das Ergbenis ist " . $ergebnis;




 ?>
<p><a href="eingabe.html"><button class="button">zurück</button></a></p>
