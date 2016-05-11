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

namespace edvlerblog\accounting\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\base\Model;

use edvlerblog\accounting\tools\StaticEb;
use edvlerblog\accounting\models\db\Account;
use edvlerblog\accounting\api\persistance\TransactionPersistance;

/**
 * Default controller for the `acc` module
 */
class EnterController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        $transaction = null;
        $transactionPartsDebit = null;
        $transactionPartsCredit = null;

        $post = Yii::$app->request->post();

        if(key_exists('clone',$post)) {
            $transactionTemplate = Transaction::find()->withTransactionPartAndHash($post['clone'])->one();

            $transaction = StaticEb::initTransaction($transactionTemplate);
            $transactionPartsDebit = StaticEb::initTransactionPartsDebit($transactionTemplate->getTransactionPartsDebit());
            $transactionPartsCredit = StaticEb::initTransactionPartsCredit($transactionTemplate->getTransactionPartsCredit());

        } else {
            $transaction = StaticEb::initTransaction();
            $transactionPartsDebit = StaticEb::initTransactionPartsDebit();
            $transactionPartsCredit = StaticEb::initTransactionPartsCredit();
        }

        $accounts = ArrayHelper::map(Account::find()->all(),'account_id','name');

        return $this->render('enter', [
            'transaction' => $transaction,
            'tpDebit' => $transactionPartsDebit,
            'tpCredit' => $transactionPartsCredit,
            'accounts' => $accounts,
        ]);
    }
    
    public function actionSavetransaction() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $transaction = StaticEb::initTransaction();
        $transactionPartsDebit = StaticEb::initTransactionPartsDebit();
        $transactionPartsCredit = StaticEb::initTransactionPartsCredit();


        if($transaction->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();

            if (array_key_exists('TransactionPartDebit',$post)) {
                for($i=0; $i < (count($post['TransactionPartDebit'])-1); $i++) {
                    array_push($transactionPartsDebit, new TransactionPartDebit);
                }
                Model::loadMultiple($transactionPartsDebit, Yii::$app->request->post());
            }

            if (array_key_exists('TransactionPartCredit',$post)) {
                for($i=0; $i < (count($post['TransactionPartCredit'])-1); $i++) {
                    array_push($transactionPartsCredit, new TransactionPartCredit);
                }
                Model::loadMultiple($transactionPartsCredit, Yii::$app->request->post());
            }

            try {
                $transaction->date = StaticEb::parseDate($transaction->date, 'd/m/Y', 'Y-m-d');
                TransactionPersistance::persistTransaction($transaction, $transactionPartsDebit, $transactionPartsCredit);
                $arr = ['ok' => 'yeah'];
            } catch (\yii\base\Exception $e) {
                $arr = ['err' => $e->getMessage()];
            }

        } else {
            $arr = ['err' => 'general error'];
        }
        return $arr;
    }    
}