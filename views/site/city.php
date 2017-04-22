<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
 ?>


<h1>Введите данные в форму для получения информации о погоде</h1>

<?php $form = ActiveForm::begin() ?>
<?= $form->field($modelCity, 'apiKey')->hiddenInput()->label(false) ?>
<?= $form->field($modelCity, 'format')->hiddenInput()->label(false) ?>
<?= $form->field($modelCity, 'extra')->hiddenInput()->label(false) ?>
<?= $form->field($modelCity, 'hour')->hiddenInput()->label(false)?>


<?= $form->field($modelCity, 'city')->textInput(['autofocus'=>true]) ?>
<?= $form->field($modelCity, 'country')->textInput(['autofocus'=>true]) ?>
<?= $form->field($modelCity, 'startDate')->textInput(['autofocus'=>true]) ?>
<?= $form->field($modelCity, 'endDate')->textInput(['autofocus'=>true]) ?>
<button type="submit" name="button" id='send'>Отправить</button>
<?php ActiveForm::end() ?>
