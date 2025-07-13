<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\DataTable $model */

$this->title = 'Create Data Table';
$this->params['breadcrumbs'][] = ['label' => 'Data Tables', 'url' => ['/']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-table-create">

    <?= $this->render('_form', [
        'model' => $model,
        'getRegions' => $getRegions
    ]) ?>

</div>