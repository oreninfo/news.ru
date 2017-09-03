<?php

namespace app\models;

use yii\db\ActiveRecord;


class Time56 extends ActiveRecord
{	
     public $imageFile;
        public function rules()
        {
            return [
                [['title','content','image_path', 'id_category'], 'required'],
               # [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            ];
        }
        public static function tableName()
	{
		return 'time56';
	}
        public static function getAll()
	{
		$data = self::find()->all();
		return $data;
	}
        public static function getOne($id)
	{
		$data = self::find()
                        ->where(['id'=>$id])
                        ->one();
		return $data;
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
?>