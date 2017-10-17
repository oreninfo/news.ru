<?php
$db = mysql_connect("localhost", "stas", "123456") or die ("MySQL сервер недоступен!" .mysql_error());
mysql_select_db("link_bd", $db) or die ("Не удалось подключиться к базе даных!”" .mysql_error());
$hQuery = mysql_query("SELECT * FROM `time56`");
while($aResult = mysql_fetch_assoc($hQuery)){
 $aNews[] = $aResult;
}

for ($i = 0 ; $i < count($aNews); ++$i)
   {    $V=$aNews[$i][id].",".$aNews[$i][id_category];
        $aValuesIns[]="($V)";
        for ($j = $i+1; $j<count($aNews);++$j){
            if ($aNews[$i][title] === $aNews[$j][title] and $aNews[$i][image_path] === $aNews[$j][image_path]) {
                $V=$aNews[$i][id].",".$aNews[$j][id_category];
                $aValuesIns[]="($V)";
                /*if( isset($aNews[$j])  )
                    {
                    unset( $aNews[$j] );
                    
                    }*/
               // --$j;
            }
        }
    }
    //print_r($aValuesIns);
 	$aValuesIns = implode("," ,$aValuesIns);
	$Query = "INSERT INTO `time56_category` (`news_id`, `category_id`) VALUES $aValuesIns;";
	$hQuery = mysql_query($Query);
	if ($hQuery === TRUE) {
		echo "Успешно";
	}
	else {
	       echo "Не успешно";
	}
