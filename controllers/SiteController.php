<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;


use app\models\City;
use app\models\CityForm;
use app\models\Weather;

class SiteController extends Controller
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
                    'logout' => ['post', 'get'],
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionTest(){
      return $this->render('table');
    }
    public function actionCity(){

      $modelCity = new CityForm;

      if (!empty(Yii::$app->request->isPost)){
        //данные для работы
        $dataForm = Yii::$app->request->post('CityForm');
        $startDate = $dataForm['startDate'];
        $endDate = $dataForm['endDate'];
        $url = CityForm::getUrl($dataForm);
        $data = CityForm::getJSon($url);
        $cityForm = CityForm::getCity($data);
        $arrWeatherNewCity = [];
        //ищем город в бд. Если нету, то заносим в бд и сохраняем для него погоду в выбраном диапозоне
        $city = City::find()->where('cityName=:cityForm',[':cityForm'=>$cityForm])->all();
        if (empty($city)) {
          $city = CityForm::saveCity($cityForm);

          $weathers = CityForm::getWeather($data);
          //заглушка, если введены некорректные данные
          if (empty($weathers)) {
            return $this->render('errorWeather');
          }

          foreach ($weathers as $weather) {
            $saveWeather = CityForm::saveWeatherInNewCity($weather, $city);
            array_push($arrWeatherNewCity, $saveWeather);
          }
          return $this->render('table', compact('city', 'arrWeatherNewCity'));
        }

        //проверяем на наличие начальной и конечной дат в бд, если есть, то выводим. Если нет - сохранем новые даты из заданого диапозона и выводим.
        $isStartDate = Weather::find()->where('city_id=:city_id',[':city_id'=>$city['0']->id])->andWhere('date=:startDate',[':startDate'=>$startDate])->all();
        $isEndDate = Weather::find()->where('city_id=:city_id',[':city_id'=>$city['0']->id])->andWhere('date=:endDate',[':endDate'=>$endDate])->all();
        // var_dump($isEndDate);
        if (!empty($isStartDate) && !empty($isEndDate)) {
          $weathers = Weather::find()->where('city_id=:city_id',[':city_id'=>$city['0']->id])->andWhere(['between', 'date', $startDate, $endDate])->all();
          return $this->render('table', compact('city', 'weathers'));
        }else{
          // echo 1;
          $weathers = CityForm::getWeather($data);
          foreach ($weathers as $weather) {
            $saveWeather = CityForm::saveWitherInNewBetween($weather, $city);
            array_push($arrWeatherNewCity, $saveWeather);
          }
          return $this->render('table', compact('city', 'arrWeatherNewCity'));
        }
      }

      return $this->render('city', compact('modelCity', 'city'));
    }
}
