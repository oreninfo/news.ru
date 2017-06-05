<?php 

namespace app\controllers;

use yii\web\Controller;
use app\models\Category;

class CategoryController extends Controller
{
	public function actionIndex()
	{
		$Category = Category::find();
                $Posts = $Category->orderBy(['id' => SORT_ASC]);
		 
                        return $this->render('index', [
		       'posts' => $Posts,
		        ]);    
		   
	}
}
?>