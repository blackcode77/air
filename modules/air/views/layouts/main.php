<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

//$AirAsset = \air\views\assets\AirAsset::register($this);
$mainAsset = Yii::$app->getAssetManager()->publish('@air/views/assets');

\app\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags();  ?>
    <title><?= Html::encode($this->title) ?></title>

    <?php $this->registerAssetBundle('air\views\assets\AirAsset')  ?>

    <link rel="shortcut icon" href="<?= $mainAsset[1] ?>/img/favicon.ico"/>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div id="overall-wrap">

    <?= \air\widgets\AdminPanelWidget::widget(['mainAsset' => $mainAsset]);
    //$this->widget('yupe\widgets\YAdminPanel'); ?>
    <div class="container-fluid" id="page"><?= $content; ?></div>
    <div id="footer-guard"></div>
</div>

<div class='notifications top-right' id="notifications"></div>



<footer>
    &copy; 2017 - <?= date('Y'); ?>
    <?= Yii::$app->getModule('air')->poweredBy(); ?>
    <small class="label label-info"><?=Yii::$app->getModule('air')->getVersion(); ?></small>
</footer>



<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
