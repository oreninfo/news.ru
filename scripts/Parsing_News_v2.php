<?php
set_time_limit(0);
include 'simple_html_dom.php';
//функция проверки ссылки с сайта на равенство ссылке в базе данных
function IsNewsExist($iCategoryId,$sLink,$sDate,$sTitleNews){
//$iCategoryId - id, той рубрики, которую в данный момент мы парсим
//$sLink - ссылка на новость, которую мы в данный момент парсим
//$sDate- дата с сайта новости $sLink
//sTitleNews - заголовок новости 
	$TD = trim($sDate);
	$sDate = date("Y-m-d", strtotime($TD));
        $sTitleNews = trim($sTitleNews);
        $sLink = trim($sLink);
        $Query = "SELECT t.id, t.title, t.content_path, tc.category_id FROM time56 t LEFT JOIN time56_category tc ON t.id = tc.news_id LEFT JOIN category c ON c.id = tc.category_id WHERE t.title = '$sTitleNews' AND t.content_path = '$sLink'";
        $hQuery = mysql_query($Query) or trigger_error(mysql_error()." in ". $Query);
	$aResult = mysql_fetch_assoc($hQuery);
        if($aResult !== FALSE)
        {
            if($iCategoryId == $aResult['category_id'])
            {
                return 0; //добавлять новость не надо
            }
            else
            {   
                $Query2 = "SELECT news_id, category_id FROM `time56_category` WHERE news_id = '$aResult[id]' AND category_id = '$iCategoryId';";
                $hQuery2 = mysql_query($Query2) or trigger_error(mysql_error()." in ". $Query2);
                $aResult2 = mysql_fetch_assoc($hQuery2);
                if($aResult2 === FALSE){
                $Query = "INSERT INTO `time56_category` (`news_id`, `category_id`) VALUES ($aResult[id], $iCategoryId);";
                $hQuery = mysql_query($Query) or trigger_error(mysql_error()." in ". $Query);
                }
                return 0;//добавляем только категорию в связанную таблицу к айдишнику новости
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
//$sCategoryName - название рубрики
//$sPathImage - путь до сохраняемой картинки на сервере
//$aValues[] - массив содержащий в себе новости, которые будут добавлены в базу 
//$aValuesTC[] - массид содержащий news_id и category_id для добавления в связанную таблицу БД
//$iMaxId - максимальный id новости в таблице time56
        $aValuesTC = [];
	$aValues = [];
        $aRes = 0;
	$iCountNews = 0;
        $iPageCount = 1; //число страничек пагинации, т.е. сколько страничек парсить в каждой рубрике
	$oTime = new DateTime();
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
                 if($iMaxId !== NULL){
                     if (IsNewsExist($iCategoryId,$sLinkNews->href,$sNewsDate->plaintext,$sLinkNews->plaintext) == 0) {
                         continue;
			}
                    }
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
                        }
                        
                    }					
		}
                $iCountNews++;				
	    }             
	}
        $oHtml->clear();
        unset($oHtml);
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
$iCountAddedNews = 0;
foreach($aCategory as $sLinkCategory){		
                $iCountNews = Parsing_News($sLinkCategory['category_link'],$sLinkCategory['id'],$sLinkCategory['category_my']);
		$iCountAddedNews=$iCountAddedNews+$iCountNews; //сумма добавленных новостей.                
}
echo "Всего добавлено ".$iCountAddedNews." новостей\r\n";
$hQuery = mysql_query("SELECT COUNT(id) FROM `time56`");
$aTime56Count = mysql_fetch_array($hQuery);
echo "Всего количество новостей в базе: ".$aTime56Count[0]."\r\n";
echo 'Время выполнения скрипта: '.(microtime(true) - $start).' сек.'; 
?>
