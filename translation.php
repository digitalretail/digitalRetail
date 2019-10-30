<?php
error_reporting(0);
$list = new ArrayObject();
$list->append('json/translation.json');


$autoList = new ArrayObject();
$autoList->append('Audi A1 Sportback (AACV)');
$autoList->append('Audi A4 Limousine (AAAM)');
$autoList->append('Audi A4 allroad quattro (AABV)');
$autoList->append('Audi A6 Avant (AAAZ)');
$autoList->append('Audi A7 Sportback (AACP)');
$autoList->append('Audi A8 (AAAS)');
$autoList->append('Audi Allroad (AADM)');
$autoList->append('Audi Q3 (AACS)');
$autoList->append('Audi Q5 (AAAR)');
$autoList->append('Audi Q7 (AACE)');
$autoList->append('Audi RS 3 Limousine (AADQ)');
$autoList->append('Audi S4 Avant (AABG)');
$autoList->append('Audi S6 Limousine (AABI)');
$autoList->append('Audi SQ2 (AAEP)');
$autoList->append('Audi TT RS Coupé (AABY)');
$autoList->append('Audi TT Roadster (AACH)');
$autoList->append('Audi e-tron (AAEU)');
$modell = "Audi e-tron (AAEU)";

function recursive_array_search($needle, $haystack, $currentKey = '') {
    foreach($haystack as $key=>$value) {
        if (is_array($value)) {
            $nextKey = recursive_array_search($needle,$value, $currentKey . '[' . $key . ']');
            if ($nextKey) {
                return $nextKey;
            }
        }
        else if($value==$needle) {
            return is_numeric($key) ? $currentKey . '[' .$key . ']' : $currentKey . '[' .$key . ']';

        }
    }
    return false;
}

function getArrayForJson($array){
  return split(",",$array);
}

$i = $list->getIterator();

$jsonfile = file_get_contents('json/translation.json');
$json = json_decode($jsonfile,TRUE);
for($counter=0;$counter<=count($list2);$counter++){

      while($i->valid()){
      $array = $json[$modell]['0']['MissingEquipItemsTranslations'];
      foreach ($array as $key => $value) {
       echo $modell.";".$value[MissingTranslations][layerTitle].";".$value[UID].";\n<br>";

      }
      $i->next();
    }
}


      ?>
