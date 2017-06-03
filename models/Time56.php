<?php

namespace app\models;

use yii\db\ActiveRecord;


class Time56 extends ActiveRecord
{
	public static function tableName()
	{
		return 'time56';
	}
	
	public static function getAll()
	{
		$data = self::find()->all();
		return $data;
	}
	
}
?>