
<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = 'Власть';
$this->params['breadcrumbs'][] = $this->title;
//print_r($arrayInView);
?>
<?php
function word_trim($string, $count, $ellipsis = FALSE){
  $words = explode(' ', $string);
  if (count($words) > $count){
    array_splice($words, $count);
    $string = implode(' ', $words);
    if (is_string($ellipsis)){
      $string .= $ellipsis;
    }
    elseif ($ellipsis){
      $string .= '…';
    }
  }
  return $string;
}?>
<?php foreach ($titles as $title): ?>
 <div class="news_list">   
	<div class="news">
	<?php 
		     $a = $title->title; 
			 $b = $title->content;
		      $text = str_replace("&quot;", "''", $a);
			  $massive = array("&quot;", "&laquo;", "&raquo;", "&nbsp;","&ndash;");
			  $massive2 = array("''", "''", "''", " ", " - ");
			  $text2 = str_replace($massive, $massive2, $b);
			  $text3 = word_trim($text2, 15);
			  ?>
			  <div class="news_img">
			  <?= Html::a(Html::img(Html::encode($title->image_path), ['widht' => '150', 
			   'height' => '160',
			   'title' => $title->title,
			   'alt' => $title->title
			   ]), ["'$title->content_path'"],['title' => $title->title]); 
			   ?> 
			   </div>
			  <div class="news_name">
			   <?= Html::a(Html::encode($text), ["time56/news/$title->id"],[
			  'title' => $title->title, 'align' => 'right']);?>
		       </div>
			   <div class="news_anons"><p><?= Html::encode($text3)?></p>
			   </div>
			   <div class="news_date"><?= Html::encode($title->created_at)?></div>
			   <div class="clear"></div>
	</div>	
</div>
		
<?php endforeach; ?>

<?= LinkPager::widget(['pagination' => $pagination]) ?>
  

