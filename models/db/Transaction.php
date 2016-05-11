<?php

namespace edvlerblog\accounting\models\db;

use Yii;

/**
 * This is the model class for table "{{%transaction}}".
 *
 * @property integer $transaction_id
 * @property string $description
 * @property string $date
 * @property string $value
 * @property string $hash
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'date', 'value', 'hash'], 'required'],
            [['description'], 'string'],
            [['date'], 'safe'],
            [['value'], 'number'],
            [['hash'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transaction_id' => Yii::t('yii2-accounting-models', 'Transaction ID'),
            'description' => Yii::t('yii2-accounting-models', 'Description'),
            'date' => Yii::t('yii2-accounting-models', 'Date'),
            'value' => Yii::t('yii2-accounting-models', 'Value'),
            'hash' => Yii::t('yii2-accounting-models', 'Hash'),
        ];
    }

    /**
     * @inheritdoc
     * @return TransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionQuery(get_called_class());
    }
}
