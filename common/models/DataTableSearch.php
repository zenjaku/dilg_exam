<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DataTable;

class DataTableSearch extends DataTable
{
    public $search;
    public $status;
    public $region;
    public $province;
    public $citymun;

    public function rules()
    {
        return [
            [['search'], 'safe'],
            [['status'], 'integer'],
            [['region', 'province', 'citymun'], 'string']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params, $formName = null)
    {
        $query = DataTable::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        if ($this->search !== null && $this->search !== '') {
            $query->filterWhere(['like', 'first_name', $this->search])
                ->andFilterWhere(['like', 'last_name', $this->search])
                ->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'contact_number', $this->contact_number]);
        }

        if ($this->status !== null && $this->status !== '') {
            $query->where(['status' => $this->status]);
        }

        if ($this->region !== null && $this->region !== '') {
            $query->where(['region_id' => $this->region]);
        }

        if ($this->province !== null && $this->province !== '') {
            $query->where(['province_id' => $this->province]);
        }
        if ($this->citymun !== null && $this->citymun !== '') {
            $query->where(['citymun_id' => $this->citymun]);
        }

        return $dataProvider;
    }
}