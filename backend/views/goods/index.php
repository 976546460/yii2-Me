<style>
    .container {
        width: 100% /*重写表格包裹的div宽度，让表格满屏显示*/
    }

    table {
        table-layout: fixed;
        width: 100%;
    <!-- /* 设置表格中超出部分隐藏的前提，规定表格的宽的 并且设置table-layout: fixed属性*/
    -->
    }

    th, td {
        text-align: center;
        vertical-align: middle !important;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    <!-- 设置内容超出单元格部分隐藏 -->
    }
</style>
<table class="table  <!--应用表格样式-->
              table-hover <!--鼠标悬停状态作出响应-->
              table-condensed <!--让表格更加紧凑-->
              table-striped <!--每一行增加斑马条纹样式 -->"
>
    <a href="<?= \yii\helpers\Url::to(['goods/add']) ?>" class="btn btn-primary">添加商品</a>&nbsp;&nbsp;
    <?php
    $form = \yii\bootstrap\ActiveForm::begin([
        'method' => 'get',
        //get方式提交,需要显式指定action
        'action' => \yii\helpers\Url::to(['goods/index']),
        'options' => ['class' => 'form-inline']
    ]);//搜索模型$models
    echo \yii\helpers\Html::input('text', 'keyName', $models->keyName, ['width' => '100px', 'placeholder' => '商品名']) . "+";
    echo \yii\helpers\Html::input('text', 'GoodsSearchForm[sn]', $models->sn, ['width' => '100px', 'placeholder' => '货号']), "+";
    echo \yii\helpers\Html::input('text', 'GoodsSearchForm[minPrice]', $models->minPrice, ['width' => '100px', 'placeholder' => '￥']) . "-";
    echo \yii\helpers\Html::input('text', 'GoodsSearchForm[maxPrice]', $models->maxPrice, ['width' => '100px', 'placeholder' => '￥']) . '&nbsp';
    echo \yii\bootstrap\Html::submitButton('搜索', ['class' => 'btn btn-info']);
    \yii\bootstrap\ActiveForm::end();

    ?>
    <tr class="danger <!--第一行背景色设置-->">
        <th class="col-xs-0.5">编号</th>
        <th class="col-xs-1">商品名称</th>
        <th class="col-xs-1 ">LOGO图片</th>
        <th class="col-xs-1">商品分类</th>
        <th class="col-xs-1">市场价格</th>
        <th class="col-xs-1">商品价格</th>
        <th class="col-xs-0.5">库存</th>
        <th class="col-xs-1">货号</th>
        <th class="col-xs-0.5">是否在售</th>
        <th class="col-xs-0.5">状态</th>
        <th class="col-xs-0.5">排序</th>
        <th class="col-xs-1">添加时间</th>
        <th class="col-xs-2">操作</th>
    </tr>
    <?php foreach ($model as $k => $v):
        if ($k / 2 == 0) {  //更换列表背景颜色
            echo '<tr class="info">';
        } else {
            echo '<tr class="success">';
        }
        ?>
        <th class="col-xs-0.5"><?= $v->id ?></th>
        <th class="col-xs-1"><?= $v->name ?></th>
        <th class="col-xs-1"><?= \yii\bootstrap\Html::img($v->logo, ['width' => 60]) ?></th>
        <th class="col-xs-1"><?= $v->goodsCategory->name ?></th>
        <th class="col-xs-1"><?= $v->market_price ?></th>
        <th class="col-xs-1"><?= $v->shop_price ?></th>
        <th class="col-xs-0.5"><?= $v->stock ?></th>
        <th class="col-xs-1"><?= $v->sn ?></th>
        <th class="col-xs-0.5"><?= $v->is_on_sale == 1 ? '在售' : '下架'; ?></th>
        <th class="col-xs-0.5"><?= $v->status == 1 ? '正常' : '回收'; ?></th>
        <th class="col-xs-0.5"><?= $v->sort ?></th>
        <th class="col-xs-1"><?= date("Y-m-d H:i:s", $v->create_time) ?></th>
        <th class="col-xs-2">
            <?= \yii\bootstrap\Html::a('查看', ['goods/show', 'id' => $v->id], ['class' => 'btn btn-info btn-xs']) ?>
            <?= \yii\bootstrap\Html::a('修改', ['goods/edit', 'id' => $v->id], ['class' => 'btn btn-info btn-xs']) ?>
            <?= \yii\bootstrap\Html::a('删除', ['goods/del', 'id' => $v->id], ['class' => 'btn btn-danger btn-xs']) ?>
        </th>
        </tr>
    <?php endforeach; ?>
</table>

<?php
echo \yii\widgets\LinkPager::widget([
    'pagination' => $pagination,
    'firstPageLabel' => '第一页',
    'prevPageLabel' => '上一页',
    'nextPageLabel' => '下一页',
    'lastPageLabel' => '最末页',
])


?>