<?php
set_time_limit(0);
include 'simple_html_dom.php';
//функция проверки ссылки с сайта на равенство ссылке в базе данных
function IsNewsExist($iCategoryId,$sLink,$sDate,$sTitleNews){
//$iCategoryId - id, той рубрики, которую в данный момент мы парсим
//$sLink - ссылка на новость, которую мы в данный момент парсим
//$sDate- дата с сайта новости $sLink
//$aLastNews - sql запрос, выводящий самую последнюю добавленную новость в БД из рубрики $id_category
//$aTime56Count - количество новостей в таблице time56
	$TD = trim($sDate);
	$sDate = date("Y-m-d", strtotime($TD));
        //$Query = "SELECT id, title, content_path FROM `time56` WHERE content_path = '$sLink' AND title = '$sTitleNews'";
        $Query = "SELECT t.id, t.title, t.content_path, tc.category_id FROM time56 t LEFT JOIN time56_category tc ON t.id = tc.news_id LEFT JOIN category c ON c.id = tc.category_id WHERE t.title = '$sTitleNews' AND t.content_path = '$sLink'";
        $hQuery = mysql_query($Query) or trigger_error(mysql_error()." in ". $Query);
	$aResult = mysql_fetch_assoc($hQuery);
        if($aResult !== FALSE)
        {
            if($iCategoryId == $aResult['category_id'])
            {
                echo "такая новость"." "."'".$sTitleNews."'"."совпадает с таковой в базе"." "."'".$aResult['title']."'"."\r\n";
                echo "их категории равны"." ".$iCategoryId." == ".$aResult['category_id'];
                return 0; //добавлять новость не надо
            }
            else
            {
                //добавляем только категорию в связанную таблицу к айдишнику новости
                $Query = "INSERT INTO `time56_category` (`news_id`, `category_id`) VALUES $aResult[id], $iCategoryId;";
            }
        }
        else {    
            return 1;
        }
}
//функция парсинга страничек новостных///
function Parsing_News($sCategoryUrl,$iCategoryId,$sCategoryName){
//$sCategoryUrl - ссылка на рубрику сайта получена из БД
//$iCategoryId - id рубрики сайта
//$aLastNews - sql запрос, в котором храниться самая последняя новость из конкретной рубрики $id
//$sCategoryName - название рубрики
//$aTime56Count - количество новостей в таблице time56
//$sPathImage - путь до сохраняемой картинки на сервере
//$aValues[] - массив содержащий в себе новости, которые будут добавлены в базу 
//$iMaxId - максимальный id новости в таблице time56
        $aValuesTC = [];
	$aValues = [];
        $aRes = 0;
	$iCountNews = 0;
        $iPageCount = 1; //число страничек пагинации, т.е. сколько страничек парсить в каждой рубрике
	$oTime = new DateTime();
        $Query = "SELECT * FROM `time56`";
        $hQuery = mysql_query($Query) or trigger_error(mysql_error()." in ". $Query);
	$aResult = mysql_fetch_assoc($hQuery);
        if($aResult === FALSE)
        {
        $iMaxId = 0;    
       	for ($k=1; $k<=$iPageCount; $k++) {
		$oHtml = file_get_html(trim($sCategoryUrl."page".$k)); //stranichka site
		if ($oHtml === FALSE){
			echo "Error parsing news 53 str\r\n";
			return 0;
		}
			foreach($oHtml->find('div[class=news]') as $element) {
				$sLinkNews = $element->find("div[class=news_name] a",0);
				$sNewsDate = $element->find("div[class=news_date]",0);
				//echo $sLinkNews->href." ".$sNewsDate->plaintext."Эту новость будем сравнивать с новостью из базы 59 str\r\n";
				/*if ($aLastNews !== FALSE){
					if (IsNewsExist($iCategoryId,$sLinkNews->href,$sNewsDate->plaintext,$aLastNews,$aTime56Count) == 0) {	
					$oHtml->clear();
				        unset($oHtml);
					//echo "71 строка \r\n";
					$iResult = Add_News_in_DB($aValues,$iCountNews,$aValuesTC);
                                        echo "Добавлено"." ".$iResult." "."новостей в рубрику"." ".'"'.$sCategoryName.'"'."!"."\r\n";
				        return $iResult;
					}
                                        //echo "77 строка \r\n";
				}*/
				//echo $sLinkNews->href." ".$sNewsDate->plaintext."Новость прошла проверку на добавление 68 str\r\n";
				$oHtml2 = file_get_html(trim($sLinkNews->href));
				if ($oHtml2 === FALSE){
					echo "Error parsing news\r\n";
					return 0;
				}
				foreach($oHtml2->find('div[class=news_text]') as $element2) {
					$sTitle = trim($element2->find("h1",0)->plaintext);
					if (!empty($sTitle)){
						$sContent = trim($element2->find("div[class=news_text]",0)->plaintext);
						$sImagePath = $element2->find("img[src]",0);
						$sCreatedAt = trim($element2->find("span[class=date_info]",0)->plaintext);
				                $sCreatedAt = date("Y-m-d", strtotime($sCreatedAt))." ".$oTime->format('H:i:s');
				                preg_match("/<img[^>]+src\s*=\s*[\"']\/?([^\"']+)[\"'][^>]*\>/", $sImagePath, $aRes);
				                if (!empty($sContent) && !empty($sCreatedAt) && !empty($aRes[1])) {
                                                    $sFileName = microtime(). ".jpg";
                                                    $sWebPath = "photo/".$sFileName;
                                                    $sPathImage = dirname(__DIR__)."\web\photo\\".$sFileName;
                                                    //copy($aRes[1], $sPathImage);
                                                    $aValues[] = "('$sTitle', '$sContent', '$sCreatedAt', '$sWebPath', '$sPathImage', '$sLinkNews->href')\r\n";
                                                    $iMaxId = $iMaxId + 1;
                                                    $aValuesTC[] = "('$iMaxId','$iCategoryId')\r\n";
					            $oTime = $oTime->modify('-1 minute');
						 //echo "Новость"." ".$sLinkNews->href." "."добавили 85str \r\n"; 
						 }
					}					
				}
				$iCountNews++;				
			}
                      
	}
        
	$iResult = Add_News_in_DB($aValues,$iCountNews,$aValuesTC);
	echo "Добавлено"." ".$iResult." "."новостей в рубрику"." ".'"'.$sCategoryName.'"'."!"."\r\n";
	return $iResult;
        
        
         }
         else 
             {
             $Query2 = "SELECT MAX(id) FROM `time56`";
             $hQuery2 = mysql_query($Query2) or trigger_error(mysql_error()." in ". $Query2);
	     $aMaxId = mysql_fetch_assoc($hQuery2);
             $iMaxId = $aMaxId["MAX(id)"];    
       	      for ($k=1; $k<=$iPageCount; $k++) {
		$oHtml = file_get_html(trim($sCategoryUrl."page".$k)); //stranichka site
		if ($oHtml === FALSE){
			echo "Error parsing news 53 str\r\n";
			return 0;
		}
			foreach($oHtml->find('div[class=news]') as $element) {
				$sLinkNews = $element->find("div[class=news_name] a",0);
				$sNewsDate = $element->find("div[class=news_date]",0);
				
                                
                                //echo $sLinkNews->href." ".$sNewsDate->plaintext."Эту новость будем сравнивать с новостью из базы 59 str\r\n";
				if (IsNewsExist($iCategoryId,$sLinkNews->href,$sNewsDate->plaintext,$sLinkNews->plaintext) == 0) {	
					$oHtml->clear();
				        unset($oHtml);
                                        continue;
					/*echo "71 строка \r\n";
					$iResult = Add_News_in_DB($aValues,$iCountNews,$aValuesTC);
                                        echo "Добавлено"." ".$iResult." "."новостей в рубрику"." ".'"'.$sCategoryName.'"'."!"."\r\n";
				        return $iResult;*/
					}
                                        //echo "77 строка \r\n";
				}
				//echo $sLinkNews->href." ".$sNewsDate->plaintext."Новость прошла проверку на добавление 68 str\r\n";
				$oHtml2 = file_get_html(trim($sLinkNews->href));
				if ($oHtml2 === FALSE){
					echo "Error parsing news\r\n";
					return 0;
				}
				foreach($oHtml2->find('div[class=news_text]') as $element2) {
					$sTitle = trim($element2->find("h1",0)->plaintext);
					if (!empty($sTitle)){
						$sContent = trim($element2->find("div[class=news_text]",0)->plaintext);
						$sImagePath = $element2->find("img[src]",0);
						$sCreatedAt = trim($element2->find("span[class=date_info]",0)->plaintext);
				                $sCreatedAt = date("Y-m-d", strtotime($sCreatedAt))." ".$oTime->format('H:i:s');
				                preg_match("/<img[^>]+src\s*=\s*[\"']\/?([^\"']+)[\"'][^>]*\>/", $sImagePath, $aRes);
				                if (!empty($sContent) && !empty($sCreatedAt) && !empty($aRes[1])) {
                                                    $sFileName = microtime(). ".jpg";
                                                    $sWebPath = "photo/".$sFileName;
                                                    $sPathImage = dirname(__DIR__)."\web\photo\\".$sFileName;
                                                    //copy($aRes[1], $sPathImage);
                                                    $aValues[] = "('$sTitle', '$sContent', '$sCreatedAt', '$sWebPath', '$sPathImage', '$sLinkNews->href')\r\n";
                                                    $iMaxId = $iMaxId + 1;
                                                    $aValuesTC[] = "('$iMaxId','$iCategoryId')\r\n";
					            $oTime = $oTime->modify('-1 minute');
						 //echo "Новость"." ".$sLinkNews->href." "."добавили 85str \r\n"; 
						 }
					}					
				}
				$iCountNews++;				
			}
                      
	}
        
	$iResult = Add_News_in_DB($aValues,$iCountNews,$aValuesTC);
	echo "Добавлено"." ".$iResult." "."новостей в рубрику"." ".'"'.$sCategoryName.'"'."!"."\r\n";
	return $iResult;
        
              
    
             
           
      
}

