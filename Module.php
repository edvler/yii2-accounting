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

use Yii;

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
        
        //Yii::configure($this, require(__DIR__ . '/config/main.php'));
        Yii::$app->assetManager->bundles['wbraganca\dynamicform\DynamicFormAsset'] = [
            'sourcePath' => '@edvlerblog/accounting/assets/yii2-dynamic-form',
            'js' => ['yii2-dynamic-form-custom.js'],
        ];
        
        if (!isset(Yii::$app->i18n->translations['yii2-accounting-models'])) {
            Yii::$app->i18n->translations['yii2-accounting-models'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath' => '@vendor/edvlerblog/yii2-accounting/messages'
            ];
        }
        // custom initialization code goes here
    }
}
