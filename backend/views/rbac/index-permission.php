<style>
    table{
        table-layout: fixed;width:1200px  /*<!-- 设置表格中超出部分隐藏的前提，规定表格的宽的 并且设置table-layout: fixed属性 -->*/
    }
    th,td{ /*让单元格中的内容自动居中*/
        text-align: center;
        vertical-align: middle!important;
        white-space: nowrap;text-overflow: ellipsis;overflow: hidden; <!--设置内容超出单元格部分隐藏  -->
    }
</style>
<table  class="table <!--应用表格样式-->
              table-hover <!--鼠标悬停状态作出响应-->
              table-condensed <!--让表格更加紧凑-->
              table-bordered <!--表格和其中的每个单元格增加边框--> ">
    <a href="<?=\yii\helpers\Url::to(['rbac/add-permission'])?>" class="btn btn-primary">添加权限</a >&nbsp;&nbsp;
<!--    <a href="--><?//=\yii\helpers\Url::to(['article_category/index'])?><!--" class="btn btn-primary">分类列表</a >-->
    <tr class="danger <!--第一行背景色设置-->">
        <th class="col-lg-2">权限名称</th>
        <th class="col-lg-4">权限描述</th>
        <th class="col-lg-1">操作</th>
    </tr>

    <?php  $num=0; foreach ($model as $k=>$v):
        if($num%2==0){  ////////更换列表背景颜色
            $num++; echo  '<tr class="info">';
        }else{
            $num++;  echo  '<tr class="success">';
        }
        ?>
        <th class="col-lg-2" ><?=$v->name?></th>
        <th class="col-lg-4"><?=$v->description?></th>
        <th class="col-lg-1">
            <?=\yii\bootstrap\Html::a('修改',['rbac/edit-permission','name'=>$v->name],['class'=>'btn btn-info btn-xs'])?>
            <?=\yii\bootstrap\Html::a('删除',['rbac/del-permission','name'=>$v->name],['class'=>'btn btn-danger btn-xs'])?>
        </th>
        </tr>
    <?php endforeach;  ?>
</table>
<?php
//输出分页工具条
/*echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pagination,
    'firstPageLabel'=>'|<<',
    'prevPageLabel'=>'<<',
    'nextPageLabel'=>'>>',
    'lastPageLabel'=>'>>|'
])*/
?>