<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "data_table".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property int $age
 * @property string $contact_number
 * @property string $status
 * @property string $street
 * @property string $barangay
 * @property string $zipcode
 * @property int $region_id
 * @property int $province_id
 * @property int $citymun_id
 * @property int $created_at
 * @property int $updated_at
 */
class DataTable extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
    public static function tableName()
    {
        return 'data_table';
    }

    public function rules()
    {
        return [
            [['first_name', 'last_name', 'age', 'contact_number', 'status', 'street', 'barangay', 'zipcode', 'region_id', 'province_id', 'citymun_id'], 'required'],
            [['age', 'created_at', 'updated_at'], 'integer'],
            [['first_name', 'last_name', 'status', 'street', 'barangay', 'zipcode', 'region_id', 'province_id', 'citymun_id',], 'string', 'max' => 255],
            [['contact_number'], 'string', 'max' => 20],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'age' => 'Age',
            'contact_number' => 'Contact Number',
            'status' => 'Status',
            'street' => 'Street',
            'barangay' => 'Barangay',
            'zipcode' => 'Zip Code',
            'region_id' => 'Region ID',
            'province_id' => 'Province ID',
            'citymun_id' => 'Citymun ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getAllRegions()
    {
        $dbRegions = Region::find()
            ->where(['!=', 'region_m', '-'])
            ->all();
        return ArrayHelper::map($dbRegions, 'region_c', 'region_m');
    }

    public static function getDbRegion()
    {
        $dbRegionIds = DataTable::find()
            ->select('region_id')
            ->distinct()
            ->column();

        $regions = Region::find()
            ->select(['region_c', 'region_m'])
            ->where(['region_c' => $dbRegionIds])
            ->asArray()
            ->all();

        return ArrayHelper::map($regions, 'region_c', 'region_m');
    }

    public static function getDbProvince()
    {
        $dbprovinceIds = DataTable::find()
            ->select('province_id')
            ->distinct()
            ->column();

        $provinces = Province::find()
            ->select(['province_c', 'province_m'])
            ->where(['province_c' => $dbprovinceIds])
            ->asArray()
            ->all();

        return ArrayHelper::map($provinces, 'province_c', 'province_m');
    }
    public static function getDbCityMun()
    {
        $citymuns = (new Query())
            ->select(['c.citymun_c', 'c.province_c', 'c.citymun_m'])
            ->from('tblcitymun c')
            ->innerJoin(
                [
                    'd' => (new Query())
                        ->select(['citymun_id', 'province_id'])
                        ->from('data_table')
                        ->distinct()
                ],
                'c.citymun_c = d.citymun_id AND c.province_c = d.province_id'
            )
            ->orderBy(['c.citymun_m' => SORT_ASC])
            ->all();

        return ArrayHelper::map(
            $citymuns,
            'citymun_c',
            'citymun_m'
        );
    }

    public static function getAllStatuses()
    {
        $dbStatuses = DataTable::find()
            ->select('status')
            ->distinct()
            ->column();

        $labels = [
            1 => 'Under Investigation',
            2 => 'Surrendered',
            3 => 'Apprehended',
            4 => 'Escaped',
            5 => 'Deceased',
        ];

        $result = [];
        foreach ($dbStatuses as $status) {
            if (isset($labels[$status])) {
                $result[$status] = $labels[$status];
            }
        }
        return $result;
    }

    public function getRegion()
    {
        return $this->hasOne(Region::class, ['region_c' => 'region_id']);
    }

    public function getProvince()
    {
        return $this->hasOne(Province::class, ['province_c' => 'province_id']);
    }

    public function getCorrectCitymunName()
    {
        return CityMun::find()
            ->select('citymun_m')
            ->where([
                'citymun_c' => $this->citymun_id,
                'province_c' => $this->province_id,
            ])
            ->scalar();
    }

    public static function dropdownProvinces($region_id)
    {
        return Province::find()
            ->select(['province_c', 'province_m'])
            ->where(['region_c' => $region_id])
            ->orderBy('province_m')
            ->all();
    }

    public static function dropdownCityMun($province_id)
    {
        return CityMun::find()
            ->select(['citymun_c', 'citymun_m'])
            ->where(['province_c' => $province_id])
            ->orderBy('citymun_m')
            ->all();
    }

}
