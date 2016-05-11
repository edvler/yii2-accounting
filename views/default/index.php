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

use yii\widgets\Pjax;
use yii\helpers\Html;
?>

<?php
    Pjax::begin();
?>
<div class="acc-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
        The action belongs to the controller "<?= get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
    <p>
        You may customize this page by editing the following file:<br>
        <code><?= __FILE__ ?></code>
    </p>
</div>
<?php
    echo Html::a("Refresh", ['install/index'], ['class' => 'btn btn-lg btn-primary']);
    Pjax::end();
?>