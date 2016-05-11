<?php

namespace edvlerblog\accounting\models\db;

use Yii;

/**
 * This is the model class for table "{{%transaction_part}}".
 *
 * @property integer $transactionpart_id
 * @property integer $transaction_id
 * @property integer $account_id
 * @property string $accountside
 * @property string $value
 * @property string $description
 */
class TransactionPart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction_part}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'accountside', 'value'], 'required'],
            [['transaction_id', 'account_id'], 'integer'],
            [['value'], 'number'],
            [['description'], 'string'],
            [['accountside'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transactionpart_id' => Yii::t('yii2-accounting-models', 'Transactionpart ID'),
            'transaction_id' => Yii::t('yii2-accounting-models', 'Transaction ID'),
            'account_id' => Yii::t('yii2-accounting-models', 'Account ID'),
            'accountside' => Yii::t('yii2-accounting-models', 'Accountside'),
            'value' => Yii::t('yii2-accounting-models', 'Value'),
            'description' => Yii::t('yii2-accounting-models', 'Description'),
        ];
    }

    /**
     * @inheritdoc
     * @return TransactionPartQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionPartQuery(get_called_class());
    }
}
