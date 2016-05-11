<?php

namespace edvlerblog\accounting\models\db;

/**
 * This is the ActiveQuery class for [[\app\models\Account]].
 *
 * @see \app\models\Account
 */
class AccountQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Account[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Account|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
