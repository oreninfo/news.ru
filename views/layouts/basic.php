<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <!--header-->		
        <div class="header">  
            <div class="container">
                <!---start-top-nav---->
                <div class="top-menu">
                    <span class="menu"> </span> 
                    <ul>
                        <li class="active"><a href="index.php">HOME</a></li>						
                        <li><a href="about.html">ABOUT</a></li>	
                        <li><a href="contact.html">CONTACT</a></li>						
                        <div class="clearfix"> </div>
                    </ul>
                </div>
                <div class="clearfix"></div>

                <!---//End-top-nav---->					
            </div>
        </div>
        <!--/header-->
        <div class="content">
            <div class="container">
                <div class="content-grids">
                    <div class="col-md-8 content-main">
                        <div class="content-grid">					 
                            <div class="content-grid-info">
                                <img src="images/post1.jpg" alt=""/>
                                <div class="post-info">
                                    <h4><a href="single.html">Lorem ipsum dolor sit amet</a>  July 30, 2014 / 27 Comments</h4>
                                    <p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis.</p>
                                    <a href="single.html"><span></span>READ MORE</a>
                                </div>
                            </div>
                            <div class="content-grid-info">
                                <img src="images/post2.jpg" alt=""/>
                                <div class="post-info">
                                    <h4><a href="single.html">Lorem ipsum dolor sit amet</a>  July 30, 2014 / 27 Comments</h4>
                                    <p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis.</p>
                                    <a href="single.html"><span></span>READ MORE</a>
                                </div>
                            </div>
                            <div class="content-grid-info">
                                <img src="images/post3.jpg" alt=""/>
                                <div class="post-info">
                                    <h4><a href="single.html">Lorem ipsum dolor sit amet</a>  July 30, 2014 / 27 Comments</h4>
                                    <p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis.</p>
                                    <a href="single.html"><span></span>READ MORE</a>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-4 content-right">
                        <div class="recent">
                            <h3>RECENT POSTS</h3>
                            <ul>
                                <li><a href="#">Aliquam tincidunt mauris</a></li>
                                <li><a href="#">Vestibulum auctor dapibus  lipsum</a></li>
                                <li><a href="#">Nunc dignissim risus consecu</a></li>
                                <li><a href="#">Cras ornare tristiqu</a></li>
                            </ul>
                        </div>
                        <div class="comments">
                            <h3>RECENT COMMENTS</h3>
                            <ul>
                                <li><a href="#">Amada Doe </a> on <a href="#">Hello World!</a></li>
                                <li><a href="#">Peter Doe </a> on <a href="#"> Photography</a></li>
                                <li><a href="#">Steve Roberts  </a> on <a href="#">HTML5/CSS3</a></li>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                        <div class="archives">
                            <h3>ARCHIVES</h3>
                            <ul>
                                <li><a href="#">October 2013</a></li>
                                <li><a href="#">September 2013</a></li>
                                <li><a href="#">August 2013</a></li>
                                <li><a href="#">July 2013</a></li>
                            </ul>
                        </div>
                        <div class="categories">
                            <h3>CATEGORIES</h3>
                            <ul>
                                <li><a href="#">Vivamus vestibulum nulla</a></li>
                                <li><a href="#">Integer vitae libero ac risus e</a></li>
                                <li><a href="#">Vestibulum commo</a></li>
                                <li><a href="#">Cras iaculis ultricies</a></li>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <!---->
        <div class="footer">
            <div class="container">
                <p>Copyrights © 2015 Blog All rights reserved | Template by <a href="http://w3layouts.com/">W3layouts</a></p>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>