<?php

namespace app\models;

use yii\db\ActiveRecord;


class Time56Category extends ActiveRecord
{
	public static function tableName()
	{
		return 'time56_category';
	}
	
	public static function getAll()
	{
		$data = self::find()->all();
		return $data;
	}
	
}
?>