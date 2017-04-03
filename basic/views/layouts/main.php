<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

$this->title = 'Search System Parser';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Search System Parser',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index'], 'visible' => !Yii::$app->user->isGuest],
            [
                'label' => 'Search results',
                'items' => [
                    ['label' => 'List', 'url' => ['/search-result/index'], 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'Spider', 'url' => ['/webspider/index'], 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'Google Analysis', 'url' => ['/google-analysis/index'], 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'Google Analysis Stats', 'url' => ['/google-analysis/stats'], 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'Position', 'url' => ['/position-parser/index'], 'visible' => !Yii::$app->user->isGuest],
                ],
                'visible' => !Yii::$app->user->isGuest
            ],
            [
                'label' => 'Keywords',
                'items' => [
                    ['label' => 'Theme', 'url' => ['/theme-keyword/index'], 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'List', 'url' => ['/keyword/index'], 'visible' => !Yii::$app->user->isGuest],
                ],
                'visible' => !Yii::$app->user->isGuest
            ],
            [
                'label' => 'Search System',
                'items' => [
                    ['label' => 'List', 'url' => ['/search-system/index'], 'visible' => !Yii::$app->user->isGuest],
                ],
                'visible' => !Yii::$app->user->isGuest
            ],
            [
                'label' => 'Domain',
                'items' => [
                    ['label' => 'List', 'url' => ['/domain/index'], 'visible' => !Yii::$app->user->isGuest],
                ],
                'visible' => !Yii::$app->user->isGuest
            ],
                        
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
