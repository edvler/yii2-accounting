<?php
/**
 * yii2-accounting (c) by Matthias Maderer
 * 
 * @link https://github.com/edvler/yii2-accounting
 * @copyright Copyright (c) 2016 Matthias Maderer
 * 
 * Licensed under GNU General Public License 3.0 or later. 
 * @license https://github.com/edvler/yii2-accounting/LICENSE.md
 * 
 */

use yii\widgets\Pjax;
use yii\helpers\Html;
use edvlerblog\accounting\models\db\Transaction;
use yii\bootstrap\ActiveForm;

?>

<div class="acc-default-index">
    <h1>Installation of Yii2 accounting</h1>
    
    <?php 
        Pjax::begin();
        
        echo Html::a("Refresh", ['install/index'], ['class' => 'btn btn-lg btn-primary']);
        echo Html::a("site", ['default/index'], ['class' => 'btn btn-lg btn-primary']);
        foreach (Yii::$app->modules as $module) {
            echo get_class($module); 
        }
        echo 'Current time: ' .  date('H:i:s') . '</h1>';
        Pjax::end();
    ?>   
                <?php $form = ActiveForm::begin([
                                            'id' => 'enter-form',
                                            'action' => 'savetransaction'
                                            ]); 
                
                ?>
    
            <?php 
                $transaction = new Transaction();
                echo $form->field($transaction, 'description')->textInput(['autofocus' => true]);
                echo Html::submitButton('Update', ['class' => 'btn btn-primary mmmsndform', 'name' => 'contact-button'])
            ?>    
                <?php 
                    ActiveForm::end();
                ?>
    <p></p> 
</div>
