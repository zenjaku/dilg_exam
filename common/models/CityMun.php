<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tblcitymun".
 *
 * @property string $region_c
 * @property string $province_c
 * @property string $district_c
 * @property string $citymun_c
 * @property string $citymun_m
 * @property string $lgu_type
 * @property string $income
 */
class CityMun extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblcitymun';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['region_c', 'province_c', 'district_c', 'citymun_c', 'citymun_m', 'lgu_type', 'income'], 'required'],
            [['region_c', 'province_c', 'citymun_c'], 'string', 'max' => 2],
            [['district_c', 'lgu_type'], 'string', 'max' => 3],
            [['citymun_m'], 'string', 'max' => 200],
            [['income'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'region_c' => 'Region C',
            'province_c' => 'Province C',
            'district_c' => 'District C',
            'citymun_c' => 'Citymun C',
            'citymun_m' => 'Citymun M',
            'lgu_type' => 'Lgu Type',
            'income' => 'Income',
        ];
    }

}
