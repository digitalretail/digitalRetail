<?php
error_reporting(0);
$list = new ArrayObject();
$list->append('json/schaufenster_1_a10.json');
$list->append('json/schaufenster_2_r8.json');

$verzeichnis = "json";
if ( is_dir ( $verzeichnis ))
{
    // öffnen des Verzeichnisses
    if ( $handle = opendir($verzeichnis) )
    {
        // einlesen der Verzeichnisses
        while (($file = readdir($handle)) !== false)
        {

            $list->append('json/'.$file);

        }
        closedir($handle);
    }
}


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
while($i->valid()){

$jsonfile = file_get_contents($i->current());
$json = json_decode($jsonfile,TRUE);

$model =  recursive_array_search("model", $json);

print_r(array_search("model",$json));

$gewicht = recursive_array_search("gross_weight_limit", $json);
$leistung = recursive_array_search("power.kw", $json);
$co2_nefz = recursive_array_search("co2.emission", $json);
$hubraum = recursive_array_search("displacement", $json);
$fuel =  recursive_array_search("fuel", $json);
$fuel_type = recursive_array_search("fuel_type", $json);
$efficeny_class = recursive_array_search("efficiency_class", $json);

$kombiniert = recursive_array_search("consumption_combined", $json);
$innerorts = recursive_array_search("consumption_urban", $json);
$ausserorts = recursive_array_search("consumption_extra_urban", $json);
$wltp = recursive_array_search("WLTP", $json);



//echo $json['items'][$tmp[2]'][$tmp[3]];
?>
        <table>
         <tr><td> Feld </td><td> Schlüssel </td><td> Wert </td></tr>
         <tr><td> Marke: </td><td>  </td><td>Audi</td></tr>
         <tr><td> Modell: </td><td> <?php echo $model; ?></td><td><?php echo $json[items][3][value];?></td></tr>
         <tr><td> Gewicht: </td><td> <?php echo $gewicht; ?></td>
           <td>
             <?php
                 $zahl1 = substr($gewicht,53,1);
                 $zahl2 = substr($gewicht,86,1);

                 echo $json[hypermediaEquips][0][equipType][hypermediaFamilies][$zahl1][equipFamily][hypermediaItems][$zahl2][equipItem][text];?>
               </td></tr>
         <tr><td> Leistung: </td><td> <?php echo $leistung; ?></td><td><?php echo $json[items][6][values][1]['value'];?></td></tr>
         <tr><td> CO2 NEFZ: </td><td> <?php echo $co2_nefz; ?></td>
                  <td>
                      <?php
                          $zahl1 = substr($co2_nefz,8,2);
                          echo $json[items][$zahl1][value];?>
                  </td></tr>
         <tr><td> Hubraum: </td><td> <?php echo $hubraum; ?></td><td><?php echo $json[hypermediaEquips][0][equipType][hypermediaFamilies][3][equipFamily][hypermediaItems][4][equipItem][text];
         echo $json[hypermediaEquips][0][equipType][hypermediaFamilies][3][equipFamily][hypermediaItems][3][equipItem][text];?></td></tr>
         <tr><td> BEnzin: </td><td> <?php echo $fuel; ?></td><td><?php echo $json[items][6][values][0][value];?></td></tr>
         <tr><td> Benzindetail: </td><td> <?php echo $fuel_type; ?></td>
              <td><?php
                  $zahl1 = substr($fuel_type,53,1);
                  $zahl2 = substr($fuel_type,86,1);

                  echo $json[hypermediaEquips][0][equipType][hypermediaFamilies][$zahl1][equipFamily][hypermediaItems][$zahl2][equipItem][text];?></td></tr>
         <tr>
           <td> Kombiniert: </td><td> <?php echo $kombiniert; ?></td>
             <td>
               <?php
                   $zahl1 = substr($kombiniert,53,1);
                   $zahl2 = substr($kombiniert,86,1);
                   $kombiniert = $json[hypermediaEquips][0][equipType][hypermediaFamilies][$zahl1][equipFamily][hypermediaItems][$zahl2][equipItem][text];
                   echo $json[hypermediaEquips][0][equipType][hypermediaFamilies][$zahl1][equipFamily][hypermediaItems][$zahl2][equipItem][text];?>
                 </td>
         </tr>
         <tr><td> Innerorts: </td><td> <?php echo $innerorts; ?></td>
           <td>
             <?php
                 $zahl1 = substr($innerorts,53,1);
                 $zahl2 = substr($innerorts,86,1);

                 echo $json[hypermediaEquips][0][equipType][hypermediaFamilies][$zahl1][equipFamily][hypermediaItems][$zahl2][equipItem][text];?>
               </td></tr>
         <tr><td> Ausserorts: </td><td> <?php echo $ausserorts; ?></td>
           <td>
             <?php
                 $zahl1 = substr($ausserorts,53,1);
                 $zahl2 = substr($ausserorts,86,1);

                 echo $json[hypermediaEquips][0][equipType][hypermediaFamilies][$zahl1][equipFamily][hypermediaItems][$zahl2][equipItem][text];?>
               </td>
         </tr>
         <tr><td> Effizienzklasse: </td><td> <?php echo $efficeny_class; ?></td>
           <td>
             <?php

                 echo $json[hypermediaEquips][0][equipType][hypermediaFamilies][2][equipFamily][hypermediaItems][0][equipItem][text];?>
               </td>
         </tr>

         <tr><td> Krafststoff: </td><td> Super: 1,45<br>Super Plus: 1,54<br>Diesel: 1,28</td>
           <td> <?php
                   $kombiniert = explode("l",$kombiniert);
                  echo $kombiniert[0]*1.45*20000/100;echo"<br>";
                         echo $kombiniert[0]*1.54*20000/100;echo"<br>";
                                echo $kombiniert[0]*1.28*20000/100;echo"<br>";

                ?></td>
         </tr>
         <tr><td> WLTP: </td><td> <?php echo $wltp; ?> </td><tr>
        </table>
<?php

echo"<hr>";
$model =  "";
$gewicht = "";
$leistung ="";
$co2_nefz = "";
$hubraum = "";
$fuel = "";
$fuel_type = "";
$kombiniert = "";
$innerorts ="";
$ausserorts = "";
$wltp="";

$i->next();
}
?>
