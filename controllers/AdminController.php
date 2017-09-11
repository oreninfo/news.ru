<?php
namespace app\controllers;

use yii\web\Controller;
use yii;
use yii\web\UploadedFile;
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
        $oOne = Time56::getOne($id);
        
        if($oOne->load(Yii::$app->request->post()))
        {
            $oOne->title=$_POST['Time56']['title'];
            $oOne->content=$_POST['Time56']['content'];
            #$one->created_at = date("Y-m-d H:i:s");
            if($oOne->validate() && $oOne->save())
            {
                return $this->redirect(['index']);
            }
        }
       return $this->render('edit',['oOne'=>$oOne]);
    }
    public function actionCreate()
    {
        $oModel = new Time56();
        if ($oModel->load(Yii::$app->request->post())) 
         {
            $oModel->imageFile = UploadedFile::getInstance($oModel, 'imageFile');
            $oModel->imageFile->saveAs('photo/'.$oModel->imageFile->baseName.".".$oModel->imageFile->extension);
            $oModel->title=$_POST['Time56']['title'];
            $oModel->content=$_POST['Time56']['content'];
            $oModel->image_path='photo/'.$oModel->imageFile->baseName.".".$oModel->imageFile->extension;
            $oModel->id_category=$_POST['Time56']['id_category'];
            $oModel->created_at = date("Y-m-d H:i:s");
            if($oModel->validate()&& $oModel->save())
            {
               
               
                return $this->redirect(['index']);
            }
         }
            return $this->render('create', ['oModel'=>$oModel]);
    }
    public function actionDelete($id)
    {
        $oModel = Time56::getOne($id);
        if ($oModel !== NULL) {
        $oModel -> delete();
        return $this->redirect(['index']);
        }
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

