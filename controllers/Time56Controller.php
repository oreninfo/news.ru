<?php 

namespace app\controllers;

use yii\web\Controller;
use yii\data\Pagination;
use app\models\Time56;

class Time56Controller extends Controller
{
	public function actionIndex()
	{
		$query = Yii::$app->db->createCommand('SELECT * FROM `time56` WHERE `idRubriki`=12')
		->queryAll();//Time56::find()->where(['idRubriki' => 12]);
		$pagination = new Pagination([
		   'defaultPageSize' => 7,
		   'totalCount' => $query->count(),
		   ]);
		   $titles = $query->orderBy(['id' => SORT_DESC])
		   ->offset($pagination->offset)
		   ->limit($pagination->limit)
		   ->all();
		   
		   return $this->render('index', [
		   'titles' => $titles,
		   'active_page' => Yii::$app->request->get("page", 1),
		   'count_pages' => $pagination->getPageCount(),
		   'pagination' => $pagination
		   ]);
	}
}
