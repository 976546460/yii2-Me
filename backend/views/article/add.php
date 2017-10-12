<?php
echo \yii\bootstrap\Html::a('返回列表', ['article/index'], ['class' => 'btn btn-info']);
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'intro')->textarea();
echo $form->field($model, 'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($article_category, 'id', 'name'));
//文本编辑器的第二种输出：该输出可详细配置编辑器参数
echo $form->field($article_detail,'content')->widget(kucha\ueditor\UEditor::className(),['options'=>['initialFrameWidth' => 850,]]);
//echo $form->field($article_detail, 'content')->textarea(['style' => ['height' => '300px']]);//输出普通textarea文本框
echo $form->field($model, 'sort')->textInput();
echo $form->field($model, 'status', ['inline' => true])->radioList([1 => '正常', 0 => '隐藏']);
echo \yii\bootstrap\Html::submitButton('提交', ['class' => 'btn btn-info']);
\yii\bootstrap\ActiveForm::end();


?>