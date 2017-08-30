<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\UploadForm;
 
class TestController extends Controller
{
    public $layout = 'photo';
    
   public function actionIndex()
   {
    
    $model = new UploadForm();
    
  if(Yii::$app->request->post())
  {
  $model->file = UploadedFile::getInstance($model, 'file');
    if ($model->validate()) {
     $path = Yii::$app->params['pathUploads'] . 'test/';
     $model->file->saveAs( $path . $model->file);
    }
    }
    return $this->render('index', ['model'=>$model]);
   } 
}