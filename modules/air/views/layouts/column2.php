<?php
//debug_(Yii::$app);
//
// ?>
<?php $this->beginContent( Yii::$app->getModule('air')->getBackendLayoutAlias("main.php") ); ?>

<?=\air\widgets\AlertWidget::widget()?>
<?=$content?>

<?php $this->endContent(); ?>

