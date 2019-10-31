<?php
//$right = new rightClass();

$protokollfuehrer = 99993; //Schwindl
$alterGrenze = 15;
$_anzahlPartner = 7;
$_anzahlPartner_asociated = 8;
function __autoload($classname){
    include_once "class/".$classname.".php";
}



function getDateArray($date){
    $datum[] = substr($date, -2);
    $datum[]= substr($date,-5,2);
    $datum[] = substr($date,0,4);

    return $datum;


}

function compareDateToCurrentTime($date,$time){

  $h = substr($time,0,2);
  $h ="23";

  $m = substr($time,3,2);
  $m = "59";

   $date = getDateArray($date);
   $termin = mktime($h,$m,0,$date[1],$date[0],$date[2]);
   $now = time();

   if($termin < $now){ //Termin war bereits
   return 1;

   }else{
   return -1;
   }


}


function transDate($date){

    if($date ==""){
      return "";
    }

    $day = substr($date, -2);
    $month = substr($date,-5,2);
    $year = substr($date,0,4);

    return $day.".".$month.".".$year;
}

function getAlter($date){
    $tag = intval(substr($date, -2));
    $mon = intval(substr($date,-5,2));
    $jah = intval(substr($date,0,4));

   $jetzt = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
   $gebur = mktime(0, 0, 0, $mon, $tag, $jah);
   return $age   = intval(($jetzt - $gebur) / (3600 * 24 * 365));


}


function transDateToDB($date){
    $day = substr($date,0,2);
    $month = substr($date,3,2);
    $year = substr($date,-4);

    return $year."-".$month."-".$day;
}

?>
