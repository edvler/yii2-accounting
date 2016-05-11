<?php

namespace edvlerblog\accounting\models\db;

use Yii;

/**
 * This is the model class for table "{{%account}}".
 *
 * @property integer $account_id
 * @property string $accounttype
 * @property string $name
 * @property string $balance_debit
 * @property string $balance_credit
 * @property string $balance
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accounttype', 'name'], 'required'],
            [['balance_debit', 'balance_credit', 'balance'], 'number'],
            [['accounttype'], 'string', 'max' => 1],
            [['name'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'account_id' => Yii::t('yii2-accounting-models', 'Account ID'),
            'accounttype' => Yii::t('yii2-accounting-models', 'Accounttype'),
            'name' => Yii::t('yii2-accounting-models', 'Name'),
            'balance_debit' => Yii::t('yii2-accounting-models', 'Balance Debit'),
            'balance_credit' => Yii::t('yii2-accounting-models', 'Balance Credit'),
            'balance' => Yii::t('yii2-accounting-models', 'Balance'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\modles\AccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AccountQuery(get_called_class());
    }
}
