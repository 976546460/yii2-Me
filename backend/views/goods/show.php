<style>
    img{
        width: 1100px;
    }

    .table-condensed {
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
    <a href="<?= \yii\helpers\Url::to(['goods/index']) ?>" class="btn btn-primary">返回列表</a>&nbsp;&nbsp;
    <tr class="danger">
        <th class="col-xs-2">商品参数类别</th>
        <th class="col-xs-8">参数状态</th>
    </tr>
    <tr class="info">
        <th class="col-xs-1">商品名称</th>
        <th class="col-xs-8"><?= $goods->name ?></th>
    </tr>

    <!--        <th  class="col-xs-1 ">LOGO图片</th>-->
    <tr class="success">
        <th class="col-xs-1">商品分类</th>
        <th class="col-xs-8"><?= $goodsCategory->name ?></th>
    </tr>
    <tr class="info">
        <th class="col-xs-1">市场价格</th>
        <th class="col-xs-10"><?= $goods->market_price ?></th>
    </tr>
    <tr class="success">
        <th class="col-xs-1">商品价格</th>
        <th class="col-xs-10"><?= $goods->shop_price ?></th>
    </tr>
    <tr class="info">
        <th class="col-xs-0.5">库存</th>
        <th class="col-xs-10"><?= $goods->stock ?></th>
    </tr>
    <tr class="success">
        <th class="col-xs-1">货号</th>
        <th class="col-xs-10"><?= $goods->sn ?></th>
    </tr>
    <tr class="info">
        <th class="col-xs-0.5">是否在售</th>
        <th class="col-xs-10"><?= $goods->is_on_sale==1?'销售中':'下架' ?></th>
    </tr>
    <tr class="success">
        <th class="col-xs-0.5">状态</th>
        <th class="col-xs-10"><?= $goods->status==1?'正常':'下架'; ?></th>
    </tr>
    <tr class="info">
        <th class="col-xs-0.5">排序</th>
        <th class="col-xs-10"><?= $goods->sort ?></th>
    </tr>
    <tr class="success">
        <th class="col-xs-1">添加时间</th>
        <th class="col-xs-10"><?= date("Y-m-d H:i:s", $goods->create_time) ?></th>
    </tr>
    <tr class="success">
        <th class="col-xs-1">商品LOGO</th>
        <th class="col-xs-10"><?= \yii\bootstrap\Html::img($goods->logo, ['style' => ['width'=>'70px']]) ?></th>
    </tr>

</table>
<h1>商品大图：</h1>
<?php if ($goodsPhoto != null) {

    foreach ($goodsPhoto as $imgUrl) {
        echo "<img class='photo' src=$imgUrl->photoImage>";

    }
}

?>
    <h2>商品图文详解：</h2>

<?= $goodsIntro->content ?>