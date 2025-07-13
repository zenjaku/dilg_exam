<?php

/** @var yii\web\View $this */
use common\models\DataTable;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Url;

$this->registerJsVar('statusChartUrl', Url::to(['site/status-chart']));
$this->registerJsFile('@web/js/chart.js', [
    'depends' => \yii\web\JqueryAsset::class,
]);
?>
<div class="site-index">
    <div class="row my-3 justify-content-between">
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
                        ['' => 'All Statuses'] + $statuses,
                        ['id' => 'status-filter', 'class' => 'form-control']
                    ),
                ['class' => 'd-flex flex-lg-row flex-column gap-2 mb-2']

            )
                ?>
        </div>

        <div class="col-md-6">
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
                ['class' => 'd-flex flex-lg-row flex-column gap-2']
            ) ?>

        </div>
    </div>

    <?php Pjax::begin([
        'id' => 'post-grid',
        'clientOptions' => [
            'container' => '#post-grid',
        ],
    ]); ?>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'emptyText' => '<div class="text-center p-4">No data found</div>',
            'emptyTextOptions' => ['class' => 'text-center p-4'],
            'options' => [
                'id' => 'grid-table',
            ],
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

            $(document).on('pjax:send', function(event, xhr, options) {
                var \$gridTable = $('#grid-table');
                var \$tbody = \$gridTable.find('tbody');
                
                \$tbody.find('.pjax-loading-row').remove();
                
                var colCount = \$gridTable.find('thead th').length;
                \$tbody.append(
                    '<tr class="pjax-loading-row">' +
                    '<td colspan="' + colCount + '" class="text-center p-4">' +
                    '<div class="spinner-border text-primary" role="status">' +
                    '<span class="visually-hidden">Loading...</span>' +
                    '</div>' +
                    '</td>' +
                    '</tr>'
                );
            });

            $(document).on('pjax:complete', function() {
                $('#grid-table tbody').find('.pjax-loading-row').remove();
            });
        JS;

    $this->registerJs($js);

    ?>

</div>