<?php

/** @var yii\web\View $this */
use common\models\DataTable;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Url;
?>
<div class="site-index">
    <div class="row mb-3 justify-content-between">
        <div class="col-md-5">
            <?= Html::tag(
                'div',
                Html::textInput('DataTableSearch[search]', $searchModel->search, [
                    'id' => 'search-box',
                    'class' => 'form-control',
                    'placeholder' => 'Type to search…',
                ]) . Html::dropDownList(
                        'DataTableSearch[status]',
                        $searchModel->status,
                        ['' => 'All Statuses'] + $statuses, // Add empty option
                        ['id' => 'status-filter', 'class' => 'form-control']
                ),
                ['class' => 'd-flex gap-2']

            )
                ?>
        </div>

        <div class="col-md-6 col-sm-12">
            <?= Html::tag(
                'div',
                Html::dropDownList(
                    'DataTableSearch[region]',
                    $searchModel->region,
                    ['' => 'All Regions'] + $regions,
                    ['id' => 'region-filter', 'class' => 'form-control']
                ) .
                Html::dropDownList(
                    'DataTableSearch[province]',
                    $searchModel->province,
                    ['' => 'All Provinces'] + $provinces,
                    ['id' => 'province-filter', 'class' => 'form-control']
                ) .
                Html::dropDownList(
                    'DataTableSearch[citymun]',
                    $searchModel->citymun,
                    ['' => 'All City/Municipalities'] + $citymuns,
                    ['id' => 'citymun-filter', 'class' => 'form-control']
                ),
                ['class' => 'd-flex gap-2']
            ) ?>

        </div>
        <!-- 
        <div class="col-md-3">
            <?= Html::dropDownList(
                'DataTableSearch[region]',
                $searchModel->region,
                ['' => 'All Regions'] + $regions,
                ['id' => 'region-filter', 'class' => 'form-control']
            ) ?>
        </div>

        <div class="col-md-3">
            <?= Html::dropDownList(
                'DataTableSearch[province]',
                $searchModel->province,
                ['' => 'All Provinces'] + $provinces,
                ['id' => 'province-filter', 'class' => 'form-control']
            ) ?>
        </div>

        <div class="col-md-3">
            <?= Html::dropDownList(
                'DataTableSearch[citymun]',
                $searchModel->citymun,
                ['' => 'All City/Municipalities'] + $citymuns,
                ['id' => 'citymun-filter', 'class' => 'form-control']
            ) ?>
        </div> -->
    </div>

    <?php Pjax::begin(['id' => 'post-grid']); ?>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                [
                    'attribute' => 'first_name',
                    'label' => 'Name',
                    'contentOptions' => [
                        'class' => 'text-nowrap'
                    ],
                    'value' => function ($model) {
                    return "$model->first_name $model->last_name";
                },
                ],
                'age',
                'contact_number',
                [
                    'attribute' => 'street',
                    'label' => 'Address',
                    'contentOptions' => [
                        'class' => 'address'
                    ],
                    'value' => function ($model) {
                    $address = [];
                    if ($model->street && $model->barangay)
                        $address[] = trim("$model->street  $model->barangay");

                    if ($model->citymun_id && $model->province_id) {
                        $citymunName = $model->getCorrectCitymunName();
                        if ($citymunName) {
                            $address[] = $citymunName;
                        }
                    }

                    if ($model->province_id)
                        $address[] = $model->province->province_m;

                    if ($model->region_id)
                        $address[] = $model->region->region_m;

                    return implode(', ', $address);
                }
                ],
                [
                    'attribute' => 'status',
                    'contentOptions' => [
                        'class' => 'text-nowrap'
                    ],
                    'value' => function ($model) {
                    $statusLabel = $model->getAllStatuses();
                    return $statusLabel[$model->status] ?? $model->status;
                }
                ],
                [
                    'attribute' => 'created_at',
                    'contentOptions' => [
                        'class' => 'text-nowrap'
                    ],
                    'format' => ['date', 'php:F d, Y']
                ]
            ],
            'pager' => [
                'class' => LinkPager::class,
                'prevPageLabel' => '← Prev',
                'nextPageLabel' => 'Next →',
                'maxButtonCount' => 5,
                'options' => [
                    'class' => 'd-flex justify-content-end'
                ]
            ]
        ]) ?>
    </div>

    <?php Pjax::end(); ?>

    <?php
    /* ---------- Tiny bit of jQuery ---------- */
    $reloadUrl = Url::to(['/']);
    $js = <<<JS
            let typingTimer;
            $('#search-box').on('keyup', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(pjaxReload, 400);
            });

            $('#status-filter').on('change', pjaxReload);
            $('#region-filter').on('change', pjaxReload);
            $('#province-filter').on('change', pjaxReload);
            $('#citymun-filter').on('change', pjaxReload);

            function pjaxReload() {
                $('#post-grid').append('<div class="pjax-loading">Loading...</div>');

                const searchVal   = $('#search-box').val().trim();
                const statusVal   = $('#status-filter').val();
                const regionVal   = $('#region-filter').val();
                const provinceVal = $('#province-filter').val();
                const citymunVal = $('#citymun-filter').val();

                let requestData = {};

                if (searchVal)   requestData['DataTableSearch[search]']     = searchVal;
                if (statusVal)   requestData['DataTableSearch[status]']     = statusVal;
                if (regionVal)   requestData['DataTableSearch[region]']  = regionVal;
                if (provinceVal) requestData['DataTableSearch[province]'] = provinceVal;
                if (citymunVal) requestData['DataTableSearch[citymun]'] = citymunVal;

                $.pjax.reload({
                    container: '#post-grid',
                    url: '$reloadUrl',
                    data: requestData,
                    push: false,
                    replace: true,
                    type: 'GET'
                });
            }
        JS;

    $this->registerJs($js);

    ?>

</div>