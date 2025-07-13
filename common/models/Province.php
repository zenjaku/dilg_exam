<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tblprovince".
 *
 * @property string $region_c
 * @property string $province_c
 * @property string $province_m
 * @property string $income
 */
class Province extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblprovince';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['region_c', 'province_c', 'province_m', 'income'], 'required'],
            [['region_c', 'province_c'], 'string', 'max' => 2],
            [['province_m'], 'string', 'max' => 200],
            [['income'], 'string', 'max' => 20],
            [['province_c'], 'unique'],
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
            'province_m' => 'Province M',
            'income' => 'Income',
        ];
    }

}
