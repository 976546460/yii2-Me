<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/21
 * Time: 22:11
 */

namespace frontend\controllers;


use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsIntro;
use backend\models\GoodsPhoto;
use frontend\models\Cart;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class IndexController extends CommonController //继承自带提示页面的controller
{
    public $layout = 'index';

    public function actionIndex()
    {
        $goodsCategory = GoodsCategory::find()->where(['parent_id' => 0])->all();

        return $this->render('index', ['goodsCategory' => $goodsCategory]);

    }


    public function actionList($cate_id)
    {
        $goodsCategory = GoodsCategory::find()->where(['parent_id' => 0])->all();
        $goods = Goods::find()->where(['goods_category_id' => $cate_id])->all();

        return $this->render('list', ['goodsCategory' => $goodsCategory, 'goods' => $goods]);

    }

    public function actionGoodsIntro($id)
    {
//        $goodsCategory=GoodsCategory::find()->where(['parent_id'=>0])->all();
        //实列化
        $goods = Goods::findOne($id);//商品表
        $goodsCategory = GoodsCategory::findOne(['id' => $goods->goods_category_id]);//商品分类表
        $brand = Brand::findOne(['id' => $goods->brand_id]);//品牌表
        $goodsIntro = GoodsIntro::findOne(['goods_id' => $goods->id]);//商品详情表
        $goods_photo = GoodsPhoto::findAll(['goods_id' => $id]);
        return $this->render('goods-intro', [
            'goods' => $goods,
            'goodsCategory' => $goodsCategory,
            'brand' => $brand,
            'goodsIntro' => $goodsIntro,
            'goodsPhoto' => $goods_photo,
        ]);
    }

    //添加购物车
    /**
     * @throws NotFoundHttpException
     */
    public function actionAdd()
    {

        $goods_id = \Yii::$app->request->post('goods_id');
        var_dump($goods_id);
        $amount = \Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id' => $goods_id]);
        if ($goods == null) {
            throw new NotFoundHttpException('数据不存在');
        }
        //判断是否是游客
        if (\Yii::$app->user->isGuest) {//未登录  是游客
            //未登陆 先获取cooking中的数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if ($cookie == null) {
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);
            }

            //将商品的数据放入cookie中 如:id=2 amount=10 , 存入格式如:2=>10
            $cookies = \Yii::$app->response->cookies;//response中的cookie是可以读写的数据

            // 检查购物车中是否存在该商品 若果有集累加
            if (key_exists($goods_id, $cart)) {
                $cart[$goods_id] += $amount;
            } else {
                $cart[$goods_id] = $amount;
            };
            //在name为cart的键位对应的数组中添加数据
            $cookie = new \yii\web\Cookie([
                'name' => 'cart', 'value' => serialize($cart)
            ]);
            ////保存
            $cookies->add($cookie);
        } else {//已登录  不是游客
            $user = \Yii::$app->user->id;//获取用户id
            /* //查看cookie中是否有购物车的数据
             $cookies = \Yii::$app->request->cookies;
             $cookie = $cookies->get('cart');//查询cookie中 购物车的数据
             if ($cookie != null) {
                 $cart[] = unserialize($cookie->value);//将存入cookie中的序列化数据反序列化出来
                 foreach ($cart as $oneCart) {//遍历数组 得到的是每一个商品ID为键 商品数量为键值的数组
                     foreach (array_keys($oneCart) as $key) {//取出所有数组中的键位(商品id)组成一个新数组, 遍历出每一个商品id
                         //判断当前用户是否拥有该商品记录
                         $userCartDate = Cart::findOne(['goods_id' => $key]);//再通过查询每个商品在购物车数据表中是否存在
                         if ($userCartDate == null) {//不存在记录 添加在数据
                             $cart = new Cart();
                             //调用模型中的方法
                             $cart->insertCartData($user,$key,$oneCart[$key]);
                           /*  $cart->member_id = $user;
                             $cart->goods_id = intval($key);
                             $cart->amount = $oneCart[$key];
                             $cart->save();
                         } else { //存在 更新购物车中商品的购买数量
                             $userCartDate->amount += intval($oneCart[$key]);
                             $userCartDate->update();
                         }
                     }
                 }
                 //存入数据库完毕 清除cookie
                 \Yii::$app->response->cookies->remove('cart');
             }*/
            //判断数据表中是否存在该用户的购物车信息
            $cartData = Cart::find()->where(['member_id' => $user])->andWhere(['goods_id' => $goods_id])->all();//注意:这是一个满足where条件的集合,但是逻辑上在数据表中只能存在一条数据
            if (empty($cartData)) {//不存在 就存入购买的商品信息
                $cart = new Cart();
                //调用模型中的方法
                $cart->insertCartData($user, $goods_id, $amount);
            } else {//存在就更新数量
                $cartData[0]->amount += intval($amount);
                $cartData[0]->update();//累加并保存
            }
        }
        return $this->redirect(['index/cart']);
    }

