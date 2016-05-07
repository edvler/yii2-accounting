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

namespace edvlerblog\accounting;

/**
 * acc module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'edvlerblog\accounting\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
