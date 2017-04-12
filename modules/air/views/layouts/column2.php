<?php
//debug_(Yii::$app);
//
// ?>
<?php $this->beginContent( Yii::$app->getModule('air')->getBackendLayoutAlias("main.php") ); ?>

<?=$content?>

<?php $this->endContent(); ?>

