<?php
namespace app\controllers;

use yii\web\Controller;
use yii;
use yii\web\UploadedFile;
use app\models\Time56;
use app\models\Category;
use yii\data\Pagination;

class AdminController extends Controller
{
    public function actionIndex()
    {
        #$oCountQuery = Time56::getAll();
        $oCountQuery = Time56::find()->orderBy(['id' => SORT_DESC ]);
        $oCategory = Category::find()->all();
        
        #$countQuery = clone $query;
        $oPages = new Pagination(['totalCount'=> $oCountQuery->count(), 'pageSize' => 100]);
        $oPages->pageSizeParam = false;
        $oModel = $oCountQuery->offset($oPages->offset)
                ->limit($oPages->limit)
                ->all();
        return $this->render('index', ['model'=>$oModel, 
                'model_2'=>$oCategory,
                'pages' => $oPages,
                ]);
    }
    public function actionEdit($id)
    {
        $oOne = Time56::getOne($id);
        if($oOne->load(Yii::$app->request->post()))
        {    
            
            $oOne->imageFile = UploadedFile::getInstance($oOne, 'imageFile');
            $oOne->imageFile->saveAs('photo/'.$oOne->imageFile->baseName.".".$oOne->imageFile->extension);
            $oOne->imageFile->tempName = 'photo/'.$oOne->imageFile->baseName.".".$oOne->imageFile->extension;
            $oOne->image_path = $oOne->imageFile->tempName;
            $oOne->created_at = date("Y-m-d H:i:s");
            if($oOne->validate() && $oOne->save())
            {
                return $this->redirect(['index']);
            }
        }
        return $this->render('edit',['one'=>$oOne]);
    }
    public function actionCreate()
    {
        $oModel = new Time56();
        if ($oModel->load(Yii::$app->request->post())) 
         {
            $oModel->imageFile = UploadedFile::getInstance($oModel, 'imageFile');
            $oModel->imageFile->saveAs('photo/'.$oModel->imageFile->baseName.".".$oModel->imageFile->extension);
            $oModel->imageFile->tempName = 'photo/'.$oModel->imageFile->baseName.".".$oModel->imageFile->extension;
            $oModel->image_path = $oModel->imageFile->tempName;
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

