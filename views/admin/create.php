<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<h1>Создать</h1>

<?php $form = ActiveForm::begin();?>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'title')->textInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'content')->textInput() ?>
    </div>
    <div class="col-md-12">
        <?= Html::submitButton('Создать', ['class' => 'btn btn-success'])?>
    </div>
</div>
<?php ActiveForm::end(); ?>


