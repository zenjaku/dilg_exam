<?php

namespace frontend\controllers;

use common\models\DataTable;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class DataTableController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['view', 'update', 'create', 'delete', 'provinces', 'cities'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ]
            ]
        );
    }
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new DataTable();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $getRegions = DataTable::getAllRegions();

        return $this->render('create', [
            'model' => $model,
            'getRegions' => $getRegions,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = DataTable::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionProvinces($region_id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ArrayHelper::map(
            DataTable::dropdownProvinces($region_id),
            'province_c',
            'province_m'
        );
    }

    public function actionCities($province_id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ArrayHelper::map(
            DataTable::dropdownCityMun($province_id),
            'citymun_c',
            'citymun_m'
        );
    }
}
