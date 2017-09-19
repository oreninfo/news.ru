
<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = '�������';
$this->params['breadcrumbs'][] = $this->title;
//print_r($arrayInView);
?>
<?php
set_time_limit(0);
include 'simple_html_dom.php';
//������� �������� ������ � ����� �� ��������� ������ � ���� ������
function IsNewsExist($iCategoryId,$sLink,$sDate,$aLastNews,$aTime56Count){
//$iCategoryId - id, ��� �������, ������� � ������ ������ �� ������
//$sLink - ������ �� �������, ������� �� � ������ ������ ������
//$sDate- ���� � ����� ������� $sLink
//$aLastNews - sql ������, ��������� ����� ��������� ����������� ������� � �� �� ������� $id_category
//$aTime56Count - ���������� �������� � ������� time56
	$TD = trim($sDate);
	$sDate = date("Y-m-d", strtotime($TD));
	if ($aTime56Count !== 0) {
		$sLastNewsAddDate = strtok($aLastNews['created_at'], " ");
		//echo "���������� ����"." ".$sDate." "."� �����"." ".$sLastNewsAddDate." "."15 str\r\n";
		if (trim($sDate)>trim($sLastNewsAddDate)) {
			//echo "���� ������\r\n";
			return 1;
        }
		elseif(trim($sDate) === trim($sLastNewsAddDate)) {
			//echo "���� �����\r\n";
			$sResult = strpos($sLink, $aLastNews['content_path']);
			if ($sResult !== FALSE) {
				//echo "������ �����, �� ��������� ��������� � ����\r\n";
				return 0; 
			}
			else {
				//echo "������ �� �����, ��������� ������� � ����\r\n";
				return 1; 
			}
		}
		else {
			//echo "������� �� ����� ��� ���� � ����\r\n";
			return 0;
		}
	}
	//echo "���� �����, ������� ��������� �������\r\n";
					return 1; ////
}
//������� �������� ��������� ���������///
function Parsing_News($sCategoryUrl,$iCategoryId,$aLastNews,$sCategoryName,$aTime56Count){
//$sCategoryUrl - ������ �� ������� ����� �������� �� ��
//$iCategoryId - id ������� �����
//$aLastNews - sql ������, � ������� ��������� ����� ��������� ������� �� ���������� ������� $id
//$sCategoryName - �������� �������
//$aTime56Count - ���������� �������� � ������� time56
	$aValues = [];
	$iCountNews = 0;
	$oTime = new DateTime();
	$iPageCount = 6; //����� ��������� ���������, �.�. ������� ��������� ������� � ������ �������
	for ($k=1; $k<=$iPageCount; $k++) {
		$oHtml = file_get_html(trim($sCategoryUrl."page".$k)); //stranichka site
		if ($oHtml === FALSE){
			echo "Error parsing news 53 str\r\n";
			return 0;
		}
			foreach($oHtml->find('div[class=news]') as $element) {
				$sLinkNews = $element->find("div[class=news_name] a",0);
				$sNewsDate = $element->find("div[class=news_date]",0);
				//echo $sLinkNews->href." ".$sNewsDate->plaintext."��� ������� ����� ���������� � �������� �� ���� 59 str\r\n";
				if ($aLastNews !== FALSE){
					if (IsNewsExist($iCategoryId,$sLinkNews->href,$sNewsDate->plaintext,$aLastNews,$aTime56Count) == 0) {	
					$oHtml->clear();
				    unset($oHtml);
					//echo "����� �� 64 ������\r\n";
					$iResult = Add_News_in_DB($aValues,$iCountNews);
					echo "���������"." ".$iResult." "."�������� � �������"." ".'"'.$sCategoryName.'"'."!"."\r\n";
				    return $iResult;
					}
				}
				//echo $sLinkNews->href." ".$sNewsDate->plaintext."������� ������ �������� �� ���������� 68 str\r\n";
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
						 //echo "�������"." ".$sLinkNews->href." "."�������� 85str \r\n";
						 }
					}					
				}				
				$iCountNews++;				
			}
	}
	$iResult = Add_News_in_DB($aValues,$iCountNews);
	echo "���������"." ".$iResult." "."�������� � �������"." ".'"'.$sCategoryName.'"'."!"."\r\n";
	return $iResult;
}
///// 
///////������� ��������� ������////////
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
///////������� �������� �������
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
///////������� ���������� �������� � ���� ������/////
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
$db = mysql_connect("localhost", "stas", "123456") or die ("MySQL ������ ����������!" .mysql_error());
mysql_select_db("link_bd", $db) or die ("�� ������� ������������ � ���� �����!�" .mysql_error());
/////������� ������////////
$aCategory = Get_Category();
/////������� ��������� �����////////////
$hQuery = mysql_query("SELECT COUNT(id) FROM `time56`");
$aTime56Count = mysql_fetch_array($hQuery);
$iCountAddedNews = 0;
foreach($aCategory as $sLinkCategory){	
		$Query = "SELECT * FROM `time56` WHERE `id_category` = '$sLinkCategory[id]' ORDER BY `created_at` DESC LIMIT 1";
        $hQuery = mysql_query($Query) or trigger_error(mysql_error()." in ". $Query);
	    $aResult = mysql_fetch_assoc($hQuery);
		//echo "======================================================================\r\n";
		//echo "������ �� ������� -"." ".$sLinkCategory['category_link']." "."id - �������"." ".$sLinkCategory['id']." "."��������� ����������� �������, ����"." ".$aResult['created_at']." "."160 str\r\n";
		$iCountNews = Parsing_News($sLinkCategory['category_link'],$sLinkCategory['id'],$aResult,$sLinkCategory['category_my'],$aTime56Count[0]);
		$iCountAddedNews=$iCountAddedNews+$iCountNews; //����� ����������� ��������.
		
}
echo "����� ��������� ".$iCountAddedNews." ��������\r\n";
?>

<?= LinkPager::widget(['pagination' => $pagination]) ?>
  

