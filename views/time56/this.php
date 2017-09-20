<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<h1>Админка</h1>
<a href="/admin/create" class="btn btn-primary">Создать</a>
<table class="table">
    <thead>
        <tr>
            <td>#</td>
            <td>Название</td>
            <td>Дата создания</td>
            <td>Рубрика</td>
            <td>Действия</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($model as $item): ?>   
        <tr>
            <td><?=$item->id?></td>
            <td><?=$item->title?></td>
            <td><?=$item->created_at?></td>
            <td><?=$item->content?></td>
            
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
