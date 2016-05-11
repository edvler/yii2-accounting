<?php
use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;
use edvlerblog\accounting\widgets\autonumeric\AutoNumericLocalized;
?>


<?php DynamicFormWidget::begin([
    'widgetContainer' => $uniqueName,
    'widgetBody' => '.container-tp' . $uniqueName,
    'widgetItem' => '.tp-item' . $uniqueName,
    'limit' => 4,
    'min' => 1,
    'insertButton' => '.add-' . $uniqueName,
    'deleteButton' => '.remove-' . $uniqueName,
    'model' => $transactionParts[0],
    'formId' => 'enter-form',
    'formFields' => [
        'Account',
        'description'
    ],
]); ?>

<?php
    $template2 = '
        <div class="visible-xs-block">{label}</div>
        <div class="input-group">
        <span class="input-group-addon" title="Description"><i class="glyphicon glyphicon-pencil"></i></span>
        {input}
        </div>
        {error}{hint}';
?>

<div class="row">
  <div class="col-md-3 visible-md-block">Account</div>
  <div class="col-md-3 visible-md-block">Value</div>
  <div class="col-md-3 visible-md-block">Description</div>
  <div class="col-md-3">
    <button type="button" class="add-<?= $uniqueName?> btn btn-success btn-xs">
        <span class="glyphicon glyphicon-plus"></span>
    </button>
  </div>
</div>

<div class="container-tp<?= $uniqueName?>">
    <?php foreach ($transactionParts as $fid => $tp): ?>
        <div class="row tp-item<?= $uniqueName?>">
          <div class="col-md-3">
                    <?=
                          $form->field($tp, "[{$fid}]account_id")->label(false)->widget(Select2::classname(), [
                              'data' =>$accounts,
                              'options' => ['placeholder' => 'Select account ...'],
                              'pluginOptions' => [
                                  'allowClear' => true
                              ],
                          ]);
                          ?>
          </div>
          <div class="col-md-3">
                    <?=
                        $form->field($tp, "[{$fid}]value",['template' => $template2])->widget(AutoNumericLocalized::classname());
                    ?>
          </div>
          <div class="col-md-3">
                    <?php
                        // necessary for update action.
                        if (! $tp->isNewRecord) {
                            echo Html::activeHiddenInput($tp, "[{$fid}]transaction_id");
                        }
                    ?>

                    <?=
                        $form->field($tp, "[{$fid}]description",['template' => $template2])->textInput(['maxlength' => true])
                    ?>
          </div>
          <div class="col-md-3">
            <button type="button" class="remove-<?= $uniqueName?> btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus"></span></button>
          </div>
        </div>
    <?php endforeach; ?>
</div>

<?php DynamicFormWidget::end(); ?>
