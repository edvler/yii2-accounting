<?php

namespace edvlerblog\accounting\models\db;

/**
 * This is the ActiveQuery class for [[TransactionPart]].
 *
 * @see TransactionPart
 */
class TransactionPartQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TransactionPart[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TransactionPart|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
