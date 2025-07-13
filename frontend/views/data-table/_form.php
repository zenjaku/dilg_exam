<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\DataTable $model */
/** @var yii\widgets\ActiveForm $form */
?>
<div class="card mx-auto my-4 shadow" style="max-width: 800px;">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Insert new data</h5>
    </div>

    <div class="card-body">

        <?php $form = ActiveForm::begin(); ?>

        <?= Html::tag(
            'div',
            Html::tag(
                'div',
                $form->field($model, 'first_name')
                    ->textInput(['maxlength' => true, 'class' => 'form-control']),
                ['class' => 'flex-grow-1']
            ) .
            Html::tag(
                'div',
                $form->field($model, 'last_name')
                    ->textInput(['maxlength' => true, 'class' => 'form-control']),
                ['class' => 'flex-grow-1']
            ),
            ['class' => 'd-flex flex-lg-row flex-column gap-3 mb-3']
        ) ?>

        <?= Html::tag(
            'div',
            Html::tag(
                'div',
                $form->field($model, 'age')
                    ->textInput(['type' => 'number', 'maxlength' => true, 'class' => 'form-control']),
                ['class' => 'flex-grow-1']
            ) .
            Html::tag(
                'div',
                $form->field($model, 'contact_number')
                    ->textInput(['type' => 'number', 'maxlength' => true, 'class' => 'form-control']),
                ['class' => 'flex-grow-1']
            ),
            ['class' => 'd-flex flex-lg-row flex-column gap-3 mb-3']
        ) ?>

        <?= $form->field($model, 'status')->dropDownList(
            [
                '' => 'Select Status',
                '1' => 'Under Investigation',
                '2' => 'Surrendered',
                '3' => 'Apprehended',
                '4' => 'Escaped',
                '5' => 'Deceased',
            ],
            ['class' => 'form-control mb-3']
        ) ?>

        <?= Html::tag(
            'div',
            Html::tag(
                'div',
                $form->field($model, 'street')
                    ->textInput(['maxlength' => true, 'class' => 'form-control']),
                ['class' => 'flex-grow-1']
            ) .
            Html::tag(
                'div',
                $form->field($model, 'barangay')
                    ->textInput(['maxlength' => true, 'class' => 'form-control']),
                ['class' => 'flex-grow-1']
            ),
            ['class' => 'd-flex flex-lg-row flex-column gap-3 mb-3']
        ) ?>

        <?= Html::tag(
            'div',
            $form->field($model, 'region_id', ['options' => ['class' => 'flex-grow-1']])
                ->dropDownList(
                    ['' => 'Select Region'] + $getRegions,
                    ['id' => 'region', 'class' => 'form-control']
                )->label(false) .
            $form->field($model, 'province_id', ['options' => ['class' => 'flex-grow-1']])
                ->dropDownList(
                    ['' => 'Select Province'],
                    ['id' => 'province', 'class' => 'form-control', 'disabled' => true]
                )->label(false) .
            $form->field($model, 'citymun_id', ['options' => ['class' => 'flex-grow-1']])
                ->dropDownList(
                    ['' => 'Select City/Municipality'],
                    ['id' => 'citymun', 'class' => 'form-control', 'disabled' => true]
                )->label(false),
            ['class' => 'd-flex flex-lg-row flex-column gap-3 mb-3']
        ) ?>

        <?= $form->field($model, 'zipcode')->textInput(['type' => 'number', 'maxlength' => true, 'class' => 'form-control mb-3']) ?>

        <div class="form-group text-end">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
<?php
$provincesUrl = Url::to(['provinces']);
$citiesUrl = Url::to(['cities']);

$js = <<<JS
        $('#region').on('change', function () {
            const id = $(this).val();
            resetSelect('#province', 'Select Province');
            resetSelect('#citymun',  'Select City/Municipality');

            if (!id) return;
            $.getJSON('$provincesUrl', {region_id: id}, function (data) {
                populate('#province', data);
            });
        });

        $('#province').on('change', function () {
            const id = $(this).val();
            resetSelect('#citymun', 'Select City/Municipality');

            if (!id) return;
            $.getJSON('$citiesUrl', {province_id: id}, function (data) {
                populate('#citymun', data);
            });
        });

        function resetSelect(sel, placeholder) {
            $(sel).html('<option value="">' + placeholder + '</option>').prop('disabled', true);
        }

        function populate(sel, items) {
            const opts = Object.keys(items).map(function(k) {
                return '<option value="' + k + '">' + items[k] + '</option>';
            });
            $(sel).append(opts.join('')).prop('disabled', false);
        }
    JS;

$this->registerJs($js);
?>