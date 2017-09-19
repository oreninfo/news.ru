
<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = 'ѕарсинг';
$this->params['breadcrumbs'][] = $this->title;
//print_r($arrayInView);
?>
<?php
set_time_limit(0);
include 'simple_html_dom.php';
//функци€ проверки ссылки с сайта на равенство ссылке в базе данных
function IsNewsExist($iCategoryId,$sLink,$sDate,$aLastNews,$aTime56Count){
//$iCategoryId - id, той рубрики, которую в данный момент мы парсим
//$sLink - ссылка на новость, которую мы в данный момент парсим
//$sDate- дата с сайта новости $sLink
//$aLastNews - sql запрос, вывод€щий самую последнюю добавленную новость в Ѕƒ из рубрики $id_category
//$aTime56Count - количество новостей в таблице time56
	$TD = trim($sDate);
	$sDate = date("Y-m-d", strtotime($TD));
	if ($aTime56Count !== 0) {
		$sLastNewsAddDate = strtok($aLastNews['created_at'], " ");
		//echo "сравниваем дату"." ".$sDate." "."с датой"." ".$sLastNewsAddDate." "."15 str\r\n";
		if (trim($sDate)>trim($sLastNewsAddDate)) {
			//echo "ƒата больше\r\n";
			return 1;
        }
		elseif(trim($sDate) === trim($sLastNewsAddDate)) {
			//echo "ƒата равна\r\n";
			$sResult = strpos($sLink, $aLastNews['content_path']);
			if ($sResult !== FALSE) {
				//echo "строки равны, не требуетс€ добавл€ть в базу\r\n";
				return 0; 
			}
			else {
				//echo "строки не равны, добавл€ем новость в базу\r\n";
				return 1; 
			}
		}
		else {
			//echo "новость на сайте уже есть в базе\r\n";
			return 0;
		}
	}
	//echo "база пуста, поэтому добавл€ем новость\r\n";
					return 1; ////
}
//функци€ парсинга страничек новостных///
function Parsing_News($sCategoryUrl,$iCategoryId,$aLastNews,$sCategoryName,$aTime56Count){
//$sCategoryUrl - ссылка на рубрику сайта получена из Ѕƒ
//$iCategoryId - id рубрики сайта
//$aLastNews - sql запрос, в котором хранитьс€ сама€ последн€€ новость из конкретной рубрики $id
//$sCategoryName - название рубрики
//$aTime56Count - количество новостей в таблице time56
	$aValues = [];
	$iCountNews = 0;
	$oTime = new DateTime();
	$iPageCount = 6; //число страничек пагинации, т.е. сколько страничек парсить в каждой рубрике
	for ($k=1; $k<=$iPageCount; $k++) {
		$oHtml = file_get_html(trim($sCategoryUrl."page".$k)); //stranichka site
		if ($oHtml === FALSE){
			echo "Error parsing news 53 str\r\n";
			return 0;
		}
			foreach($oHtml->find('div[class=news]') as $element) {
				$sLinkNews = $element->find("div[class=news_name] a",0);
				$sNewsDate = $element->find("div[class=news_date]",0);
				//echo $sLinkNews->href." ".$sNewsDate->plaintext."Ёту новость будем сравнивать с новостью из базы 59 str\r\n";
				if ($aLastNews !== FALSE){
					if (IsNewsExist($iCategoryId,$sLinkNews->href,$sNewsDate->plaintext,$aLastNews,$aTime56Count) == 0) {	
					$oHtml->clear();
				    unset($oHtml);
					//echo "вышли на 64 строке\r\n";
					$iResult = Add_News_in_DB($aValues,$iCountNews);
					echo "ƒобавлено"." ".$iResult." "."новостей в рубрику"." ".'"'.$sCategoryName.'"'."!"."\r\n";
				    return $iResult;
					}
				}
				//echo $sLinkNews->href." ".$sNewsDate->plaintext."Ќовость прошла проверку на добавление 68 str\r\n";
				$oHtml2 = file_get_html(trim($sLinkNews->href));
				if ($oHtml2 === FALSE){
					echo "Error parsing news";
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
						 $aValues[] = "('$sTitle', '$sContent', '$sCreatedAt', '$aRes[1]', '$sLinkNews->href', '$iCategoryId')";
					     $oTime = $oTime->modify('-1 minute');
						 //echo "Ќовость"." ".$sLinkNews->href." "."добавили 85str \r\n";
						 }
					}					
				}				
				$iCountNews++;				
			}
	}
	$iResult = Add_News_in_DB($aValues,$iCountNews);
	echo "ƒобавлено"." ".$iResult." "."новостей в рубрику"." ".'"'.$sCategoryName.'"'."!"."\r\n";
	return $iResult;
}
///// 
///////функци€ получени€ рубрик////////
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
///////функци€ парсинга рубрики
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
///////функци€ добавлени€ новостей в базу данных/////
function Add_News_in_DB($aValues,$iCountNews){
	$aValues = implode(',' ,$aValues);
	$Query = "INSERT INTO `time56` (`title`, `content`, `created_at`, `image_path`, `content_path`, `id_category`) VALUES $aValues;";
	$hQuery = mysql_query($Query);
	if ($hQuery === TRUE) {
		return $iCountNews;
	}
	else {
		return 0;
	}
}
//telo programm//
$db = mysql_connect("localhost", "stas", "123456") or die ("MySQL сервер недоступен!" .mysql_error());
mysql_select_db("link_bd", $db) or die ("Ќе удалось подключитьс€ к базе даных!Ф" .mysql_error());
/////парсинг рубрик////////
$aCategory = Get_Category();
/////парсинг страничек сайта////////////
$hQuery = mysql_query("SELECT COUNT(id) FROM `time56`");
$aTime56Count = mysql_fetch_array($hQuery);
$iCountAddedNews = 0;
foreach($aCategory as $sLinkCategory){	
		$Query = "SELECT * FROM `time56` WHERE `id_category` = '$sLinkCategory[id]' ORDER BY `created_at` DESC LIMIT 1";
        $hQuery = mysql_query($Query) or trigger_error(mysql_error()." in ". $Query);
	    $aResult = mysql_fetch_assoc($hQuery);
		//echo "======================================================================\r\n";
		//echo "—сылка на рубрику -"." ".$sLinkCategory['category_link']." "."id - рубрики"." ".$sLinkCategory['id']." "."ѕоследн€€ добавленна€ новость, дата"." ".$aResult['created_at']." "."160 str\r\n";
		$iCountNews = Parsing_News($sLinkCategory['category_link'],$sLinkCategory['id'],$aResult,$sLinkCategory['category_my'],$aTime56Count[0]);
		$iCountAddedNews=$iCountAddedNews+$iCountNews; //сумма добавленных новостей.
		
}
echo "¬сего добавлено ".$iCountAddedNews." новостей\r\n";
?>

<?= LinkPager::widget(['pagination' => $pagination]) ?>
  

