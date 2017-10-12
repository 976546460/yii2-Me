<?php
use yii\web\JsExpression;
//var_dump($model);exit;
$form = \yii\bootstrap\ActiveForm::begin(["options" => ["enctype" => "multipart/form-data"]]);
echo $form->field($model, 'name');
//    echo $form->field($model,'logo')->textInput();
/*******图片插件uploadifile**********/
//建立隐藏域添加logo的地址
echo $form->field($model, 'logo')->hiddenInput([]);
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

}
EOF
        ),
    ]
]);
if ($model->logo) {
    echo \yii\bootstrap\Html::img($model->logo, ['height' => 70, 'id' => 'img_logo']);
} else {
    echo \yii\bootstrap\Html::img('', ['style' => 'display:none', 'id' => 'img_logo', 'height' => 70]);
};
/*******图片插件uploadifile**END ********/
echo $form->field($goodsIntro, 'content')->widget(kucha\ueditor\UEditor::className(), []);
echo $form->field($model, 'brand_id')->dropDownList(\yii\helpers\ArrayHelper::map($brand, 'id', 'name'));
echo $form->field($model, 'goods_category_id')->hiddenInput();//品隐藏框牌的
echo '<ul id="treeDemo"  class="ztree"></ul>';//输出带有层级的商品分类
//echo $form->field($model,'brand_id')->textInput();
//var_dump($goodsPhoto);
echo $form->field($goodsPhoto, 'fileImage[]')->widget(\kartik\file\FileInput::className(), [
    'options' => ['multiple' => true,'accept'=>"*/*"],

]);
echo $form->field($model, 'market_price')->textInput();
echo $form->field($model, 'shop_price')->textInput();
echo $form->field($model, 'stock')->textInput();
echo $form->field($model, 'is_on_sale', ['inline' => true])->radioList([1 => '在售', 0 => '下架']);
echo $form->field($model, 'status', ['inline' => true])->radioList([1 => '正常', 0 => '回收']);
echo $form->field($model, 'sort')->textInput();
echo \yii\bootstrap\Html::submitButton('提交', ['class' => 'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

//引入样式文件
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
//$this->registerJsFile('@web/zTree/js/jquery-1.4.4.min.js');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js', ['depends' => \yii\web\JqueryAsset::className()]);// depends ->解决文件前后加载依赖关系
$zNodes = \yii\helpers\Json::encode($goodsCategory);//将类别数组数据转为json字符串 以便ztree插件使用
$js = new \yii\web\JsExpression(
    <<<JS
    var zTreeObj;
    // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }
        },
        callback: {
            onClick: function zTreeOnClick(event, treeId, treeNode) {
                $('#goods-goods_category_id').val(parseInt(treeNode.id));//将单击鼠标选中的值动态赋值到parent_id隐藏于中 
           console.debug( $('#goods-goods_category_id').val(parseInt(treeNode.id)));
            }
        }
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes =$zNodes;
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        zTreeObj.expandAll(true);//设置默认展开全部节点 包括子节点
        var nodes = zTreeObj.getNodesByParam("id", $('#goods-goods_category_id').val(), null);//根据当前id找到该节点
         zTreeObj.selectNode(nodes[0]);//根据现在的节点找到父id
JS
);
$this->registerJs($js);


