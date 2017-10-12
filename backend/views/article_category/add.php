<?php
echo \yii\bootstrap\Html::a('返回列表',['article_category/index'],['class'=>'btn btn-info']);
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'status',['inline'=>true])->radioList([1=>'正常',0=>'隐藏']);
echo $form->field($model,'is_help',['inline'=>true])->radioList([1=>'帮助文档',0=>'导购文档']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();