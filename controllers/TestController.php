<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\UploadForm;
 
class TestController extends Controller
{
    
   public function actionUpload()
   {
    
    $model = new UploadForm();
    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        $model->file = UploadedFile::getInstance($model, 'file');
        $model->file->saveAs('photo/'.$model->file->baseName.$model->file->extension);
    }
  /*if(Yii::$app->request->post())
  {
  $model->file = UploadedFile::getInstance($model, 'file');
    if ($model->upload()) {
     $path = Yii::$app->params['pathUploads'] . 'test/';
     $model->file->saveAs( $path . $model->file);
    
        return;
    }
    }*/
    return $this->render('upload', ['model'=>$model]);
   } 
}