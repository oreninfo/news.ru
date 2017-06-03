<?php 

namespace app\controllers;

use yii\web\Controller;
use app\models\Category;

class CategoryController extends Controller
{
	public function actionIndex()
	{
		$Category = Category::find()->all();
		
		   foreach($Category as $scategory) {
			   echo $scategory->name;     
		    } 
		   
	}
}
?>