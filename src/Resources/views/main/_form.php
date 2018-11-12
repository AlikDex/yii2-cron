<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $activeForm = ActiveForm::begin([
    'action' => $action,
    'id' => 'task-form',
]) ?>


<?= $activeForm->field($form, 'command')->textarea(['rows' => 3]) ?>

<?= $activeForm->field($form, 'expression')->textInput() ?>

<?= $activeForm->field($form, 'priority')->textInput() ?>

<?php if ('update' === $scenario): ?>
    <?= $activeForm->field($form, 'last_execution')->textInput() ?>

    <?= $activeForm->field($form, 'duration')->textInput() ?>

    <?= $activeForm->field($form, 'status')->textInput() ?>
<?php endif ?>

<?= $activeForm->field($form, 'enabled')->checkbox() ?>

<?php ActiveForm::end() ?>
