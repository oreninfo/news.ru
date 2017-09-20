<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\models\Time56;

class Time56Controller extends Controller
{
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
			'page' => [
			           'class' => 'yii\web\ViewAction',
					   ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {   $query = Time56::find();
	    $pagination = new Pagination([
		   'defaultPageSize' => 5,
		   'totalCount' => $query->count(),
		 ]);
		 $titles = $query->orderBy(['created_at' => SORT_DESC])
		 ->offset($pagination->offset)
		 ->limit($pagination->limit)
		 ->all();
        return $this->render('index', [
			   'active_page' => Yii::$app->request->get("page", 1),
			   'count_pages' => $pagination->getPageCount(),
			   'titles' => $titles,
			   'pagination' => $pagination,
			   
		]);
    }
	
	public function actionObshestvo()
    {   $query = Time56::find()->where(['id_category' => 1]);
	    $pagination = new Pagination([
		   'defaultPageSize' => 5,
		   'totalCount' => $query->count(),
		 ]);
		 $titles = $query->orderBy(['created_at' => SORT_DESC])
		 ->offset($pagination->offset)
		 ->limit($pagination->limit)
		 ->all();
        return $this->render('obshestvo', [
		       
			   'active_page' => Yii::$app->request->get("page", 1),
			   'count_pages' => $pagination->getPageCount(),
			   'titles' => $titles,
			   'pagination' => $pagination,
			   
		]);
    }
    
	public function actionProishestviya()
    {    $query = Time56::find()->where(['id_category' => 2]);
	    $pagination = new Pagination([
		   'defaultPageSize' => 5,
		   'totalCount' => $query->count(),
		 ]);
		 $titles = $query->orderBy(['created_at' => SORT_DESC])
		 ->offset($pagination->offset)
		 ->limit($pagination->limit)
		 ->all();
        return $this->render('proishestviya', [
		      
			   'active_page' => Yii::$app->request->get("page", 1),
			   'count_pages' => $pagination->getPageCount(),
			   'titles' => $titles,
			   'pagination' => $pagination,
			   
		]);
    }
	
    public function actionVlast()
    {   $query = Time56::find()->where(['id_category' => 3]);
	    $pagination = new Pagination([
		   'defaultPageSize' => 5,
		   'totalCount' => $query->count(),
		 ]);
		 $titles = $query->orderBy(['created_at' => SORT_DESC])
		 ->offset($pagination->offset)
		 ->limit($pagination->limit)
		 ->all();
        return $this->render('vlast', [
		       
			   'active_page' => Yii::$app->request->get("page", 1),
			   'count_pages' => $pagination->getPageCount(),
			   'titles' => $titles,
			   'pagination' => $pagination,
			   
		]);
    }
	
	 public function actionKultura()
    {   $query = Time56::find()->where(['id_category' => 4]);
	    $pagination = new Pagination([
		   'defaultPageSize' => 5,
		   'totalCount' => $query->count(),
		 ]);
		 $titles = $query->orderBy(['created_at' => SORT_DESC])
		 ->offset($pagination->offset)
		 ->limit($pagination->limit)
		 ->all();
        return $this->render('kultura', [
		       
			   'active_page' => Yii::$app->request->get("page", 1),
			   'count_pages' => $pagination->getPageCount(),
			   'titles' => $titles,
			   'pagination' => $pagination,
			   
		]);
    }
	public function actionEconomy()
    {   $query = Time56::find()->where(['id_category' => 5]);
	    $pagination = new Pagination([
		   'defaultPageSize' => 5,
		   'totalCount' => $query->count(),
		 ]);
		 $titles = $query->orderBy(['created_at' => SORT_DESC])
		 ->offset($pagination->offset)
		 ->limit($pagination->limit)
		 ->all();
        return $this->render('economy', [
		      
			   'active_page' => Yii::$app->request->get("page", 1),
			   'count_pages' => $pagination->getPageCount(),
			   'titles' => $titles,
			   'pagination' => $pagination,
			   
		]);
		
    }
     public function actionSport()
    {   $query = Time56::find()->where(['id_category' => 6]);
	    $pagination = new Pagination([
		   'defaultPageSize' => 5,
		   'totalCount' => $query->count(),
		 ]);
		 $titles = $query->orderBy(['created_at' => SORT_DESC])
		 ->offset($pagination->offset)
		 ->limit($pagination->limit)
		 ->all();
        return $this->render('sport', [
		       
			   'active_page' => Yii::$app->request->get("page", 1),
			   'count_pages' => $pagination->getPageCount(),
			   'titles' => $titles,
			   'pagination' => $pagination,
			   
		]);
		
    }
    public function actionEducation()
    {   $query = Time56::find()->where(['id_category' => 7]);
	    $pagination = new Pagination([
		   'defaultPageSize' => 5,
		   'totalCount' => $query->count(),
		 ]);
		 $titles = $query->orderBy(['created_at' => SORT_DESC])
		 ->offset($pagination->offset)
		 ->limit($pagination->limit)
		 ->all();
        return $this->render('education', [
		      
			   'active_page' => Yii::$app->request->get("page", 1),
			   'count_pages' => $pagination->getPageCount(),
			   'titles' => $titles,
			   'pagination' => $pagination,
			   
		]);
		
    }
	public function actionHealth()
    {   $query = Time56::find()->where(['id_category' => 8]);
	    $pagination = new Pagination([
		   'defaultPageSize' => 5,
		   'totalCount' => $query->count(),
		 ]);
		 $titles = $query->orderBy(['created_at' => SORT_DESC])
		 ->offset($pagination->offset)
		 ->limit($pagination->limit)
		 ->all();
        return $this->render('health', [
		       
			   'active_page' => Yii::$app->request->get("page", 1),
			   'count_pages' => $pagination->getPageCount(),
			   'titles' => $titles,
			   'pagination' => $pagination,
			   
		]);
		
    }
	public function actionWorld()
    {   $query = Time56::find()->where(['id_category' => 9]);
	    $pagination = new Pagination([
		   'defaultPageSize' => 5,
		   'totalCount' => $query->count(),
		 ]);
		 $titles = $query->orderBy(['created_at' => SORT_DESC])
		 ->offset($pagination->offset)
		 ->limit($pagination->limit)
		 ->all();
        return $this->render('world', [
		       
			   'active_page' => Yii::$app->request->get("page", 1),
			   'count_pages' => $pagination->getPageCount(),
			   'titles' => $titles,
			   'pagination' => $pagination,
			   
		]);
		
    }
	public function actionRussia()
    {   $query = Time56::find()->where(['id_category' => 10]);
	    $pagination = new Pagination([
		   'defaultPageSize' => 5,
		   'totalCount' => $query->count(),
		 ]);
		 $titles = $query->orderBy(['created_at' => SORT_DESC])
		 ->offset($pagination->offset)
		 ->limit($pagination->limit)
		 ->all();
        return $this->render('russia', [
		       
			   'active_page' => Yii::$app->request->get("page", 1),
			   'count_pages' => $pagination->getPageCount(),
			   'titles' => $titles,
			   'pagination' => $pagination,
			   
		]);
		
    }
	public function actionVideo()
    {   $query = Time56::find()->where(['id_category' => 11]);
	    $pagination = new Pagination([
		   'defaultPageSize' => 5,
		   'totalCount' => $query->count(),
		 ]);
		 $titles = $query->orderBy(['created_at' => SORT_DESC])
		 ->offset($pagination->offset)
		 ->limit($pagination->limit)
		 ->all();
        return $this->render('video', [
		      
			   'active_page' => Yii::$app->request->get("page", 1),
			   'count_pages' => $pagination->getPageCount(),
			   'titles' => $titles,
			   'pagination' => $pagination,
			   
		]);
		
    }
	public function actionTV()
    {   $query = Time56::find()->where(['id_category' => 12]);
	    $pagination = new Pagination([
		   'defaultPageSize' => 5,
		   'totalCount' => $query->count(),
		 ]);
		 $titles = $query->orderBy(['created_at' => SORT_DESC])
		 ->offset($pagination->offset)
		 ->limit($pagination->limit)
		 ->all();
        return $this->render('TV', [
		       
			   'active_page' => Yii::$app->request->get("page", 1),
			   'count_pages' => $pagination->getPageCount(),
			   'titles' => $titles,
			   'pagination' => $pagination,
			   
		]);
		
    }
    
    public function actionThis($id)
    {
        $oModel = Time56::getOne($id);
        return $this->render('this',['model'=>$oModel]);
    }
	public function actionTime()
    {   
        return $this->render('time-date', ['response' => date('H:i:s')]);
    }
	
	public function actionDate()
    {   
        return $this->render('time-date', ['response' => date('d:m:Y')]);
    }
    
	
}
?>