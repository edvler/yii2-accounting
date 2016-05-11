<?php

namespace edvlerblog\accounting\models\db;

use Yii;
use edvlerblog\accounting\tools\StaticEb;

/**
 * @inheritdoc
 */
class TransactionPartCredit extends TransactionPart
{
    function __construct() {
        $this->accountside=StaticEb::$accountsideCreditSign;
    }
    
    
    /**
     * @inheritdoc
     */
    public function getAccountside()
    {
        return StaticEb::$accountsideCreditSign;
    }
}
