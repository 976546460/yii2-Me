<table class="cate table <!--应用表格样式-->
              table-hover <!--鼠标悬停状态作出响应-->
              table-condensed <!--让表格更加紧凑-->
              table-bordered <!--表格和其中的每个单元格增加边框--> ">
    <a href="<?= \yii\helpers\Url::to(['goods-category/add']) ?>" class="btn btn-primary">添加分类</a>&nbsp;&nbsp;
    <tr class="danger <!--第一行背景色设置-->">
        <th>编号</th>
        <th style="width: 500px;">分类名称</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $k => $v):
        //将$model遍历出来
        if ($k / 2 == 0) {  ////////更换列表背景颜色   并且设置一些特定的值
            echo "<tr class='info' date-lft='{$v->lft}' date-rgt='{$v->rgt}' date-tree='{$v->tree}'>";
        } else {
            echo "<tr class='success'  date-lft='{$v->lft}' date-rgt='{$v->rgt}' date-tree='{$v->tree}'>";
        }
        ?>
        <th><?= $v->id ?></th>
        <th style="width: 700px;"><?= str_repeat('----', $v->depth) . $v->name ?>
                <span class="toggle_cate  glyphicon glyphicon-chevron-down" style="float: right"> </span>
        <th>
            <?= \yii\bootstrap\Html::a('修改', ['goods-category/edit', 'id' => $v->id], ['class' => 'btn btn-info btn-xs']) ?>
            <?= \yii\bootstrap\Html::a('删除', ['goods-category/del', 'id' => $v->id], ['class' => 'btn btn-danger btn-xs']) ?>
        </th>
        </tr>
    <?php endforeach; ?>
</table>

<?php
$js=<<<JS
    //点击分类名称右边箭头 收起或展开当前分类
    $(".toggle_cate").click(function () {
        //查找当前分类的所有子孙分类（根据tree  lft rgt）
        //先获得当前的所有分类中tree lft rgt 的值以便后续对比 找到对应子节点
        //点击图标之后往上级寻找 tr  然后获取当前的tr中的tree 也就是在表格中tr设置的date-tree值
        var tr=$(this).closest('tr');//定义tr 为变量
        var tree=parseInt(tr.attr('date-tree'));//获取当前的tr中的tree 也就是在表格中tr设置的date-tree值
        var lft=parseInt(tr.attr('date-lft'));//lft 值
        var rgt=parseInt(tr.attr('date-rgt'));// rgt 值
        //显示还是隐藏 
        var show = $(this).hasClass('glyphicon-chevron-up');
        
        //切换图标
         $(this).toggleClass('glyphicon glyphicon-chevron-up');
         $(this).toggleClass('glyphicon glyphicon-chevron-down');
         
        $('.cate tr').each(function () {
            //判断是不是同一树干             判断左值大于 lft                       判断右值小于rgt 
           //parseInt() //转换为整数型  进项对等计算
            if(parseInt($(this).attr('date-tree'))==tree && parseInt($(this).attr('date-lft'))>lft && parseInt($(this).attr('date-rgt'))<rgt)
            {
               show?$(this).fadeIn():$(this).fadeOut();
            }
        });

    })
JS;
$this->registerJs($js);




/*echo '<ul id="treeDemo"  class="ztree"></ul>';//输出带有层级的商品分类

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
$this->registerJs($js);*/