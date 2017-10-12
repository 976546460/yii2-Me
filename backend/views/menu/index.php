<style>
    table{
        table-layout: fixed;width:1200px  <!--/* 设置表格中超出部分隐藏的前提，规定表格的宽的 并且设置table-layout: fixed属性*/ -->
    }
    th,td{
        text-align: center;
        vertical-align: middle!important;
        white-space: nowrap;text-overflow: ellipsis;overflow: hidden; <!--设置内容超出单元格部分隐藏  -->
    }
</style>
<table  class="table <!--应用表格样式-->
              table-hover <!--鼠标悬停状态作出响应-->
              table-condensed <!--让表格更加紧凑-->
              table-striped <!--每一行增加斑马条纹样式 -->"
>
    <a href="<?=\yii\helpers\Url::to(['article_category/add'])?>" class="btn btn-primary">添加分类</a >&nbsp;&nbsp;
    <a href="<?=\yii\helpers\Url::to(['article/index'])?>" class="btn btn-info">文章列表</a >
    <tr class="danger <!--第一行背景色设置-->">
        <th  class="col-lg-1"></th>
        <th  class="col-lg-2 ">菜单名称</th>
        <th  class="col-lg-3">路由地址</th>
        <th  class="col-lg-2">操作</th>
    </tr>
    <?php
                var_dump($model);exit;

    foreach ($model as $k=> $value):

        if($k/2==0){  //更换列表背景颜色
            echo  '<tr class="info">';
        }else{
            echo  '<tr class="success">';
        }


        ?>
        <th class="col-lg-1"><?=$value['id']?></th>
        <th class="col-lg-2"><?=$value['label']?></th>
        <th class="col-lg-3"><?=$value['url']?></th>
        <th class="col-lg-2">
            <?=\yii\bootstrap\Html::a('修改',['article_category/edit','id'=>$value['id']],['class'=>'btn btn-info btn-xs'])?>
            <?=\yii\bootstrap\Html::a('删除',['article_category/del','id'=>$value['id']],['class'=>'btn btn-danger btn-xs'])?>
        </th>
        </tr>
    <?php  endforeach;  ?>

</table>

<?php
/*echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pagination,
    'firstPageLabel'=>'第一页',
    'prevPageLabel'=>'上一页',
    'nextPageLabel'=>'下一页',
    'lastPageLabel'=>'最末页',
])*/


?>