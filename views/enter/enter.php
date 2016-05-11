<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
use edvlerblog\accounting\assets\toastr\Toastr;
use edvlerblog\accounting\widgets\autonumeric\AutoNumericLocalized;



/* @var $this yii\web\View */

Toastr::register($this);

$this->title = 'Enter expense';
$this->params['breadcrumbs'][] = $this->title;
Pjax::begin();
?>
<div class="site-enter">
    <div class="row">
        <div class="col-lg-9 col-lg-offset-1">
            <?php             
                $form = ActiveForm::begin([
                                            'id' => 'enter-form',
                                            'action' => ['savetransaction'],
                                            ]); 
            ?>

            <?= 
                ($transaction->isNewRecord ? null : $form->field($transaction, 'hash')->textInput(['readonly' => true])) 
            ?>

            <?php
                //TODO define global template
                $template = '
                    {label}
                    <div class="input-group">
                    <span class="input-group-addon" title="Description"><i class="glyphicon glyphicon-pencil"></i></span>
                    {input}
                    </div>
                    {error}{hint}';

                echo $form->field($transaction, 'description',['template' => $template])->textInput(['autofocus' => true]);

                echo $form->field($transaction, 'date')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Enter date ...'],
                    'removeButton' => false,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'calendarWeeks' => true,
                        'todayBtn' => true,
                        'todayHighlight' => true,
                        'clearBtn' => true,

                    ]
                ]);
            ?>

            <?= 
                $form->field($transaction, "value",['template' => $template])->widget(AutoNumericLocalized::classname(), []); 
            ?>

            <?= $this->render('enter_tp', [
                'uniqueName' => 'DebitForm',
                'form' => $form,
                'transactionParts' => $tpDebit,
                'accounts' => $accounts
            ]) ?>

            <?= $this->render('enter_tp', [
                'uniqueName' => 'CreditForm',
                'form' => $form,
                'transactionParts' => $tpCredit,
                'accounts' => $accounts
            ]) ?>

            <div class="form-group">
            <?= 
                Html::submitButton(($transaction->isNewRecord ? 'Create' : 'Update'), ['class' => 'btn btn-primary mmmsndform', 'name' => 'contact-button'])
            ?>
            <?php 
                ActiveForm::end();
            ?>

            <?php
                if (!$transaction->isNewRecord) {
                    echo $form_clone = Html::beginForm('enter','post', ['id' => 'clone-form']);

                    echo Html::hiddenInput('clone', $transaction->hash);

                    echo Html::submitButton('Clone', ['class' => 'btn btn-info', 'name' => 'clone-button']);

                    echo Html::endForm();

                    echo Html::button(Yii::t('frontend/enter', 'Delete'), [
                        'class' => 'btn btn-danger',
                        'onclick' => "$.ajax({
                                type     :'POST',
                                cache    : false,
                                data     : { 'hash': $('#transaction-hash').val() },
                                url  : 'delete',
                                success  : function(response) {
                                    if('ok' in response) {
                                        //alert('ok');
                                        $('#myModal').modal('hide');
                                        $('#jqGrid-hhh').jqGrid('delRowData',response['ok']);
                                    } else {
                                       alert('err');
                                    }
                                },
                                  error: function (response) {
                                    $('#debug').val('error');
                                  }
                            });

                            return false;",

                    ]);
                }
                /*
                ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);
                echo var_dump(Yii::$app);*/
            ?>
            </div>

            <?php
                $this->registerJs("
                $('body').on('beforeSubmit', 'form#enter-form', function () {
                     var form = $(this);
                     // return false if form still have some validation errors
                     if (form.find('.has-error').length) {
                          return false;
                     }
                     // submit form
                     $.ajax({
                          url: form.attr('action'),
                          type: 'post',
                          data: form.serialize(),
                          success: function (response) {
                            if('ok' in response) {
                                toastr.success(response['ok'], 'title', '');
                            } else {
                                toastr.error(response['err'], 'title', '');
                            }
                          },
                          error: function (response) {
                            toastr.error(response['err'], 'title', '');
                          },
                     });
                     return false;
                });
                "
                , \yii\web\View::POS_END, 'mmmsndform-js');

                $this->registerJs("$( '#transaction-value-disp' ).blur(function() {
                          $( '#transactionpartdebit-0-value-disp' ).autoNumeric('set', $( '#transaction-value' ).val());
                          $( '#transactionpartdebit-0-value' ).val($( '#transaction-value-disp' ).autoNumeric('get'));

                          $( '#transactionpartcredit-0-value-disp' ).autoNumeric('set', $( '#transaction-value' ).val());
                          $( '#transactionpartcredit-0-value' ).val($( '#transaction-value-disp' ).autoNumeric('get'));
                      });"
                , \yii\web\View::POS_END, 'my-value-field-js');



                $this->registerJs("$('#transaction-date').datepicker().on('show.bs.modal', function(event) {
                        // prevent datepicker from firing bootstrap modal show.bs.modal
                        event.stopPropagation();
                    });"
                 , \yii\web\View::POS_END, 'bs-datepicker-bug');
            ?>
        </div>
    </div>
</div>
<?php
Pjax::end();
?>
