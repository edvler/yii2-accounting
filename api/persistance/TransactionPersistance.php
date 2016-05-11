<?php

namespace edvlerblog\accounting\api\persistance;

use yii\base\Model;
use \yii\base\Exception;


use edvlerblog\accounting\tools\StaticEb;
use edvlerblog\accounting\models\db\Account;
use edvlerblog\accounting\models\db\Transaction;
use edvlerblog\accounting\models\db\TransactionPart;

class TransactionPersistance {

  /**
    * Generate transaction hash
    *
    * @param Transaction transaction to hash
    * @return String Hash value
    * @throws Exception
    */
    public static function hashTransaction($transaction) {
        $salt = 'SALT';

        if ($salt != null) {

              $s = $salt . microtime() . $transaction->date . $transaction->description . $transaction->value;
              $hash = hash('sha512',$s,false);
        } else {
            throw new Exception("TransactionPersistance.hash_salt setting empty");
        }

        return $hash;
    }


  /**
    * Delete a Transaction
    *
    * @param string Transaction hash
    * @throws Exception
    */
    public static function deleteTransaction($hash) {

        $transaction = Transaction::find()->withTransactionPartAndHash($hash)->one();

        if ($transaction === null) {
          throw new Exception("Transaction with hash " . $hash . " not found.");
        }

        $dbTransaction = \Yii::$app->db->beginTransaction();
        try {
            foreach ($transaction->transactionParts as $tp) {
                TransactionPersistance::setAccountBalance($tp->account_id, $tp->accountside_id, $tp->value, true);
            }
            $id = $transaction->transaction_id;
            TransactionPart::deleteAll(['transaction_id' => $transaction->transaction_id]);
            Transaction::deleteAll(['transaction_id' => $transaction->transaction_id]);
            
            $dbTransaction->commit();
            return $id;
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            throw $e;
        }
    }

   /**
     * Persit a transaction to database.
     *
     * @param Transaction Transaction to save
     * @param TransactionPart Debit transaction parts
     * @param TransactionPart Credit transaction parts
     * @return integer The id of the Transaction or null if a error occured
     * @throws Exception
     */
    public static function persistTransaction($transaction, $transactionPartsDebit, $transactionPartsCredit) {
        if ($transaction->hash === null) {
            $transaction->hash = TransactionPersistance::hashTransaction($transaction);
        }
        
        if (count($transactionPartsDebit)<1 || count($transactionPartsCredit)<1) {
            throw new Exception("transactionPartsDebit (" . count($transactionPartsDebit) . " elements) or transactionPartsCredit (" . count($transactionPartsCredit) . " elements) empty");
        }
        
        $transaction->validate();
        Model::validateMultiple($transactionPartsDebit);
        Model::validateMultiple($transactionPartsCredit);

        if (count($transaction->errors) > 0) {
            throw new Exception('Transaction validation errors: ' . print_r($transaction->errors,true));
        }
        foreach ($transactionPartsDebit as $tp) {
            if (count($tp->errors) > 0) {
                throw new Exception('TransactionPartsDebit validation errors: ' . print_r($tp->errors,true));
            }
        }
        foreach ($transactionPartsCredit as $tp) {
            if (count($tp->errors) > 0) {
                throw new Exception('TransactionPartsCredit validation errors: ' . print_r($tp->errors,true));
            }
        }
        
        //Check sums
        $sumDebit = 0;
        $debitAccounts = [];
        foreach ($transactionPartsDebit as $tp) {
            $sumDebit = StaticEb::mathAdd($tp->value,$sumDebit);
            array_push($debitAccounts,$tp->account_id);
        }

        $sumCredit = 0;
        foreach ($transactionPartsCredit as $tp) {
            if (in_array($tp->account_id,$debitAccounts)) {
                throw new Exception("Account ID " . $tp->account_id . " in debit and credit side.");
            }

            $sumCredit = StaticEb::mathAdd($tp->value,$sumCredit);
        }

        if ($sumCredit != $sumDebit || $sumDebit != $transaction->value || $sumCredit != $transaction->value ) {
            throw new Exception("Values of transaction (" . $transaction->value . "), debit side (" . $sumDebit . ") credit side (" . $sumCredit .") not equal.");
        }

        
        
        $dbTransaction = \Yii::$app->db->beginTransaction();
        try {
            if (!$transaction->save(false)) {
                $dbTransaction->rollBack();
                throw new Exception("Transaction could not be saved. Data: " . print_r($transaction->attributes,true));
            }
                     
            foreach ($transactionPartsDebit as $tp) {
                TransactionPersistance::persistTransactionPart($tp,$transaction->transaction_id);
            }

            foreach ($transactionPartsCredit as $tp) {
                TransactionPersistance::persistTransactionPart($tp,$transaction->transaction_id);
            }

            $dbTransaction->commit();
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            throw $e;
        }

        return $transaction->transaction_id;
    }

    /**
     * Save a transaction part
     *
     * @param type $transactionPart
     * @param type $transaction_id
     * @throws Exception
     */
    private static function persistTransactionPart($transactionPart, $transaction_id) {
        $transactionPart->transaction_id = $transaction_id;

        TransactionPersistance::setAccountBalance($transactionPart->account_id, $transactionPart->accountSide, $transactionPart->value);

        if (!$transactionPart->save(false)) {
            throw new Exception("TransactionPart could not be saved. Data: " . print_r($transactionPart->attributes,true));
        }
    }

    /**
     * Update the balance of a account
     *
     * @param int $account_id The account id which should be updated
     * @param char $accountSide
     * @param float $value
     * @throws Exception
     */
    private static function setAccountBalance($account_id, $accountSide, $value, $delete = false) {
        $account = Account::findOne($account_id);

        if ($account === null) {
            throw new Exception('account with id ' . $account_id . ' not found');
        }

        if ($accountSide === StaticEb::$accountsideDebitSign) {
            //If delete, then subtract
            If ($delete === false) {
                $account->balance_debit = StaticEb::mathAdd($account->balance_debit, $value);
            } else {
                $account->balance_debit = StaticEb::mathSubtract($account->balance_debit, $value);
            }

        } else if ($accountSide === StaticEb::$accountsideCreditSign) {
            //If delete, then subtract
            If ($delete === false) {
                $account->balance_credit = StaticEb::mathAdd($account->balance_credit, $value);
            } else {
                $account->balance_credit = StaticEb::mathSubtract($account->balance_credit, $value);;
            }
        } else {
            throw new Exception($accountSide . " is not valid");
        }

        if ($account->accounttype == StaticEb::$accountTypeActive ||
                $account->accounttype == StaticEb::$accountTypeExpense) {
                $account->balance = StaticEb::mathSubtract($account->balance_debit, $account->balance_credit);
        }

        if ($account->accounttype == StaticEb::$accountTypePassive ||
                $account->accounttype == StaticEb::$accountTypeIncome) {
                $account->balance = StaticEb::mathSubtract($account->balance_credit, $account->balance_debit);
        }

        if (!$account->save()) {
            throw new Exception("Account could not be saved. Data: " . print_r($account->attributes,true));
        }
    }
}
