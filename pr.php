<?php



$jsonfile = file_get_contents('a1_sportback.json');
$json = json_decode($jsonfile,true);
$key = array_key_exists('prnumber', $json);
$list = "";
$GLOBALS['list'] = "";
$GLOBALS['counter'] = 0;
function test_print($item, $key)
{
    if($key == "number"){
      if(strlen($item) == 3){

        $GLOBALS['list'].=$item.",";
        $GLOBALS['counter']++;
      }else{
      }
    }
}

array_walk_recursive($json, 'test_print');
print_r(  $GLOBALS['list']);
echo"<h2>".$GLOBALS['counter']."</h2>";

 ?>
