<?php

namespace frontend\controllers;

use backend\models\Goods;
use Codeception\Lib\Generator\Helper;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;

class OrderController extends \yii\web\Controller
{
    public $layout = 'order';

    //点击结算后跳转到此方法核对订单信息
    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest){//未登录状态点击结算将 调回登陆首页
            //附带当前地址参数 登陆后根据这个地址跳转会回来
            return $this->redirect(['user/login','url'=>'/order/index.html']);
        }
        $user_id = \Yii::$app->user->id;
        //定义送货方式填入数组  在页面进行遍历
        $deliveryMode = [
            ['普通快递送货上门', 10.00, '每张订单不满499.00元,运费15.00元'],
            ['特快专递', 40.00, '每张订单不满499.00元,运费40.00元'],
            ['加急快递送货上门', 40.00, '每张订单不满499.00元,运费40.00元'],
            ['平邮', 10.00, '每张订单不满499.00元,运费15.00元'],
        ];
        //定义支付方式:
        $paymentMethod = [
            ['货到付款', '送货上门后再收款，支持现金、POS机刷卡、支票支付'],
            ['在线支付', '即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
            ['上门自提', '自提时付款，支持现金、POS刷卡、支票支付'],
            ['邮局汇款', '通过快钱平台收款 汇款后1-3个工作日到账']
        ];
              //购物车中的数据
        $carts = Cart::find()->where(['member_id' => \Yii::$app->user->id])->all();

        if (\Yii::$app->request->post()) {

            $data = \Yii::$app->request->post();
            //配送地址详细信息
            $address = Address::findOne(['member_id' => $user_id]);
            $order = New Order();
            $order->member_id = $user_id;// '用户id',
            $order->name = $address->name;// '收货人',
            $order->province = $address->province;//' => '省',
            $order->city = $address->city;//市',
            $order->area = $address->county;// '县',
            $order->address = $address->city_intro;// '详细地址',
            $order->tel = $address->tel;//'电话号码',
            $order->delivery_id = 1; // '配送方式id',
            $order->delivery_name = $data['order']['delivery_name'];  // '配送方式名称',
            foreach ($deliveryMode as $value) {/* 遍历找出与配送名称所在数组 到找配送价格*/
                if ($value[0] == $data['order']['delivery_name']) {
                    $delivery_price = $value[1];
                }
            }

            $order->delivery_price = $delivery_price;//'配送方式价格',
            $order->payment_id = 1;//'支付方式id',
            $order->payment_name = $data['order']['payment_name'];// '支付方式名称',
            $order->total = $data['order']['total'];//'订单金额',
            $order->status = 2;// '订单状态（0取消 1待付款 2待发货 3待收货 4完成）',
            $order->trade_no = date('YmdHis') . rand(1111, 9999);//第三方支付交易号
            $order->create_time = time();// '创建时间'
            //保存
            //创建事物
            $transaction=\Yii::$app->db->beginTransaction();
            try{
                $r=$order->save();
                if ($r) {//生成订单商品详情表保存数据
                    $orderGoods = new OrderGoods();
                    $order_id=$order->id;
                    foreach ($carts as $cart) {//遍历购物车找出 购物车中商品id对应的goods表中的商品,
                        $goods = Goods::find()->asArray()->where(['id' => $cart->goods_id])->all();
                        if(empty($goods)){
                            throw  new  Exception('商品不存在');
                        }
                        $orderGoods->goods_id = $goods[0]['id'];//> '商品id',
                        $orderGoods->goods_name = $goods[0]['name'];// => '商品名称
                        $orderGoods->logo = $goods[0]['logo'];//    '图片',
                        if($cart->amount>$goods[0]['stock']){
                            throw new Exception('库存不足,请重新选择数量');
                        }
                        $orderGoods->amount = $cart->amount;//    '数量',
                        $orderGoods->total =$cart->amount * $goods[0]['shop_price'];//     小计',
                        $orderGoods->insert();
                        $orderGoods->order_id = $order_id; //> '订单id',
                        $orderGoods->save();
                    }
                }
                //提交
                $transaction->commit();
                //跳转
                return $this->redirect(['order/complete']);
            }catch (Exception $e){
                //回滚
                $transaction->rollBack();
                return $e->getMessage(); //返回自定义异常信息
            }

        } else {
            //送货地址
            $address = Address::find()->where(['member_id' => $user_id])->all();
            //商品数据
            return $this->render('index',
                [
                    'deliveryMode' => $deliveryMode,
                    'paymentMethod' => $paymentMethod,
                    'address' => $address,
                    'carts' => $carts,
                ]);
        }
    }

    //订单支付完成跳转页面
    public function actionComplete(){

        return $this->render('complete');
    }
}
