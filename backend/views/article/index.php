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
    <a href="<?=\yii\helpers\Url::to(['article/add'])?>" class="btn btn-primary">添加文章</a >&nbsp;&nbsp;
    <a href="<?=\yii\helpers\Url::to(['article_category/index'])?>" class="btn btn-primary">分类列表</a >
    <tr class="danger <!--第一行背景色设置-->">
        <th  class="col-lg-1">编号</th>
        <th  class="col-lg-2">文章名称</th>
        <th  class="col-lg-3">文章简介</th>
        <th  class="col-lg-2">所属分类</th>
        <th  class="col-lg-1">当前排序</th>
        <th  class="col-lg-0.5">当前状态</th>
        <th  class="col-lg-2">操作</th>
    </tr>
    <?php foreach ($model as $k=>$v):
        if($k/2==0){  ////////更换列表背景颜色
           echo  '<tr class="info">';
        }else{
            echo  '<tr class="success">';
        }
        ?>
                <th class="col-lg-1"><?=$v->id?></th>
                <th class="col-lg-2" ><?=$v->name?></th>
                <th class="col-lg-3"><?=$v->intro?></th>
                <th class="col-lg-2"><?=$v->article_categorys->name?></th>
                <th class="col-lg-1"><?=$v->sort?></th>
                <th class="col-lg-0.5"><?=$v->status==1?'正常':'隐藏'; ?></th>
                <th class="col-lg-2">
                    <?=\yii\bootstrap\Html::a('查看',['article_detail/show','id'=>$v->id],['class'=>'btn btn-primary btn-xs'])?>
                    <?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$v->id],['class'=>'btn btn-info btn-xs'])?>
                    <?=\yii\bootstrap\Html::a('删除',['article/del','id'=>$v->id],['class'=>'btn btn-danger btn-xs'])?>
                </th>
            </tr>
        <?php endforeach;  ?>
</table>
<?php
//输出分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pagination,
    'firstPageLabel'=>'|<<',
    'prevPageLabel'=>'<<',
    'nextPageLabel'=>'>>',
    'lastPageLabel'=>'>>|'
])
?>