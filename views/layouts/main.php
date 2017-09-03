<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        //'brandLabel' => 'Новости',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Все новости', 'url' => ['/time56/index']],
            ['label' => 'Общество', 'url' => ['/time56/obshestvo']],
            ['label' => 'Происшествия', 'url' => ['/time56/proishestviya']],
			['label' => 'Власть', 'url' => ['/time56/vlast'/*, 'view' => 'one'*/]],
			['label' => 'Культура', 'url' => ['/time56/kultura']],
			['label' => 'Экономика', 'url' => ['/time56/economy']],
			['label' => 'Спорт', 'url' => ['/time56/sport']],
			['label' => 'Образование', 'url' => ['/time56/education']],
			['label' => 'Здоровье', 'url' => ['/time56/health']],
			['label' => 'В мире', 'url' => ['/time56/world']],
			['label' => 'В России', 'url' => ['/time56/russia']],
			['label' => 'Видео', 'url' => ['/time56/video']],
			['label' => 'TV', 'url' => ['/time56/TV']],
            /*Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            ) */
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
