<table  class="table">
    <a href="<?=\yii\helpers\Url::to(['brand/add'])?>" class="btn btn-primary">添加</a >
    <tr>
        <th>编号</th>
        <th>品牌名称</th>
        <th>品牌简介</th>
        <th>品牌LOGO</th>
        <th>当前状态</th>
        <th>排序规则</th>
        <th>操作</th>
    </tr>
<?php foreach ($model as $v): ?>
    <tr>
        <th><?=$v->id?></th>
        <th><?=$v->name?></th>
        <th><?=$v->intro?></th>
        <th><?=\yii\bootstrap\Html::img($v->logo,['height'=>50])?></th>
        <th><?=$v->status?></th>
        <th><?=$v->sort?></th>
        <th><?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$v->id],['class'=>'btn btn-info'])?>
            <?=\yii\bootstrap\Html::a('删除',['brand/del','id'=>$v->id],['class'=>'btn btn-primary'])?>
        </th>
    </tr>
<?php endforeach;  ?>

</table>

<?php
// 显示分页
echo \yii\widgets\LinkPager::widget([
    'pagination' => $pagination,
    'firstPageLabel'=>'|<<',
    'prevPageLabel'=>'<<',
    'nextPageLabel'=>'>>',
    'lastPageLabel'=>'>>|',
]);
?>