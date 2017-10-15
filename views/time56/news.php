<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Текущая новость';
$this->params['breadcrumbs'][] = $this->title;
?>
<tbody>
    <h1><?= $model->title; ?></h1>
    <span class="date_info"><?= $model->created_at; ?></span>
    <div style="margin:20px 0;text-align:center"><a href="<?= $model->image_path; ?>"><img src="<?= $model->image_path; ?>" width="500" height="340" alt="Фото: yurevets33" title="<?= $model->title; ?>"></a></div> 
    <p style="text-align: justify;"><?= $model->content; ?></p>
</tbody>
