<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model edvlerblog\accounting\models\db\Account */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Account',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->account_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="account-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
