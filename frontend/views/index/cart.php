<?php
$this->registerCssFile('/style/goods.css');
$this->registerCssFile('/style/common.css');
$this->registerCssFile('/style/bottomnav.css');
$this->registerCssFile('/style/footer.css');
$this->registerCssFile('/style/cart.css');
//<!--引入jqzoom css -->
$this->registerJsFile('/js/cart1.js');

?>

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="mycart w990 mt10 bc">
    <h2><span>我的购物车</span></h2>
    <table>
        <thead>
        <tr>
            <th class="col1">商品名称</th>
            <th class="col3">单价</th>
            <th class="col4">数量</th>
            <th class="col5">小计</th>
            <th class="col6">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($models as $model): ?>

            <tr data-goods_id="<?= $model['id'] ?>">
                <td class="col1"><a href=""><img src="<?= $model['logo'] ?>" alt=""/></a> <strong><a
                            href=""><?= $model['name'] ?></a></strong>
                </td>
                <td class="col3">￥<span><?= $model['shop_price'] ?></span></td>
                <td class="col4">
                    <a href="javascript:;" class="reduce_num"></a>
                    <input type="text" name="amount" value="<?= $model['amount'] ?>" class="amount"/>
                    <a href="javascript:;" class="add_num"></a>
                </td>
                <td class="col5">￥<span class="count_money"><?= ($model['shop_price'] * $model['amount']) ?></span></td>
                <td class="del_goods"><a href="#">删除</a></td>
            </tr>
        <?php

        endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6">购物金额总计： <strong>￥ <span
                        id="total"><?= ($model['shop_price'] * $model['amount']) ?></span></strong></td>
        </tr>
        </tfoot>
    </table>
    <div class="cart_btn w990 bc mt10">
        <a href="" class="continue">继续购物</a>
        <a href="<?=\yii\helpers\Url::to(['order/index'])?>" class="checkout">结 算</a>
    </div>
</div>

<?php
$url = \yii\helpers\Url::to(['index/update-cart']);
$token = Yii::$app->request->csrfToken;//跳过raul 验证规则
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    //计算总金额 
    var count=0;
               $('.count_money').each(function(index,ele) {
                    count+= Number($(ele).text());
               })
       $('#total').text(count);
         console.debug(count);
        
    
    
    
            //监听 + - 货物数量的点击事件
        $('.reduce_num,.add_num').click(function() {
         // console.debug($(this)); //检测当前的是否添加了点击事件
         var goods_id=$(this).closest('tr').attr('data-goods_id');//得到当前tr中的goods_id
         var amount=$(this).parent().find('.amount').val();//得到当前数量输入框中的值
         //发送ajax post请求到site/update-cart  {goods_id,amount}
         $.post("$url",{goods_id:goods_id,amount:amount,"_csrf-frontend":"$token"});
        });

    //删除按钮
        $(".del_goods").click(function(){
            if(confirm('是否删除该商品')){
                var goods_id = $(this).closest('tr').attr('data-goods_id');
                //发送ajax post请求到site/update-cart  {goods_id,amount}
                $.post("$url",{goods_id:goods_id,amount:0,"_csrf-frontend":"$token"});
                //删除当前商品的标签
                $(this).closest('tr').remove();
            }
        });
JS
));
?>

