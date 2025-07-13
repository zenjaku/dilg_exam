<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\DataTable $model */

$this->title = "$model->first_name $model->last_name";
$this->params['breadcrumbs'][] = ['label' => 'Data Tables', 'url' => ['/']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="data-table-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'first_name',
            'last_name',
            'age',
            'contact_number',
            'status',
            'street',
            'barangay',
            'zipcode',
            'region_id',
            'province_id',
            'citymun_id',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>