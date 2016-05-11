<?php

namespace edvlerblog\accounting\tools;

use \yii\base\Exception;
use edvlerblog\accounting\models\db\Transaction;
use edvlerblog\accounting\models\db\TransactionPartCredit;
use edvlerblog\accounting\models\db\TransactionPartDebit;

class StaticEb {

    /**
     * Sign for accountside debit
     */
    public static $accountsideDebitSign = 'D';

    /**
     * Sign for accountside credit
     */
    public static $accountsideCreditSign = 'C';

    /**
     * Sign for accounttype Active
     */
    public static $accountTypeActive = 'A';

    /**
     * Sign for accounttype Passive
     */
    public static $accountTypePassive = 'P';

    /**
     * Sign for accounttype Income
     */
    public static $accountTypeIncome = 'I';

    /**
     * Sign for accounttype Expense
     */
    public static $accountTypeExpense = 'E';

    /**
     * Precision used for calculations
     */
    public static $roundPrecision = 4;

    /**
     * Format credit or debit accountside
     *
     * @param string $value
     * @return string formatted language string
     * @throws Exception if sign other than C,D given
     */
    public static function formatAccountside($value) {
        if ($value === StaticEb::$accountsideDebitSign) {
            return \Yii::t('common/staticeb', 'Debit');
        } else if ($value === StaticEb::$accountsideCreditSign) {
            return \Yii::t('common/staticeb', 'Credit');
        } else {
            throw new Exception(\Yii::t('common/staticeb', '{value} is not a accounside. Valid values: ' . StaticEb::$accountsideDebitSign . ',' . StaticEb::$accountsideCreditSign, [ 'value' => $value]
            ));
        }
    }

    /**
     * Get AccountsideSign from current language
     *
     * @param string $value
     * @return string formatted language string
     * @throws Exception if accountside sign could not be determined
     */
    public static function getAccountSideSignFromLanguage($value) {
        switch (strtolower($value)) {
            case strtolower(StaticEb::$accountsideDebitSign):
            case strtolower(StaticEb::formatAccountside('D')):
                return 'D';
            case strtolower(StaticEb::$accountsideCreditSign):
            case strtolower(StaticEb::formatAccountside('C')):
                return 'C';
            default:
                throw new Exception(\Yii::t('common/staticeb', 'Cannot determine accoundside sign from  value "{value}". Valid values: '
                        . StaticEb::$accountsideDebitSign . ', ' . StaticEb::formatAccountside('D') . ', '
                        . StaticEb::$accountsideCreditSign . ', ' . StaticEb::formatAccountside('C'), [ 'value' => $value]
                ));
        }
    }

    /**
     * Format a currency value
     *
     * @param string|float|int Value to format
     * @return formatted value
     */
    public static function formatCurrency($value) {
        return \Yii::$app->formatter->asCurrency($value);
    }

    /**
     * Get regex pattern for currency values
     *
     * @return string regex pattern
     */
    public static function getCurrencyRegex() {
        return '/^\d+(?:\.\d{0,4})?$/';
    }

    /**
     * Parse a date from a string
     *
     * @param string String to parse
     * @param string Source format according to http://php.net/manual/de/datetime.createfromformat.php
     * @param string Destination format according to http://php.net/manual/de/datetime.createfromformat.php
     * @return string the formatted date
     */
    public static function parseDate($sourceDate, $sourceFormat, $destinationFormat) {
        $myDateTime = \DateTime::createFromFormat($sourceFormat, $sourceDate);

        if ($myDateTime === false) {
            throw new Exception("Date " . $sourceDate . " cannot be parsed with format \"" . $sourceFormat . "\"");
        }

        return $myDateTime->format($destinationFormat);
    }

