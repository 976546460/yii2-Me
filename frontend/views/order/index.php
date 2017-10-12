<?php
$this->registerCssFile('/style/goods.css');
$this->registerCssFile('/style/common.css');
$this->registerCssFile('/style/bottomnav.css');
$this->registerCssFile('/style/fillin.css');
$this->registerCssFile('/style/footer.css');
$this->registerJsFile('/js/cart2.js');
$this->registerCssFile('/style/cart.css');
?>
<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<form action="<?=\yii\helpers\Url::to(['order/index'])?>" method="post" >
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach($address as $addre):?>
                <p><input type="radio" value="<?= $addre['id'];?>" name="order[id]" /><?=$addre['name'].':  '.$addre['tel'].' '.$addre['city_intro']?>  </p>
                <?php endforeach; ?>
            </div>


        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($deliveryMode as $k=>$delivery ):
                      echo   $k==0?'<tr class="cur">':'<tr>';
                        ?>

                        <td>
                            <input type="radio" name="order[delivery_name]" value="<?php echo $delivery[0];?>" /><?php echo $delivery[0];?>

                        </td>
                        <td>￥<?php echo $delivery[1];?></td>
                        <td><?php echo $delivery[2];?></td>
                    </tr>
                    <?php endforeach  ?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php foreach($paymentMethod as $k=>$payment):
                       echo  $k==0?'<tr class="cur">':'<tr>';
                        ?>

                        <td class="col1"><input type="radio" name="order[payment_name]" value="<?php echo $payment[0];?>"/><?php echo $payment[0];?></td>
                        <td class="col2"><?php echo $payment[1]?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>
        <!-- 发票信息 end-->
        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php  foreach ($carts as $cart): ?>

                            <tr>
                    <td class="col1"><a href=""><img src="<?=$cart->goods->logo?>" alt="" /></a>  <strong><a href="#"><?=$cart->goods->name?></a></strong></td>
                    <td class="col3">￥<?=$cart->goods->shop_price?></td>
                    <td class="col4"> <?=$cart->amount?></td>
                    <td class="col5"><span><?=$cart->goods->shop_price*$cart->amount?></span></td>
                </tr>
                    <?php endforeach; ?>

                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span id="goodsCount">4 </span>件商品，总商品金额：
                                <em id="goodsCountMoney"></em>
                            </li>
                            <li>
                                <span>返现：</span>
                                <em>0</em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em>0</em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em id="moneyCount"></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
        <input type="hidden" id="hiddenInput" name="order[total]" value="">
        <input name="_csrf-frontend" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <a href=""> <input type="submit" /></a>
        <p>应付总额：<strong id="moneyCount"></strong></p>

    </div>
</div>
</form>
    <!-- 主体部分 end -->
<?php
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    var money=0;
        $('.col5 span').each(function(i,v) {
            money+=Number($(v).text());
            $('#moneyCount').text('￥'+money)
            $('#hiddenInput').val(money);
        })
console.debug(money);
JS
));
