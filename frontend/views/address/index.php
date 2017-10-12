<?php
$this->registerCssFile('/style/home.css');
$this->registerCssFile('/style/address.css');
$this->registerCssFile('/style/bottomnav.css');
$this->registerCssFile('/style/footer.css');
$this->registerJsFile('/js/home.js')
?>
    <!-- 导航条部分 start -->
    <div class="nav w1210 bc mt10">
        <!--  商品分类部分 start-->
        <div class="category fl cat1"> <!-- 非首页，需要添加cat1类 -->
            <div class="cat_hd off">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，鼠标滑过时展开菜单则将off类换成on类 -->
                <h2>全部商品分类</h2>
                <em></em>
            </div>

            <div class="cat_bd none" style="display: none;">

                <div class="cat item1">
                    <h3 class=""><a href="">图像、音像、数字商品</a> <b></b></h3>
                    <div class="cat_detail" style="display: none;">
                        <dl class="dl_1st">
                            <dt><a href="">电子书</a></dt>
                            <dd>
                                <a href="">免费</a>
                                <a href="">小说</a>
                                <a href="">励志与成功</a>
                                <a href="">婚恋/两性</a>
                                <a href="">文学</a>
                                <a href="">经管</a>
                                <a href="">畅读VIP</a>
                            </dd>
                        </dl>

                        <dl>
                            <dt><a href="">数字音乐</a></dt>
                            <dd>
                                <a href="">通俗流行</a>
                                <a href="">古典音乐</a>
                                <a href="">摇滚说唱</a>
                                <a href="">爵士蓝调</a>
                                <a href="">乡村民谣</a>
                                <a href="">有声读物</a>
                            </dd>
                        </dl>

                        <dl>
                            <dt><a href="">音像</a></dt>
                            <dd>
                                <a href="">音乐</a>
                                <a href="">影视</a>
                                <a href="">教育音像</a>
                                <a href="">游戏</a>
                            </dd>
                        </dl>

                        <dl>
                            <dt><a href="">文艺</a></dt>
                            <dd>
                                <a href="">小说</a>
                                <a href="">文学</a>
                                <a href="">青春文学</a>
                                <a href="">传纪</a>
                                <a href="">艺术</a>
                                <a href="">经管</a>
                                <a href="">畅读VIP</a>
                            </dd>
                        </dl>

                        <dl>
                            <dt><a href="">人文社科</a></dt>
                            <dd>
                                <a href="">历史</a>
                                <a href="">心理学</a>
                                <a href="">政治/军事</a>
                                <a href="">国学/古籍</a>
                                <a href="">哲学/宗教</a>
                                <a href="">社会科学</a>
                            </dd>
                        </dl>

                        <dl>
                            <dt><a href="">经管励志</a></dt>
                            <dd>
                                <a href="">经济</a>
                                <a href="">金融与投资</a>
                                <a href="">管理</a>
                                <a href="">励志与成功</a>
                            </dd>
                        </dl>

                        <dl>
                            <dt><a href="">人文社科</a></dt>
                            <dd>
                                <a href="">历史</a>
                                <a href="">心理学</a>
                                <a href="">政治/军事</a>
                                <a href="">国学/古籍</a>
                                <a href="">哲学/宗教</a>
                                <a href="">社会科学</a>
                            </dd>
                        </dl>

                        <dl>
                            <dt><a href="">生活</a></dt>
                            <dd>
                                <a href="">烹饪/美食</a>
                                <a href="">时尚/美妆</a>
                                <a href="">家居</a>
                                <a href="">娱乐/休闲</a>
                                <a href="">动漫/幽默</a>
                                <a href="">体育/运动</a>
                            </dd>
                        </dl>

                        <dl>
                            <dt><a href="">科技</a></dt>
                            <dd>
                                <a href="">科普</a>
                                <a href="">建筑</a>
                                <a href="">IT</a>
                                <a href="">医学</a>
                                <a href="">工业技术</a>
                                <a href="">电子/通信</a>
                                <a href="">农林</a>
                                <a href="">科学与自然</a>
                            </dd>
                        </dl>

                    </div>
                </div>

                <div class="cat">
                    <h3><a href="">家用电器</a><b></b></h3>
                    <div class="cat_detail">
                        <dl class="dl_1st">
                            <dt><a href="">大家电</a></dt>
                            <dd>
                                <a href="">平板电视</a>
                                <a href="">空调</a>
                                <a href="">冰箱</a>
                                <a href="">洗衣机</a>
                                <a href="">热水器</a>
                                <a href="">DVD</a>
                                <a href="">烟机/灶具</a>
                            </dd>
                        </dl>

                        <dl>
                            <dt><a href="">生活电器</a></dt>
                            <dd>
                                <a href="">取暖器</a>
                                <a href="">加湿器</a>
                                <a href="">净化器</a>
                                <a href="">饮水机</a>
                                <a href="">净水设备</a>
                                <a href="">吸尘器</a>
                                <a href="">电风扇</a>
                            </dd>
                        </dl>

                        <dl>
                            <dt><a href="">厨房电器</a></dt>
                            <dd>
                                <a href="">电饭煲</a>
                                <a href="">豆浆机</a>
                                <a href="">面包机</a>
                                <a href="">咖啡机</a>
                                <a href="">微波炉</a>
                                <a href="">电磁炉</a>
                                <a href="">电水壶</a>
                            </dd>
                        </dl>

                        <dl>
                            <dt><a href="">个护健康</a></dt>
                            <dd>
                                <a href="">剃须刀</a>
                                <a href="">电吹风</a>
                                <a href="">按摩器</a>
                                <a href="">足浴盆</a>
                                <a href="">血压计</a>
                                <a href="">体温计</a>
                                <a href="">血糖仪</a>
                            </dd>
                        </dl>

                        <dl>
                            <dt><a href="">五金家装</a></dt>
                            <dd>
                                <a href="">灯具</a>
                                <a href="">LED灯</a>
                                <a href="">水槽</a>
                                <a href="">龙头</a>
                                <a href="">门铃</a>
                                <a href="">电器开关</a>
                                <a href="">插座</a>
                            </dd>
                        </dl>
                    </div>
                </div>

                <div class="cat">
                    <h3><a href="">手机、数码</a><b></b></h3>
                    <div class="cat_detail none">

                    </div>
                </div>

                <div class="cat">
                    <h3><a href="">电脑、办公</a><b></b></h3>
                    <div class="cat_detail none">

                    </div>
                </div>

                <div class="cat">
                    <h3><a href="">家局、家具、家装、厨具</a><b></b></h3>
                    <div class="cat_detail none">

                    </div>
                </div>

                <div class="cat">
                    <h3><a href="">服饰鞋帽</a><b></b></h3>
                    <div class="cat_detail none">

                    </div>
                </div>

                <div class="cat">
                    <h3><a href="">个护化妆</a><b></b></h3>
                    <div class="cat_detail none">

                    </div>
                </div>

                <div class="cat">
                    <h3><a href="">礼品箱包、钟表、珠宝</a><b></b></h3>
                    <div class="cat_detail none">

                    </div>
                </div>

                <div class="cat">
                    <h3><a href="">运动健康</a><b></b></h3>
                    <div class="cat_detail none">

                    </div>
                </div>

                <div class="cat">
                    <h3><a href="">汽车用品</a><b></b></h3>
                    <div class="cat_detail none">

                    </div>
                </div>

                <div class="cat">
                    <h3><a href="">母婴、玩具乐器</a><b></b></h3>
                    <div class="cat_detail none">

                    </div>
                </div>

                <div class="cat">
                    <h3><a href="">食品饮料、保健食品</a><b></b></h3>
                    <div class="cat_detail none">

                    </div>
                </div>

                <div class="cat">
                    <h3><a href="">彩票、旅行、充值、票务</a><b></b></h3>
                    <div class="cat_detail none">

                    </div>
                </div>

            </div>

        </div>
        <!--  商品分类部分 end-->

        <div class="navitems fl">
            <ul class="fl">
                <li class="current"><a href="">首页</a></li>
                <li><a href="">电脑频道</a></li>
                <li><a href="">家用电器</a></li>
                <li><a href="">品牌大全</a></li>
                <li><a href="">团购</a></li>
                <li><a href="">积分商城</a></li>
                <li><a href="">夺宝奇兵</a></li>
            </ul>
            <div class="right_corner fl"></div>
        </div>
    </div>
    <!-- 导航条部分 end -->
    <div style="clear:both;"></div>

    <!-- 页面主体 start -->
    <div class="main w1210 bc mt10">
        <div class="crumb w1210">
            <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
        </div>

        <!-- 左侧导航菜单 start -->
        <div class="menu fl">
            <h3>我的XX</h3>
            <div class="menu_wrap">
                <dl>
                    <dt>订单中心 <b></b></dt>
                    <dd><b>.</b><a href="">我的订单</a></dd>
                    <dd><b>.</b><a href="">我的关注</a></dd>
                    <dd><b>.</b><a href="">浏览历史</a></dd>
                    <dd><b>.</b><a href="">我的团购</a></dd>
                </dl>

                <dl>
                    <dt>账户中心 <b></b></dt>
                    <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                    <dd><b>.</b><a href="">账户余额</a></dd>
                    <dd><b>.</b><a href="">消费记录</a></dd>
                    <dd><b>.</b><a href="">我的积分</a></dd>
                    <dd><b>.</b><a href="">收货地址</a></dd>
                </dl>

                <dl>
                    <dt>订单中心 <b></b></dt>
                    <dd><b>.</b><a href="">返修/退换货</a></dd>
                    <dd><b>.</b><a href="">取消订单记录</a></dd>
                    <dd><b>.</b><a href="">我的投诉</a></dd>
                </dl>
            </div>
        </div>
        <!-- 左侧导航菜单 end -->

        <!-- 右侧内容区域 start -->
        <div class="content fl ml10">
            <div class="address_hd">
                <h3>收货地址薄</h3>
                <?php
                foreach ($date as $value):
                    $string = "";
                    $string .= $value['name'] . ': ';
                    $string .= $value['province'] . ' ';
                    $string .= $value['city'] . ' ';
                    $string .= $value['county'] . '<br/>';
                    $string .= $value['city_intro'] . ' ';
                    $string .= $value['tel'] . ' ';
                    ?>
                    <dl>
                        <dt>
                            <?php echo $string; ?>
                        </dt>
                        <dd>
                            <a href="<?= \yii\helpers\Url::to('@web/address/edit.html?id=' . $value['id']); ?>">修改</a>
                            <a href="<?= \yii\helpers\Url::to('@web/address/del.html?id=' . $value['id']); ?>">删除</a>
                            <a href="<?= \yii\helpers\Url::to('@web/address/default.html?id=' . $value['id']); ?>">设为默认地址</a>
                        </dd>
                    </dl>
                <?php endforeach; ?>
            </div>

            <div class="address_bd mt10">
                <h4>新增收货地址</h4>
                <?php
                if (isset($edit->city)) {
                    echo '<form action = "' . \yii\helpers\Url::to(['@web/address/edit', 'id' => $edit->id]) . '" name = "address_form" method = "post" >';
                } else {

                    echo '<form action = "' . \yii\helpers\Url::to(['@web/address/add']) . '" name = "address_form" method = "post" >';
                } ?>
                <ul>
                    <li>
                        <label for=""><span>*</span>收 货 人：</label>
                        <input type="text" name="address[name]" class="txt"
                               value="<?php echo isset($edit) ? $edit->name : "" ?>"/>
                    </li>
                    <li>
                        <label for=""><span>*</span>所在省市：</label>
                        <select name="address[province]" id="province"/>
                        <option value="<?php echo (isset($edit)&&$edit!=null) ? $edit->province : "" ?>">
                            <?php echo (isset($edit)&&$edit!=null) ? $edit->province : "=请选择=" ?></option>
                        </select>
                        <select name="address[city]" id="city"/>
                        <option value="<?php echo (isset($edit)&&$edit!=null) ? $edit->city : "" ?>">
                            <?php echo (isset($edit)&&$edit!=null) ? $edit->city : "=请选择=" ?></option>
                        </select>
                        <select name="address[county]" id="county"/>
                        <option value="<?php echo (isset($edit)&&$edit!=null) ? $edit->county : "" ?>">
                            <?php echo (isset($edit)&&$edit!=null) ? $edit->county : "=请选择=" ?></option>
                        </select>
                    </li>
                    <li>
                        <label for=""><span>*</span>详细地址：</label>
                        <input type="text" name="address[city_intro]" class="txt address"
                               value="<?php echo isset($edit) ? $edit->city_intro : "" ?>"/>
                    </li>
                    <li>
                        <label for=""><span>*</span>手机号码：</label>
                        <input type="text" name="address[tel]" class="txt"
                               value="<?php echo isset($edit) ? $edit->tel : "" ?>"/>
                    </li>
                    <li>
                        <label for="">&nbsp;</label>
                        <input type="checkbox" name="address[default_address]" class="check"
                               value="<?php echo isset($edit) ? 'checked=checked' : ''?>"/>设为默认地址
                    </li>
                    <li>
                        <input name="_csrf-frontend" type="hidden" id="_csrf"
                               value="<?= Yii::$app->request->csrfToken ?>">

                        <label for="">&nbsp;</label>

                        <input type="submit" name="" class="btn" value="保存"/>
                    </li>
                </ul>
                </form>
            </div>

        </div>
        <!-- 右侧内容区域 end -->
    </div>
    <!-- 页面主体 end-->

    <div style="clear:both;"></div>


    </body>
    </html>