    /**
     * Add two float values with precision given by StaticEb::$roundPrecision
     *
     * @param float/int Number one
     * @param float/int Number two
     * @return float the sum
     */
    public static function mathAdd($decimal1, $decimal2) {
        if (!is_numeric($decimal1)) {
            throw new Exception("decimal1 is not a numeric value: " . $decimal1);
        }

        if (!is_numeric($decimal2)) {
            throw new Exception("decimal2 is not a numeric value: " . $decimal2);
        }

        return round($decimal1 + $decimal2, StaticEb::$roundPrecision);
    }

    /**
     * Subtract two float values with precision given by StaticEb::$roundPrecision
     *
     * @param float/int Number one
     * @param float/int Number two
     * @return float the sum
     */
    public static function mathSubtract($decimal1, $decimal2) {
        if (!is_numeric($decimal1)) {
            throw new Exception("decimal1 is not a numeric value: " . $decimal1);
        }

        if (!is_numeric($decimal2)) {
            throw new Exception("decimal2 is not a numeric value: " . $decimal2);
        }

        return round($decimal1 - $decimal2, StaticEb::$roundPrecision);
    }

    /**
     * Get the localeconv array.
     * http://php.net/manual/de/function.localeconv.php
     *
     *
     * @return array with local informations for numbers and currency
     */
    public static function getLoacleconv() {
        if (setlocale(LC_MONETARY, \Yii::$app->language)) {
            $arr = localeconv();

            //\Yii::error($arr['currency_symbol'] . setlocale(LC_MONETARY,0). '    ' . bin2hex($arr['mon_thousands_sep']));

            $arr['currency_symbol'] = iconv("Windows-1252", "UTF-8", $arr['currency_symbol']);
            $arr['mon_thousands_sep'] = iconv("Windows-1252", "UTF-8", $arr['mon_thousands_sep']);
            return $arr;
        } else {
            throw new \yii\base\Exception('Cannot set locale ' . \Yii::$app->language . '. Maybe the local is not installed. See php manual for further information');
        }
    }

    /**
     * Clone transaction
     * Clone fields description, date, value into a new transaction object
     * 
     * @param Transaction The Transation to clone
     * @return Transaction New Transaction object
     */
    public static function initTransaction($transactionTemplate = null) {
        $fields = ['description', 'date', 'value'];
        
        $transaction = new Transaction();
        
        if ($transactionTemplate !== null) {
            foreach ($fields as $f) {
                $transaction->$f = $transactionTemplate->$f;
            }
        }
        
        return $transaction;
    }

    /**
     * Clone transaction
     * Clone fields description, date, value into a new transaction object
     * 
     * @param Transaction The Transation to clone
     * @return Transaction New Transaction object
     */
    public static function initTransactionPartsDebit($transactionPartsTemplate = null) {
        $fields = ['account_id', 'value', 'description'];
        
        $transactionParts = [new TransactionPartDebit()];
        
        $c = 0;
        if ($transactionPartsTemplate !== null) {
            foreach ($transactionPartsTemplate as $tp) {
                
                if ($c >= 1) {
                    array_push($transactionParts, new TransactionPartDebit());
                }
                
                foreach ($fields as $f) {
                    $transactionParts[$c]->$f = $tp->$f;
                }
                $c++;
            }
        }
        
        return $transactionParts;
    }

    /**
     * Clone transaction
     * Clone fields description, date, value into a new transaction object
     * 
     * @param Transaction The Transation to clone
     * @return Transaction New Transaction object
     */
    public static function initTransactionPartsCredit($transactionPartsTemplate = null) {
        $fields = ['account_id', 'value', 'description'];
        
        $transactionParts = [new TransactionPartCredit()];
        
        $c = 0;
        if ($transactionPartsTemplate !== null) {
            foreach ($transactionPartsTemplate as $tp) {
                
                if ($c >= 1) {
                    array_push($transactionParts, new TransactionPartCredit());
                }
                
                foreach ($fields as $f) {
                    $transactionParts[$c]->$f = $tp->$f;
                }
                $c++;
            }
        }
        
        return $transactionParts;
    }    
}
