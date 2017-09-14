<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
?>
<h1>Админка</h1>
<a href="/admin/create" class="btn btn-primary">Создать</a>
<table class="table">
    <thead>
        <tr>
            <td>#</td>
            <td>Название</td>
            <td>Дата создания</td>
            <td>Действия</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($model as $item): ?>
        <tr>
            <td><?=$item->id?></td>
            <td><?=$item->title?></td>
            <td><?=$item->created_at?></td>
            <td><?=$item->id_category?></td>
            <td>
                <a href="/admin/edit/<?=$item->id?>">Редактировать</a>
                <a href="/admin/delete/<?=$item->id?>">Удалить</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= LinkPager::widget(['pagination' => $pages,
    ]); ?>