//购物车主页面
    public function actionCart()
    {

        //判断是否是游客 是->在cookie中取出购物车信息
        if (\Yii::$app->user->isGuest) {
            //获取cookie中的商品数据和id
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get(('cart'));
            if ($cookie == null) {
                //无记录
                //当 用户购物车中没有记录就 跳转到首页
                return $this->redirect('index.html');
            } else {
                //存在数据  就将已序列化的数据 反序列化
                $cart = unserialize($cookie->value);
            };
            $models = [];
            foreach ($cart as $goods_id => $amount) {

                $goods = Goods::findOne(['id' => $goods_id])->attributes;
                $goods['amount'] = $amount;
                $models[] = $goods;
            }
        } else {//当前用户不是游客 从数据库中读取信息
            //直接从数据库读取购物车记录
            $model = Cart::findAll(['member_id' => \Yii::$app->user->id]);
            if (empty($model)) {
                //无记录 跳转首页
                return $this->redirect('index.html');
            };
            foreach ($model as $data) {
                $goods = Goods::find()->where(['id' => $data['goods_id']])->asArray()->all();
                foreach ($goods as $v) {
                    $v['amount'] = $data['amount'];
                    $models[] = $v;
                }
            }

        }
//var_dump($models);exit;
        return $this->render('cart', ['models' => $models]);

    }


    public function actionUpdateCart()
    {
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id' => $goods_id]);
        if ($goods == null) {
            throw new NotFoundHttpException('商品不存在');
        }
        if (\Yii::$app->user->isGuest) {
            //未登录
            //先获取cookie中的购物车数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if ($cookie == null) {
                //cookie中没有购物车数据
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);
            }
            //将商品id和数量存到cookie   id=2 amount=10  id=1 amount=3
            $cookies = \Yii::$app->response->cookies;
            if ($amount) {
                $cart[$goods_id] = $amount;
            } else {
                if (key_exists($goods['id'], $cart)) {
                    unset($cart[$goods_id]);
                };
            }

//            $cart = [$goods_id=>$amount];
            $cookie = new Cookie([//保存键位为cart 值为$cart的数组
                'name' => 'cart', 'value' => serialize($cart)
            ]);
            $cookies->add($cookie);
            var_dump($cookies);
        } else {
            //已登录  修改数据库里面的购物车数据
            $user = \Yii::$app->user->id;
            $goods_id = \Yii::$app->request->post('goods_id');
            $amount = \Yii::$app->request->post('amount');
            $goods = Goods::findOne(['id' => $goods_id]);
            //找到当前用户对应的商品
            $goods = Cart::find()->where(['goods_id' => intval($goods_id)])->andWhere(['member_id' => intval($user)])->all();
            if ($amount) {
                $goods[0]->amount = $amount;//更新数据
                $goods[0]->update();//保存
            } else {//删除数据
                $goods[0]->delete();
            }
        }
    }
}