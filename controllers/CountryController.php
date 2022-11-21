<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Country;
use app\models\CountryForm;
use app\models\LaknatForm;
use yii\data\Pagination;


class CountryController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = Country::find();

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);

        $countries = $query->orderBy('name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'countries' => $countries,
            'pagination' => $pagination,
        ]);
    }

    public function actionAdd(){
        $model = new CountryForm();
        
        if($model->load(Yii::$app->request->post()) && $model->validate()){

            $countryExist = Country::findOne($model->code);
            if($countryExist){
                return $this->render('add-error', ['message' => "Data sudah ada"]);
            }
            // mengambil negara yang memiliki primary key "US"
            $country = new Country;

            // Mengganti nama negara menjadi "U.S.A." dan menyimpan ke database
            $country->name = $model->name;
            $country->code = $model->code;
            $country->population = $model->population;
            $country->save();
            return $this->render('add-success', ['model' => $model]);
        }else{
            return $this->render('add', ['model' => $model]);
        }    
    }


}
