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
    
  if(Yii::$app->request->post())
  {
  $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
    if ($model->upload()) {
     $path = Yii::$app->params['pathUploads'] . 'test/';
     $model->file->saveAs( $path . $model->file);
    
        return;
    }
    }
    return $this->render('upload', ['model'=>$model]);
   } 
}