///////функция получения рубрик////////
function Get_Category(){
	$hQuery = mysql_query("SELECT * FROM `category`");
	while($aResult = mysql_fetch_assoc($hQuery)){
		$aCategory[] = $aResult;
	}
	if ($aCategory == []) {
		if (Parsing_Category() == 1) {
			$aCategory = Get_Category();
		}
		else {
			echo "There are no categories on the website\r\n";
			exit();
		}
	}	
	return $aCategory;
}
///////функция парсинга рубрики
function Parsing_Category() {
    $oHtml = file_get_html('http://www.time56.ru/');
	if ($oHtml === FALSE) {
	return 0;
	}
	$aLinkCategory=$oHtml->find('div[class=hide_menu] ul a');
	$aValues = [];
	foreach($aLinkCategory as $Link){
		$sHref = $Link->href;
		$sText = $Link->plaintext;
		if (!empty($sHref) && !empty($sText)) {
			$aValues[] = "('$sText', '$sText', '$sHref')";
		}
	}
	$aValues = implode(',' ,$aValues);
	$Query = "INSERT INTO category (`category_my`, `category_time56`, `category_link`) VALUES $aValues;";
	$hQuery = mysql_query ($Query);
	if ($hQuery === TRUE) {
		echo "Category added!\r\n";
		return 1;
	}
	else {
		echo "Category not added!\r\n";
		return 0; 
	}
}
///////функция добавления новостей в базу данных/////
function Add_News_in_DB($aValues,$iCountNews,$aValuesTC){
	$aValues = implode(',' ,$aValues);
        $aValuesTC = implode(',' ,$aValuesTC);
	$Query = "INSERT INTO `time56` (`title`, `content`, `created_at`, `image_path`, `local_image_path`, `content_path`) VALUES $aValues;";
        $Query2 = "INSERT INTO `time56_category` (`news_id`, `category_id`) VALUES $aValuesTC;";
	$hQuery = mysql_query($Query);
	if ($hQuery === TRUE) {
            $hQuery2 = mysql_query($Query2);
            if ($hQuery2 === TRUE)
            {
		return $iCountNews;
                
            } else {
                return 0;
            }
        }
	else {
		return 0;
	}
}
//telo programm//
$start = microtime(true);
$db = mysql_connect("localhost", "stas", "123456") or die ("MySQL сервер недоступен!" .mysql_error());
mysql_select_db("link_bd", $db) or die ("Не удалось подключиться к базе даных!”" .mysql_error());
/////парсинг рубрик////////
$aCategory = Get_Category();
/////парсинг страничек сайта////////////
$hQuery = mysql_query("SELECT COUNT(id) FROM `time56`");
$aTime56Count = mysql_fetch_array($hQuery);
$iCountAddedNews = 0;
foreach($aCategory as $sLinkCategory){	
		//$Query = "SELECT t.*, tc.category_id FROM time56 t LEFT JOIN time56_category tc ON t.id = tc.news_id LEFT JOIN category c ON c.id = tc.category_id WHERE c.id = '$sLinkCategory[id]' ORDER BY t.created_at DESC LIMIT 1";
                //$Query2 = "SELECT MAX(id) FROM `time56`";
                //$Query = "SELECT * FROM `time56`";
                //$hQuery = mysql_query($Query) or trigger_error(mysql_error()." in ". $Query);
	        //$aResult = mysql_fetch_assoc($hQuery);
                //$hQuery2 = mysql_query($Query2) or trigger_error(mysql_error()." in ". $Query2);
	        /*$aMaxId = mysql_fetch_assoc($hQuery2);
                if ($aMaxId["MAX(id)"] == FALSE)
                {
                    $iMaxId = 0;
                    $iMinIdEmptyDB = $iMaxId;
                }
                else 
                {
                    $iMaxId = $aMaxId["MAX(id)"];
                    
                }*/
                
                 //echo "======================================================================\r\n";
		 //echo "Ссылка на рубрику -"." ".$sLinkCategory['category_link']." "."id - рубрики"." ".$sLinkCategory['id']." "."Последняя добавленная новость, дата"." ".$aResult['created_at']." "."160 str\r\n";
                
                $iCountNews = Parsing_News($sLinkCategory['category_link'],$sLinkCategory['id'],$sLinkCategory['category_my']);
		$iCountAddedNews=$iCountAddedNews+$iCountNews; //сумма добавленных новостей. 
                exit();
}
echo "Всего добавлено ".$iCountAddedNews." новостей\r\n";
echo 'Время выполнения скрипта: '.(microtime(true) - $start).' сек.'; 
?>
