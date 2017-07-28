<?php

namespace app\models;

use yii\db\ActiveRecord;


class Time56 extends ActiveRecord
{	
        public function rules()
        {
            return [
                [['title','content'], 'required'],
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
	
}
?>