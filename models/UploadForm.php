<?php


namespace app\models;

use yii\base\Model;


class UploadForm extends Model 
{
    public $file;
    
    public function rules()
    {
         return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg']
        ];
    }
        public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    } 
   
}
