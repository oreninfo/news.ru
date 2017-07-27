<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<h1>Админка</h1>
<a href="/controllers/admin/" class="btn btn-primary">Создать</a>
<table class="table">
    <thead>
        <tr>
            <td>#</td>
            <td>Название</td>
            <td>Действия</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($model as $item): ?>
        <tr>
            <td><?=$item->id?></td>
            <td><?=$item->title?></td>
            <td>
                <a href="/views/admin/edit/<?=$item->id?>">Редактировать</a>
                <a href="/views/admin/delete/<?=$item->id?>">Удалить</a>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>