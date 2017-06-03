<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
       'css/site.css',
	   'css/style.css',
	   'css/default.css',
        //'http://fonts.googleapis.com/css?family=Oswald:100,400,300,700',
        //'http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,300italic',
    ];
    public $js = [
        //'js/watch.js',
        //'js/easing.js',
        //'js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
