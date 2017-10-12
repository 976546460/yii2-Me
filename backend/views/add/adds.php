<?php
namespace backend;
use yii\web\JsExpression;
// 插件安装区域
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);// 插件图片上传框
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
        $('#goods-logo').val(data.fileUrl);
    }
//    if(data.fileUrl!=$model->logo){
//         $('#img_logo').attr("src",data.fileUrl).show();
//     }
}
EOF
        ),
    ]
]);
if ($model->logo) {
    echo \yii\bootstrap\Html::img($model->logo, ['height' => 70, 'id' => 'images']);
} else {
    echo \yii\bootstrap\Html::img('', ['style' => 'display:none', 'id' => 'img_logo', 'height' => 70]);
};
