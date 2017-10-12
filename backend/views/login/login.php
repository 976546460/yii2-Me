<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo  $form->field($model,'username')->textInput()->label('用户名');
echo $form->field($model,'password_hash')->passwordInput()->label('密码');
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

