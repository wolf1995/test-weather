<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "weather".
 *
 * @property string $id
 * @property string $city_id
 * @property string $maxTemp
 * @property string $minTemp
 *
 * @property City $city
 */
class Weather extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'weather';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'maxTemp', 'minTemp'], 'required'],
            [['city_id'], 'integer'],
            [['maxTemp', 'minTemp'], 'string', 'max' => 255],
            // [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'City ID',
            'maxTemp' => 'Max Temp',
            'minTemp' => 'Min Temp',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }
}
