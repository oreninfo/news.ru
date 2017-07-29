<?php
namespace app\controllers;

use yii\web\Controller;
use yii;
use app\models\Time56;
use yii\data\Pagination;

class AdminController extends Controller
{
    public function actionIndex()
    {
        #$query = Time56::getAll();
        $oCountQuery = Time56::find();
        #$countQuery = clone $query;
        $oPages = new Pagination(['totalCount'=> $oCountQuery->count(), 'pageSize' => 100]);
        $oPages->pageSizeParam = false;
        $oModels = $oCountQuery->offset($oPages->offset)
                ->limit($oPages->limit)
                ->all();
        
        return $this->render('index', ['model'=>$oModels,
                'pages' => $oPages,
                ]);
    }
    public function actionEdit($id)
    {
        $one = Time56::getOne($id);
        
        if($_POST['Time56'])
        {
            $one->title=$_POST['Time56']['title'];
            $one->content=$_POST['Time56']['content'];
            #$one->created_at = date("Y-m-d H:i:s");
            if($one->validate() && $one->save())
            {
                return $this->redirect(['index']);
            }
        }
       return $this->render('edit',['one'=>$one]);
    }
    public function actionCreate()
    {
        $model = new Time56();
        
        if($_POST['Time56'])
        {
            $model->title=$_POST['Time56']['title'];
            $model->content=$_POST['Time56']['content'];
            $model->created_at = date("Y-m-d H:i:s");
            if($model->validate() && $model->save())
            {
                return $this->redirect(['index']);
            }
        }
       return $this->render('create', ['model'=>$model]);
    }
    public function actionDelete($id)
    {
        $model = Time56::getOne($id);
        if ($model !== NULL) {
        $model -> delete();
        return $this->redirect(['index']);
        }
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

