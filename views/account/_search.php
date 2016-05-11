<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model edvlerblog\accounting\models\db\AccountSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'account_id') ?>

    <?= $form->field($model, 'accounttype') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'balance_debit') ?>

    <?= $form->field($model, 'balance_credit') ?>

    <?php // echo $form->field($model, 'balance') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
