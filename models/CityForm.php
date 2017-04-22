<?php
namespace app\models;

use Yii;
use yii\base\Model;

class CityForm extends Model{

  public $city;
  public $country;
  public $startDate;
  public $endDate;
  public $hour=1;

  public $apiKey = 'c9a5d6f539284a139ea13626172004';
  public $format = 'json';
  public $extra = 'isDayTime';

  public function rules(){
    return [
      [['city', 'country', 'startDate','endDate'], 'required', 'message'=>'Поле обязательно для заполнения'],
      [['city', 'country'], 'string'],
      [['startDate', 'endDate'], 'date','format'=>'php:Y-m-d'],
      [['hour'], 'integer'],
    ];
  }

  public function getUrl($data){
    return 'http://api.worldweatheronline.com/premium/v1/past-weather.ashx?key='.$data['apiKey'].'&q='.$data['city'].','.$data['country'].'&format='.$data['format'].'&extra='.$data['extra'].'&date='.$data['startDate'].'&enddate='.$data['endDate'].'&tp='.$data['hour'];
  }

  public function getJSon($url){
    $data = json_decode(file_get_contents($url));
    return $data;
  }

  public function getCity($data){
    return $data->data->request['0']->query;
  }

  public function getWeather($data){
    return $data->data->weather;
  }

  public function saveCity($cityForm){
    $city = new City;
    $city->cityName = $cityForm;
    $city->save();
    return $city;
  }

  public function saveWeatherInNewCity($weather, $city){
    $weathers = new Weather;
    if (isset($city['0'])){
      $weathers->city_id = $city['0']->id;
    }else{
      $weathers->city_id = $city->id;
    }

    $weathers->date = $weather->date;
    $weathers->maxTemp = $weather->maxtempC;
    $weathers->minTemp = $weather->mintempC;

    $weathers->save();

    return $weathers;
  }

  public function saveWitherInNewBetween($weather, $city){
    $isWeatherInBD = Weather::find()->where('city_id=:city_id',[':city_id'=>$city['0']->id])->andWhere('date=:date', [':date'=>$weather->date])->all();
    if (empty($isWeatherInBD)) {
      $weathers = new Weather;
      $weathers->city_id = $city['0']->id;
      $weathers->date = $weather->date;
      $weathers->maxTemp = $weather->maxtempC;
      $weathers->minTemp = $weather->mintempC;
      $weathers->save();
      return $weathers;
    }

  }

}



 ?>
