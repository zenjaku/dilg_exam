<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\web\JqueryAsset;
use yii\web\View;

AppAsset::register($this);
$this->registerCssFile('@web/css/site.css');
$this->registerJsFile('@web/js/script.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('https://kit.fontawesome.com/e81967d7b9.js', ['position' => View::POS_HEAD, 'crossorigin' => 'anonymous']);
// $this->registerJsFile('https://cdnjs.com/libraries/Chart.js', [
//     'position' => View::POS_HEAD,
//     'depends' => [JqueryAsset::class]
// ]);
function isActive($route)
{
    $controller = Yii::$app->controller->id;
    $action = Yii::$app->controller->action->id;

    // For simple routes like '/site/index'
    if ($route === "/$controller/$action") {
        return true;
    }

    // For routes like '/data-table/create'
    $currentRoute = Yii::$app->request->pathInfo;
    return $route === $currentRoute || $route === "/$currentRoute";
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= Html::encode(Yii::getAlias('@web/assets/p_1.png')) ?>" type="image/x-icon">
    <title><?= Html::encode($this->title ? "DILG System - $this->title" : 'DILG System') ?></title>
    <?php $this->head() ?>
</head>

<body class="container-fluid">
    <?php $this->beginBody() ?>
    <?= Alert::widget() ?>
    <div class="wrapper">
        <aside id="sidebar" class="bg-dark">
            <div class="d-flex">
                <?= Html::button(
                    Html::img('@web/assets/p_1.png', ['alt' => 'DILG Logo', 'class' => 'img-fluid dilg_logo']),
                    ['class' => 'toggle_btn', 'encode' => false]
                ) ?>
                <?= Html::tag(
                    'div',
                    Html::a('DILG System', ['/']),
                    ['class' => 'sidebar_logo']
                ) ?>
            </div>
            <ul class="sidebar_nav d-flex flex-column gap-3">
                <?php if (!Yii::$app->user->isGuest): ?>
                    <?= Html::tag(
                        'li',
                        Html::a('<i class="fa-solid fa-database"></i><span>Report</span>', ['/'], [
                            'class' => 'sidebar_link' . (isActive('/') ? ' active' : ''),
                            'encode' => false,
                            'title' => 'Report'
                        ]),
                        ['class' => 'sidebar_item' . (isActive('/') ? ' active' : '')]
                    ) ?>

                    <?= Html::tag(
                        'li',
                        Html::a('<i class="fa-solid fa-users"></i><span>Register</span>', ['/data-table/create'], [
                            'class' => 'sidebar_link' . (isActive('/data-table/create') ? ' active' : ''),
                            'encode' => false,
                            'title' => 'Add Drug Personality'
                        ]),
                        ['class' => 'sidebar_item' . (isActive('/data-table/create') ? ' active' : '')]
                    ) ?>
                <?php endif; ?>
            </ul>
            <div class="sidebar_footer d-flex flex-column gap-2">
                <?php if (!Yii::$app->user->isGuest): ?>
                    <?= Html::tag(
                        'li',
                        Html::a('<i class="fa-solid fa-arrow-right-from-bracket arrow"></i><span>Logout</span>', ['#'], [
                            'class' => 'sidebar_link',
                            'encode' => false,
                            'id' => 'logoutBtn',
                        ]),
                        ['class' => 'sidebar_item']
                    ) ?>
                <?php else: ?>
                    <?= Html::tag(
                        'li',
                        Html::a('<i class="fa-solid fa-user-lock"></i><span>Login</span>', ['/site/login'], [
                            'class' => 'sidebar_link' . (isActive('/site/login') ? ' active' : ''),
                            'encode' => false,
                            'title' => 'Login'
                        ]),
                        ['class' => 'sidebar_item']
                    ) ?>
                <?php endif; ?>
            </div>
        </aside>
        <div class="main" id="main">
            <?= Html::tag(
                'div',
                Html::tag(
                    'h1',
                    'DILG Monitoring System',
                    ['class' => 'text-uppercase px-5 header-h1']
                ),
                ['class' => 'd-flex justify-content-between ps-5 bg-dark text-white py-3 header']
            ) ?>
            <div class="container">
                <?= Html::tag(
                    'div',
                    Breadcrumbs::widget([
                        'links' => $this->params['breadcrumbs'] ?? [],
                    ]),
                    ['class' => 'breadcrumbs']
                ) ?>
                <?= Html::tag(
                    'div',
                    $content,
                    ['class' => 'content']
                )
                    ?>
            </div>
        </div>
    </div>
    <div>
        <?=
            Html::beginForm(['/site/logout'], 'post', ['class' => '', 'id' => 'logoutForm'])
            . Html::submitButton(
                'Logout',
                ['class' => 'btn btn-link logout text-decoration-none']
            )
            . Html::endForm();
        ?>
    </div>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage();
