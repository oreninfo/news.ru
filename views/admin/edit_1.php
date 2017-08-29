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
       <?=$form->field($one, 'id_category')->dropDownList(['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12']); ?>  
    </div> 
    <div class="col-md-12">
        <?= Html::submitButton('Изменить', ['class' => 'btn btn-success'])?>
    </div>
</div>
<?php ActiveForm::end(); ?>