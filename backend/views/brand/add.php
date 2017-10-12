<?php
/////////// BUG ->   选择第二张图片的时候不能还是显示第一张图片
use yii\web\JsExpression;
echo \yii\bootstrap\Html::a('返回列表',['brand/index'],['class'=>'btn btn-info']);
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
//echo $form->field($model,'imgFile')->fileInput();
//建立隐藏域添加logo的地址
 echo $form->field($model,'logo')->hiddenInput([]);
// 插件安装区域
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else{
        console.log(data.fileUrl);
        $('#img_logo').attr("src",data.fileUrl).show();
        $('#brand-logo').val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if ($model->logo){
    echo \yii\bootstrap\Html::img($model->logo,['height'=>70,'id'=>'img_logo']);
}else{
    echo \yii\bootstrap\Html::img('',['style'=>'display:none','id'=>'img_logo','height'=>70]);
};
echo $form->field($model,'sort');
echo $form->field($model,'status')->radioList([1=>'正常',0=>'隐藏']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();