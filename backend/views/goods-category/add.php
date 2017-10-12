<?php
echo \yii\bootstrap\Html::a('分类列表',['goods-category/index'],['class'=>'btn btn-info']);
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name');
echo $form->field($model, 'parent_id')->hiddenInput();
//echo $form->field($model, 'parent_id')->dropDownList($options);
echo '<ul id="treeDemo"  class="ztree"></ul>';//输出带有层级的商品分类
echo $form->field($model, 'intro');
echo \yii\bootstrap\Html::submitButton('提交', ['class' => 'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
//引入样式文件
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
//$this->registerJsFile('@web/zTree/js/jquery-1.4.4.min.js');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js', ['depends' => \yii\web\JqueryAsset::className()]);// depends ->解决文件前后加载依赖关系
$zNodes = \yii\helpers\Json::encode($categories);//将类别数组数据转为json字符串 以便ztree插件使用
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
                $('#goodscategory-parent_id').val(treeNode.id);//将单击鼠标选中的值动态赋值到parent_id隐藏于中 
            }
        }
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes =$zNodes;
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        zTreeObj.expandAll(true);//设置默认展开全部节点 包括子节点
        var nodes = zTreeObj.getNodesByParam("id", $('#goodscategory-parent_id').val(), null);//根据当前id找到该节点
        zTreeObj.selectNode(nodes[0]);//根据现在的节点找到父id
        var aa = zTreeObj.getNodes()[0].children;//获取第一个根节点的子节点
        // console.log(aa);
JS
);
$this->registerJs($js);