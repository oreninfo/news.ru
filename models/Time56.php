<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Time56 extends ActiveRecord
{	
     public $imageFile;
     //public $title;
     //public $content;
     //public $image_path;
    // public $id_category;
        public function rules()
        {
            return [
               [['title','content','image_path', 'id_category'], 'required'],
               [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
                [['tag_list'], 'safe'],
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
        public function getTags()
        {
            return $this->hasMany(Time56::className(), ['id' => 'news_id'])
                    ->viaTable('time56_category', ['category_id' => 'id']);
        }
	public function behaviors()
                {
            return [
                [
                    'class' => \app\components\behaviors\ManyHasManyBehavior::className(),
                    'relations' => [
                    'tags' => 'tag_list',                   
                        ],
                    ],
                ];
                    
                }
                
                }
?>