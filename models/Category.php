<?php

namespace app\models;

use yii\db\ActiveRecord;


class Category extends ActiveRecord
{
	public static function tableName()
	{
		return 'category';
	}
	
	public static function getAll()
	{
		$data = self::find()->all();
		return $data;
	}
	
}
?>