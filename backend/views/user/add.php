<?php
use yii\web\JsExpression;

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'username');
echo $form->field($model, 'password2')->passwordInput()->label('密码');
echo  $form->field($model,'password')->passwordInput()->label('确认密码');
echo $form->field($model, 'password_reset_token')->textInput();
echo $form->field($model, 'email')->textInput();
echo $form->field($model,'name')->checkboxList(\backend\models\User::getRoleAction());
echo \yii\bootstrap\Html::submitButton('提交', ['class' => 'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