<?php
/**
 * @var $this \Yii\web\view
 * */

$this->registerJsFile('@web/js/address.js');
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
  //填充省的数据
  
  $(address).each(function() {
      // console.log(this.name);//查看是否将省的资料取出来
      //将每个省的名称填充到下拉框
      var  option= '<option value="'+this.name+'">'+this.name+'</option>';
      //将下拉框option载入'省'对应的select框中页面;
      $("#province").append(option);
  });
   //切换（选中）省，读取该省对应的市，更新到市下拉框
       $("#province").change(function() {//获取到当前点击的选中的省
         var province=$(this).val();
         // console.log(province);//查看是否选中可了省级
         //将省级对应的市级数据遍历出来
         $(address).each(function() {
           if(this.name==province){
               var option ='<option name="" >=请选择=</option>';
               $(this.city).each(function() {//遍历省对应的数据  加入市级下拉框
                  option+='<option name="'+this.name+'" >'+this.name+'</option>';
               });//将市级数据动态添加到对应下拉框
               $("#city").html(option);
               $(this.area).each(function(i,v) {//遍历县级
                 option+='<option valiue="'+v+'">'+v+'</option>'
              });
              $("#county").html(option);//活动添加县级
           }
         })
         //切换（选中）市，读取该市 对应的县，更新到县下拉框
        $("#city").change(function() {
          var city=$(this).val();
          $(address).each(function() {//遍历省
            if(this.name==$("#province").val()){//确认已选中的省
                $(this.city).each(function() {//遍历市级
                  if(this.name==city){//确认选中市级
                    var option = '<option value="">=请选择县=</option>';//设为默认值
                      $(this.area).each(function(i,v) {//遍历县级
                         option+='<option valiue="'+v+'">'+v+'</option>'
                      });
                      $("#county").html(option);//活动添加县级
                  }
                })
            }
          })
        })
       })
JS
));
//处理三级联动修改 或者提交失败回显

?>