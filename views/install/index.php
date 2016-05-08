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

?>

<div class="acc-default-index">
    <h1>Installation of Yii2 accounting</h1>
    <?php 
        foreach (Yii::$app->modules as $module) {
            echo get_class($module); 
        }    
    ?>   
    <p></p> 
</div>
