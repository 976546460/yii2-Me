<?php
namespace frontend\controllers;


use backend\models\Article;
use backend\models\Article_category;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Member;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ApiController extends Controller
{
    // 1 关闭Csrf验证 否则会显示无法验证数据
    public $enableCsrfValidation = false;

    // 2 将所有调用接口后返回的数据设置为json数据格式
    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();
    }
    //api接口添加
    //传入一个品牌id将该品牌下的商品以json格式返回
    public function actionGetGoodsByBrand()
    {
        if ($brand_id = \Yii::$app->request->get('brand_id')) {
            $goods = Goods::find()->where(['brand_id' => $brand_id])->asArray()->all();
            return ['status' => 1, 'errormsg' => "", 'result' => $goods];
        } else {
            return ['status' => -1, 'errorsmg' => '分类不存在', 'result' => ""];
        }
    }

    //会员注册
    public function actionRegister()
    {
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $member = new Member();
            $member->username = $request->post('username');
            $member->password_hash = $request->post('password_hash');
            $member->password = $request->post('password');
            $member->email = $request->post('email');
            $member->tel = $request->post('tel');
            $member->code = $request->post('code');
            $member->smsCode = $request->post('smsCode');
            if ($member->validate()) {
                $member->save();
                $member->password_hash = "*****";
                return ['status' => 1, 'errormsg' => "", 'result' => $member->toArray()];
            }
            return ['status' => -1, 'errormsg' => $member->getErrors(), 'result' => ""];
//           var_dump($data);

        };
    }

    //会员登录
    public function actionLogin()
    {
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $user = Member::findOne(['username' => $request->post('username')]);
            if ($user && \Yii::$app->security->validatePassword($request->post('password'), $user->password_hash)) {
                \Yii::$app->user->login($user);
                //将重要的密码信息等设置为空再分配到页面
                $user->password_hash = "";
                $user->auth_key = "";
                return ['status' => '1', 'errormsg' => '登录成功', 'result' => $user->toArray()];
            }
            return ['status' => '-1', 'errormsg' => '账号或密码错误'];
        }
        return ['status' => '-1', 'errormsg' => '请使用post请求'];
    }

    //修改密码   bug 修改密码始终返回false
    public function actionEditUser()
    {
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $user = Member::findOne(['id' => $request->post('id')]);
            if ($request->post('password') == $request->post('password_hash')) {
                $user->username = $request->post('username');
                $user->password_hash = $request->post('password_hash');
                $user->password = $request->post('password');
                $user->smsCode = $request->post('smsCode');
                $user->update();
                return ['status' => '1', 'errormsg' => ''];
            }
            return ['status' => '-1', 'errormsg' => '两次密码不一致'];
        }
        return ['status' => '-1', 'errormsg' => '请使用post请求'];
    }

    //图片验证码
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength' => 4,
                'maxLength' => 4,
            ],
        ];
    }


    //手机验证码
    public function actionSendSms()
    {
        $tel = \Yii::$app->request->post('tel');
        if (!preg_match('/^1[34578]\d{9}$/', $tel)) {
            return ['status' => '-1', 'msg' => '电话号码不正确'];
        }
        //检查上次发送时间是否超过1分钟
        $value = \Yii::$app->cache->get('time_tel_' . $tel);
        $s = time() - $value;
        if ($s < 60) {
            return ['status' => '-1', 'msg' => '请' . (60 - $s) . '秒后再试'];
        }

        $code = rand(1000, 9999);
        //$result = \Yii::$app->sms->setNum($tel)->setParam(['code' => $code])->send();
        $result = 1;
        if ($result) {
            //保存当前验证码 session  mysql  redis  不能保存到cookie
//            \Yii::$app->session->set('code',$code);
//            \Yii::$app->session->set('tel_'.$tel,$code);
            \Yii::$app->cache->set('tel_' . $tel, $code, 5 * 60);
            \Yii::$app->cache->set('time_tel_' . $tel, time(), 5 * 60);
            //echo 'success'.$code;
            return ['status' => '1', 'msg' => '', 'data' => \Yii::$app->cache->get('tel_' . $tel)];
        } else {
            return ['status' => '-1', 'msg' => '短信发送失败'];
        }
    }


    //获取当前登陆的用户信息
    public function actionGetUser()
    {
        $id = \Yii::$app->user->id;
        $id = 5;
        if ($id != null) {
            $user = Member::findOne(['id' => $id])->toArray();
            if (!empty($user)) {
                return ['status' => '1', 'errormsg' => '', 'data' => $user];
            }
            return ['status' => '-1', 'errormsg' => '用户不存在'];
        }
        return ['status' => '-1', 'errormsg' => '请登录后在操作'];
    }

    // 添加收货地址
    public function actionAddAddress()
    {
        $request = \Yii::$app->request;
        $member_id = 1;
//        var_dump($request->isPost);exit;
        if ($request->isPost) {
            $address = new Address();
            $address->name = $request->post('name');
            $address->city_intro = $request->post('city_intro');
            $address->tel = $request->post('tel');
            $address->member_id = $member_id;
            if ($address->validate()) {
                $r = $address->save();
                if ($r) {
                    return ['status' => '1', 'errormsg' => '', 'data' => $address->toArray()];
                } else {
                    return ['status' => '-1', 'errormsg' => '保存错误'];
                }
            }
        } else {
            return ['status' => '-1', 'errormsg' => '请使用post提交'];
        }
    }

    ///  修改收货地址
    public function actionEditAddress()
    {//先获得收货当前要修改的收货地址
        $request = \Yii::$app->request;
        $id = $request->get('id');
        $member_id = 2;
        $address = Address::findOne([['id' => $id, 'member_id' => $member_id]]);
        if ($request->isPost) {
            $address->name = $request->post('name');
            $address->city_intro = $request->post('city_intro');
            $address->tel = $request->post('tel');
            $address->member_id = $member_id;

            if ($address->validate()) {
                $r = $address->save();
                if ($r) {
                    return ['status' => '1', 'errormsg' => '', 'data' => $address->toArray()];
                } else {
                    return ['status' => '-1', 'errormsg' => $address->getErrors()];
                }
            }

        } else {
            return ['status' => '-1', 'errormsg' => '请使用post提交'];
        }
    }

    //删除收货地址
    public function actionDelete()
    {
        $request = \Yii::$app->request;
        //假设当前用户id为2;
        $member_id = 2;
        if ($id = $request->get('id')) {
            $address = Address::findOne([['id' => $id, 'member_id' => $member_id]]);
            if ($address) {
                $address->delete();
                return ['status' => 1, 'errormsg' => '删除成功',];
            }
            return ['status' => -1, 'errormsg' => 'get数据不存在,没有当前地址'];
        }
        return ['status' => -1, 'errormsg' => '数据不存在',];


    }

    //获取当前用户的所有收货地址接口
    public function actionGetAddressList()
    {
        //假设当前ID为3的用户,
        $member_id = \Yii::$app->user->id;
        $member_id = 3;
        $address = Address::find()->where(['member_id' => $member_id])->asArray()->all();
        if ($address) {
            return ['status' => 1, 'errormsg' => '', 'data' => $address];
        }
        return ['status' => -1, 'errormsg' => '暂无数据',];
    }

    //获取所有商品分类
    public function actionGetGoodsCategory()
    {
        $category = GoodsCategory::find()->asArray()->all();
        return ['status' => '1', 'errormsg' => '', 'data' => $category];
    }

    public static $category_list = [];

    //获取某分类的所有所有子分类 get方式   ---------未完成
    public function actionGetGoodsCategoryChild($ids = 0, $num = 0)
    {

    }

    // -获取某分类的父分类
    public function actionGetParentCategory()
    {
        //假设id为
        $request = \Yii::$app->request;

        if ($request->isGet) {
            $child = GoodsCategory::findOne(['id' => $request->get('id')]);
            $goods_category = GoodsCategory::findOne(['id' => $child->parent_id]);
            return ['status' => '1', 'errormsg' => '', 'data' => $goods_category];
        }
        return ['status' => '-1', 'errormsg' => '请使用get方式获取',];
    }
    // 4.商品
    // -获取某分类下面的所有商品*****************（已分页）
    public function actionGetCategoryByGoods()
    {  //get传入分类id
        $request = \Yii::$app->request;
        //每页显示条数
        $per_page = $request->get('per_page', 2);//,默认显示两条
        //当前第几页
        $page = $request->get('page', 1);//默认为第一页

        if ($request->isGet) {
            $goods_category = GoodsCategory::findOne(['id' => $request->get('id')]);
            if (empty($goods_category)) {
                return ['status' => '-1', 'errormsg' => '没有该分类',];
            } else {
                //总条数
                $total = Goods::find()->where(['goods_category_id' => $goods_category->id])->count();
                $goods = Goods::find()->offset($per_page * ($page - 1))->limit($per_page)->asArray()->where(['goods_category_id' => $goods_category->id])->all();
                return ['status' => '1', 'errormsg' => '', 'page' => $page, 'per_page' => $per_page, 'total' => $total, 'data' => $goods];
            }
        }
    }


    // -获取某品牌下面的所有商品 $$**********（已分页）

    public function actionGetBrandByGoods()
    {  //get传入品牌id
        $request = \Yii::$app->request;
        //每页显示条数
        $per_page = $request->get('per_page', 2);//,默认显示两条
        //当前第几页
        $page = $request->get('page', 1);//默认为第一页
        if ($request->isGet) {
            $brand = Brand::findOne(['id' => $request->get('id')]);
            if (empty($brand)) {
                return ['status' => '-1', 'errormsg' => '没有该品牌',];
            } else {
                //总条数
                $total = Goods::find()->where(['Brand_id' => $brand->id])->count();
                $goods = Goods::find()->offset($per_page * ($page - 1))->limit($per_page)->asArray()->where(['Brand_id' => $brand->id])->all();
                return ['status' => '1', 'errormsg' => '', 'page' => $page, 'per_page' => $per_page, 'total' => $total, 'data' => $goods];
            }
        }
        return ['status' => '-1', 'errormsg' => '请使用get请求',];
    }


    // 5.文章
    // -获取文章分类
    public function actionGetArticleCategory()
    {
        $article_category = Article_category::find()->asArray()->all();
        return ['status' => '-1', 'errormsg' => '', 'data' => $article_category];
    }

    // -获取某分类下面的所有文章
    public function actionGetCategoryByArticle()
    {//get 传入文章分类id
        $request = \Yii::$app->request;
        if ($request->isGet) {
            $article = Article::find()->asArray()->where(['article_category_id' => $request->get('id')])->all();
            if ($article) {
                return ['status' => '1', 'errormsg' => '', 'data' => $article];
            } else {
                return ['status' => '-1', 'errormsg' => '没有该分类',];
            }
        }
        return ['status' => '-1', 'errormsg' => '请使用get请求',];
    }

    // -获取某文章所属分类
    public function actionGetArticleByCategory()
    {//get出入文章id
        $request=\Yii::$app->request;
        if($request->isGet){
            //查询文章并获取文章的分类id
            $article=Article::findOne(['id'=>$request->get('id')]);
            if(!$article){
                return ['status' => '-1', 'errormsg' => '文章不存在',];
            }
            //查询文章的分类
            $article_category=Article_category::findOne(['id'=>$article->article_category_id])->toArray();
            return ['status' => '1', 'errormsg' => '','data'=>$article_category];
        }
        return ['status' => '-1', 'errormsg' => '请使用get请求',];
    }


    // 6.购物车
    // -添加商品到购物车
    public function actionAddShoppingCart()
    {
        $request=\Yii::$app->request;
        if($request->isPost){
            $goods_id = $request->post('goods_id');
            $amount = $request->post('amount');
            $goods = Goods::findOne(['id' => $goods_id]);
            if ($goods == null) {//商品不存在  抛出异常
                throw new NotFoundHttpException('数据不存在');
            }

            //判断是否是游客
            if (\Yii::$app->user->isGuest) {//未登录  是游客
                $cookies = \Yii::$app->request->cookies; //未登陆 先获取cooking中的数据
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

                return ['status' => '-1', 'errormsg' => '','data'=>$cookie,];
            } else {//已登录  不是游客
                $user = \Yii::$app->user->id;//获取用户id
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
                $cartData = Cart::find()->asArray()->where(['member_id' => $user])->andWhere(['goods_id' => $goods_id])->all();
                return ['status' => '1', 'errormsg' => '','data'=>$cartData,];
            }
        }
        return ['status' => '-1', 'errormsg' => '请使用post请求',];


    }

    // -修改购物车某商品数量
    // -删除购物车某商品
    // -清空购物车
    // -获取购物车所有商品
    // 7.订单
    // -获取支付方式
    // -获取送货方式
    // -提交订单
    // -获取当前用户订单列表
    // -取消订单


}