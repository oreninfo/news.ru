<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<h1>Редактировать</h1>

<?php $form = ActiveForm::begin();?>
<div class="row">
    <div class="col-md-8">
        <?= $form->field($one, 'title')->textInput()?>
    </div>
    <div class="col-md-8"> 
        <?= $form->field($one, 'content')->textarea(['rows'=>5,'cols'=>10]) ?>
    </div>
     <div class="col-md-8">
        <?= $form->field($one, 'image_path')->textInput() ?>
    </div>
     <div class="col-md-8">
       <?=$form->field($one, 'id_category')->dropDownList(['1'=>'Общество','2'=>'Происшествие','3'=>'Власть','4'=>'Культура','5'=>'Экономика','6'=>'Спорт','7'=>'Образование','8'=>'Здоровье','9'=>'В мире','10'=>'В России','11'=>'Видео','12'=>'TV']); ?>  
    </div> 
    <div class="col-md-12">
        <?= Html::submitButton('Изменить', ['class' => 'btn btn-success'])?>
    </div>
</div>
<?php ActiveForm::end(); ?>