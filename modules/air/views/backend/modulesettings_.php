<?php
/*
$this->breadcrumbs = [
    Yii::t('YupeModule.yupe', 'Yupe!')   => ['settings'],
    Yii::t('YupeModule.yupe', 'Modules') => ['settings'],
    $module->name,
];*/
?>

<h1>
    <?= 'Настройки модуля'; ?> "<?= \yii\helpers\Html::encode($module->name); ?>"
    <small>version <?= \yii\helpers\Html::encode($module->version); ?></small>
</h1>

<br/>
<?php //debug_($groups); ?>
<?php if (is_array($groups) && count($groups)): { ?>
    <?= \yii\helpers\Html::beginForm(
        ['/air/backend/saveModulesettings'],
        'post',
        [
            'class' => 'well',
        ]
    ); ?>
    <?= CHtml::hiddenField('module_id', $module->getId()); ?>
    <div class="row">
        <div class="col-sm-8">
            <?php $collapse = $this->beginWidget('booster.widgets.TbCollapse'); ?>
            <?php $i = 0; ?>
            <?php foreach ((array)$groups as $title => $items): ?>
                <?php $i++; ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#group-<?= $i ?>">
                                <?= $title ?>
                            </a>
                        </h4>
                    </div>
                    <div id="group-<?= $i ?>" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <?php foreach ((array)$items as $item): ?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <?= $item ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>


            <?php $this->endWidget(); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <?= CHtml::submitButton(
                Yii::t(
                    'YupeModule.yupe',
                    'Save "{{name}}" module settings',
                    [
                        '{{name}}' => CHtml::encode($module->name)
                    ]
                ),
                [
                    'class' => 'btn btn-primary',
                    'id'    => 'saveModuleSettings',
                    'name'  => 'saveModuleSettings',
                ]
            ); ?>
        </div>
    </div>
    <?= \yii\helpers\Html::endForm(); ?>
<?php } else: { ?>
    <b>There is no parameters which you cat change for this module...</b>
<?php } endif; ?>
