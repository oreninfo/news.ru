<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
?>
<h1>Админка</h1>
<a href="<?=Yii::$app->urlManager->createUrl(["admin/create"])?>" class="btn btn-primary">Создать</a>
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
            <?php foreach($model_2 as $item2):
                if ($item->id_category == $item2->id) {?>
            
        <tr>
            <td><?=$item->id?></td>
            <td><?=$item->title?></td>
            <td><?=$item->created_at?></td>
            <td><?=$item2->category_my?></td>
            <td>
                <a href="<?=Yii::$app->urlManager->createUrl(["admin/edit/$item->id"])?>">Редактировать</a>
                <a href="<?=Yii::$app->urlManager->createUrl(["admin/delete/$item->id"])?>">Удалить</a>
            </td>
        </tr>
                <?php } endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>
<?= LinkPager::widget(['pagination' => $pages,
    ]); ?>