<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/4
 * Time: 8:53
 */

namespace frontend\controllers;


use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;
use frontend\models\Address;
use frontend\models\Member;
use frontend\models\Order;
use yii\helpers\Url;
use yii\web\Controller;

class WechatController extends Controller
{

    //微信开发依赖的插件  easyWechat
    // 1 关闭csrf验证
    public $enableCsrfValidation = false;

    //url 就是用于接受微信服务器发送的请求
    public function actionIndex()
    {

        $app = new Application(\Yii::$app->params['wechat']);
        $reply = $app->reply;
        $app->server->setMessageHandler(function ($message) {
            // 注意，这里的 $message 不仅仅是用户发来的消息，也可能是事件  // 当 $message->MsgType 为 event 时为事件

            // $message->FromUserName // 用户的 openid
            // $message->MsgType // 消息类型：event, text....
            //return "您好！欢迎关注我!";
            if ($message->MsgType == 'text') {
                $xml = simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
                foreach ($xml as $city) {
                    if ($message->Content == $city['cityname']) {
                        $weather = $city['stateDetailed'];
                        return $message->Content . '的天气情况是：' . $weather;

                    } elseif ($message->Content == '注册') {
                        $url = Url::to(['user/register'], true);
                        return '点此注册' . $url;

                    } elseif ($message->Content == '活动') {
                        $news1 = new News([
                            'title' => '十一大减价',
                            'description' => '图文信息的描述...',
                            'url' => 'http://www.baidu.com',
                            'image' => 'http://pic27.nipic.com/20130131/1773545_150401640000_2.jpg',
                        ]);
                        $news2 = new News([
                            'title' => '流量5折',
                            'description' => '流量5折...',//在多图文信息中这个第二及以后个图文说明是没用的,将会不会被显示出来
                            'url' => 'http://www.qq.com',
                            'image' => 'http://star.xiziwang.net/uploads/allimg/131228/15_131228085042_5.jpg',
                        ]);
                        $news3 = new News([
                            'title' => '无限流量套餐',
                            'description' => '流量5折...',
                            'url' => 'http://www.jd.com',
                            'image' => 'http://p2.wmpic.me/article/2016/01/21/1453345582_dvZHmWKA.jpg',
                        ]);
                        return [$news1, $news2, $news3];//多图文信息 返回的时候将是一个数组


                    }else{
                        $app = new Application(\Yii::$app->params['wechat']);
                        $reply = $app->reply;
                        $reply->current();
                    }
                }
            }elseif ($message->Event=='CLICK'){
                if ($message->EventKey == 'zxhd'){
                    $news1 = new News([
                        'title' => '十一大减价',
                        'description' => '图文信息的描述...',
                        'url' => 'http://www.baidu.com',
                        'image' => 'http://pic.yesky.com/uploadImages/2015/099/19/3L9Z6J74V925.jpg',
                    ]);
                    $news2 = new News([
                        'title' => '流量5折',
                        'description' => '流量5折...',//在多图文信息中这个第二及以后个图文说明是没用的,将会不会被显示出来
                        'url' => 'http://www.qq.com',
                        'image' => 'http://pic74.nipic.com/file/20150810/10115763_233338370780_2.jpg',
                    ]);
                    $news3 = new News([
                        'title' => '无限流量套餐',
                        'description' => '流量5折...',
                        'url' => 'http://www.jd.com',
                        'image' => 'http://pic3.16pic.com/00/37/06/16pic_3706700_b.jpg',
                    ]);
                    return [$news1, $news2, $news3];//多图文信息 返回的时候将是一个数组

                }

            }


//
//

//            switch ($message->MsgType) {
//                case 'text':
//                    //文本消息
//                    switch ($message->Content) {
//
//
//                        case '成都':
//                            $xml = simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
//                            foreach ($xml as $city) {
//                                    if ($city['cityname'] == '成都') {
//                                        $weather = $city['stateDetailed'];
//                                        break;
//                                    }
//                                }
//                            return '成都的天气情况是：' . $weather;
//                            break;
//                        case '注册':
//                            $url = Url::to(['user/register'], true);
//                            return '点此注册' . $url;
//                            break;
//                        case '活动':
//                            //回复图文消息 单图文信息
//                            /*$news = new News([
//                                'title'       => '十一大减价',
//                                'description' => '图文信息的描述...',
//                                'url'         => 'http://www.baidu.com',
//                                'image'       => 'http://pic27.nipic.com/20130131/1773545_150401640000_2.jpg',
//                            ]);
//                            return $news;*/
//                            //多图文信息
//                            $news1 = new News([
//                                'title' => '十一大减价',
//                                'description' => '图文信息的描述...',
//                                'url' => 'http://www.baidu.com',
//                                'image' => 'http://pic27.nipic.com/20130131/1773545_150401640000_2.jpg',
//                            ]);
//                            $news2 = new News([
//                                'title' => '流量5折',
//                                'description' => '流量5折...',//在多图文信息中这个第二及以后个图文说明是没用的,将会不会被显示出来
//                                'url' => 'http://www.qq.com',
//                                'image' => 'http://pic74.nipic.com/file/20150810/10115763_233338370780_2.jpg',
//                            ]);
//                            $news3 = new News([
//                                'title' => '无限流量套餐',
//                                'description' => '流量5折...',
//                                'url' => 'http://www.jd.com',
//                                'image' => 'http://pic3.16pic.com/00/37/06/16pic_3706700_b.jpg',
//                            ]);
//                            return [$news1, $news2, $news3];//多图文信息 返回的时候将是一个数组
//                            break;
//                    }
//
//                    return '收到你的消息:' . $message->Content;//将刚刚收到的用户消息 原样返回
//                    break;
//                case 'event'://当前用户操作是一个事件,运行一下代码
//                    //事件的类型   $message->Event
//                    //事件的key值  $message->EventKey
//                    if ($message->Event == 'CLICK') {//菜单点击事件
//                        if ($message->EventKey == 'zxhd') {//用户点击下面菜单,通过key值判断他点的是不是最新活动,
//                            $news1 = new News([//实列化一个News类 里面是展示活动简介等,配置url参数 点击后可进行跳转
//                                'title' => '十一大减价',
//                                'description' => '图文信息的描述...',
//                                'url' => 'http://www.baidu.com',
//                                'image' => 'http://pic27.nipic.com/20130131/1773545_150401640000_2.jpg',
//                            ]);
//                            $news2 = new News([
//                                'title' => '流量5折',
//                                'description' => '流量5折...',
//                                'url' => 'http://www.qq.com',
//                                'image' => 'http://pic74.nipic.com/file/20150810/10115763_233338370780_2.jpg',
//                            ]);
//                            $news3 = new News([
//                                'title' => '无限流量套餐',
//                                'description' => '流量5折...',
//                                'url' => 'http://www.jd.com',
//                                'image' => 'http://pic3.16pic.com/00/37/06/16pic_3706700_b.jpg',
//                            ]);
//                            return [$news1, $news2, $news3];//多图文信息,返回的是一个数组,包含多个对象
//                        }
//                    }
//
//                    return '接受到了' . $message->Event . '类型事件' . 'key:' . $message->EventKey;
//                    break;
//            }
        });


        $response = $app->server->serve();
        // 将响应输出
        $response->send(); // Laravel 里请使用：return $response;

    }

    /**
     * 开发需求
     * 1.菜单设置
     * -促销商品（click）
     * -在线商城（view：跳转至商城首页）
     * -个人中心
     * ---绑定账户（view）
     * ---我的订单（view）
     * ---收货地址（view）
     * ---修改密码（view）
     * 2.详细功能
     *  点击【促销商品】，发送图文信息（发送5条任意商品信息），点击图文信息中的商品，跳转至商品详情页
     *  点击【在线商城】，跳转至商城首页
     * 点击 【我的订单】，【收货地址】，【修改密码】，判断用户是否绑定账户，如果没有绑定则跳转至绑定账户页面
     * 3.对话功能
     * 用户发送【帮助】，回复以下信息“您可以发送 优惠、解除绑定 等信息”
     * 用户发送【优惠】，效果和点击【促销商品】相同
     * 用户发送【解除绑定】，如用户已绑定账户，则解绑当前openid，并回复解绑成功；否则回复请先绑定账户及绑定页面地址
     */


    //设置菜单  上传服务器好了后在手动打开浏览器运行此方法 将下面Yii内部的 url地址生成实际的外链url
    public function actionSetMenu()
    {
        $app = new Application(\Yii::$app->params['wechat']);//实列化Application模型类
        $menu = $app->menu; //得到菜单对象
        $buttons = [ //创建菜单中的按钮及按钮包含的操作
            [
                "type" => "click",
                "name" => "促销商品",
                "key" => "zxhd"//当前为属性为点击时间的时候必须加上key值
            ],
            [
                "type" => "view",
                "name" => "商城首页",
                "url" => Url::to(['index/index'], true)
            ],
            [
                "name" => "个人中心",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "我的订单",
                        "url" => Url::to(['wechat/order'], true)
                    ],
                    [
                        "type" => "view",
                        "name" => "收货地址",
                        "url" => Url::to(['wechat/address'], true)
                    ],
                    [
                        "type" => "view",
                        "name" => "修改密码",
                        "url" => Url::to(['wechat/editpwd'], true)
                    ],
                    [
                        "type" => "view",
                        "name" => "绑定账户",
                        "url" => Url::to(['wechat/account'], true)
                    ],
                ],
            ],
        ];
        $menu->add($buttons);// 将按钮添加到菜单栏中
        //获取已设置的菜单（查询菜单）
        $menus = $menu->all();
        var_dump($menus);
    }

    //查看自己的订单
    public function actionOrder()
    {
        //保存当前的地址到session到中 待得到openid之后跳转回来;
        \Yii::$app->session->set('redirect', \Yii::$app->controller->action->uniqueId);//保存当前用户的操作地址,待用户授权登陆之后跳转回来进行后续操作
        $openid = \Yii::$app->session->get('openid');
        if ($openid == null) {//说明未登录
            //获取当前用户的基本信息,通过网页授权机制(openid)
            self::actionGetOpenid();
        }
        //根据$openid查询出对应的的账户
        $member = Member::findOne(['openid' => $openid]);
        //判断当前的用户是否存在
        if ($member == null) {//用户不存在 诱惑用户登陆
            return $this->redirect(['wechat/user-login']);
        }
        //用户存在就查询用户是否存在的订单
        $order = Order::findAll(['member_id' => $member->id]);
        if (empty($order)) {//没有订单
            echo "目前你还没有订单哦!赶快去下两单吧!<br/>";//这里应该添加一个跳转到购物首页的url 未解决
        } else {
            var_dump($order);
        }
    }

    //定义收货地址
    public function actionAddress()
    {
        \Yii::$app->session->set('redirect', \Yii::$app->controller->action->uniqueId);//保留地址
        //获取openid
        $openid = \Yii::$app->session->get('openid');
        if ($openid == null) {//获取微信授权
            self::actionGetOpenid();
        }
        //通过openid查询用户
        $member = Member::findOne(['openid' => $openid]);
        //判断用户是否存在
        if (empty($member)) {
            //用户不存在就跳转到登陆页面
            return $this->redirect(['wechat/login']);
        }
        //获取收货地址:
        $address = Address::findAll(['member_id' => $member->id]);
        if (empty($address)) {
            echo '您目前还没有收货地址哦!';
        } else {
            var_dump($address);
        }


    }

    //修改密码:
    public function actionEditpwd()
    {
        \Yii::$app->session->set('redirect', \Yii::$app->controller->action->uniqueId);//保留地址
        //获取openid
        $openid = \Yii::$app->session->get('openid');
        if ($openid == null) {//获取微信授权
            self::actionGetOpenid();
        }
        //通过openid查询用户
        $member = Member::findOne(['openid' => $openid]);
        //应用微信验证场景
        $member->scenario = 'weixin';
        //判断用户是否存在
        if (empty($member)) {
            //用户不存在就跳转到登陆页面
            return $this->redirect(['wechat/login']);
        }
        if (!empty($member->getErrors())) {

        }
        $request = \Yii::$app->request;//接收post参数
        if ($request->isPost && $member->validate()) {
//            $user=Member::findOne(['username'=>$request->post('username')]);
            var_dump($member->save());

        }
        var_dump($member->getErrors());
        return $this->renderPartial('editpwd', ['model' => $member]);

    }

    //我的订单
    public function actionOrder11()
    {

        //openid
        $openid = \Yii::$app->session->get('openid');
        if ($openid == null) {
            //获取用户的基本信息（openid），需要通过微信网页授权机制
            \Yii::$app->session->set('redirect', \Yii::$app->controller->action->uniqueId);
            //echo 'wechat-user';
            $app = new Application(\Yii::$app->params['wechat']);
            //发起网页授权
            $response = $app->oauth->scopes(['snsapi_base'])->redirect();
            $response->send();
        }
        //var_dump($openid);
        //通过openid获取账号
        $member = Member::findOne(['openid' => $openid]);
        if ($member == null) {
            //该openid没有绑定任何账户
            //引导用户绑定账户
            return $this->redirect(['wechat/login']);
        } else {
            //已绑定账户
            $orders = Order::findAll(['member_id' => $member->id]);
//            var_dump($orders);
        }
    }

    //网页授权获取用户信息
    // 1 修改授权回调域名,字符串类型  位置-> 实际项目中:[开发 - 接口权限 - 网页服务 - 网页帐号 - 网页授权获取用户基本信息] 在测试号中:微信公众号测试品台--网页授权获取用户基本信息-修改
    // 2 发起网页授权->要在frontend/params.php设置回调页面地址,及选择scopes：公众平台
    // 3 创建回调页地址,->在控制器中创建回调页面地址 actionCallback()
    //个人中心
    public function actionUser()////要在frontend/params.php设置回调页面地址,及选择scopes：公众平台（snsapi_userinfo / snsapi_base）
    {
        $openid = \Yii::$app->session->get('openid');
        if ($openid == null) {
            //获取用户的基本信息（openid），需要通过微信网页授权
            \Yii::$app->session->set('redirect', \Yii::$app->controller->action->uniqueId);
            //echo 'wechat-user';
            $app = new Application(\Yii::$app->params['wechat']);
            //发起网页授权
            $response = $app->oauth->scopes(['snsapi_base'])->redirect();
            $response->send();
        }
        echo '当前OpenID是:' . $openid;
    }


    public function actionTest()
    {
        \Yii::$app->session->removeAll();
    }


    // 绑定用户账号   将openid和用户账号绑定
    public function actionLogin()
    {
        $openid = \Yii::$app->session->get('openid');
        if ($openid == null) {
            //获取用户的基本信息（openid），需要通过微信网页授权
            \Yii::$app->session->set('redirect', \Yii::$app->controller->action->uniqueId);
            //echo 'wechat-user';
            $app = new Application(\Yii::$app->params['wechat']);
            //发起网页授权
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            $response->send();
        }

        //让用户登录，如果登录成功，将openid写入当前登录账户
        $request = \Yii::$app->request;
        if (\Yii::$app->request->isPost) {
            $user = Member::findOne(['username' => $request->post('username')]);
            if ($user && \Yii::$app->security->validatePassword($request->post('password'), $user->password_hash)) {
                \Yii::$app->user->login($user);
                //如果登录成功，将openid写入当前登录账户
                Member::updateAll(['openid' => $openid], 'id=' . $user->id);
                if (\Yii::$app->session->get('redirect')) return $this->redirect([\Yii::$app->session->get('redirect')]);
                echo '绑定成功';
                exit;
            } else {
                echo '登录失败';
                exit;
            }
        }

        return $this->renderPartial('login');
    }

    //定义统一获取用户信息(openid)的方法
    public function actionGetOpenid()
    {
        $openid = \Yii::$app->session->get('openid');
        if ($openid == null) {
            //获取用户的基本信息（openid），需要通过微信网页授权
            $app = new Application(\Yii::$app->params['wechat']);
            //发起网页授权
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            $response->send();
        }
    }

    //授权回调页
    public function actionCallback()//在frontend/params.php设置回调页面地址
    {
        $app = new Application(\Yii::$app->params['wechat']);
        $user = $app->oauth->user();
        // $user 可以用的方法:
        // $user->getId();  // 对应微信的 OPENID
        // $user->getNickname(); // 对应微信的 nickname
        // $user->getName(); // 对应微信的 nickname
        // $user->getAvatar(); // 头像网址
        // $user->getOriginal(); // 原始API返回的结果
        // $user->getToken(); // access_token， 比如用于地址共享时使用
        //将openid放入session
        \Yii::$app->session->set('openid', $user->getId());
        return $this->redirect([\Yii::$app->session->get('redirect')]);//跳转到上次设置的地址,也就是上次请求页面;

    }

    //定义用户统一登陆方法
    public function actionUserLogin()
    {
        $openid = \Yii::$app->session->get('openid');
        //让用户登录，如果登录成功，将openid写入当前登录账户
        $request = \Yii::$app->request;
        if (\Yii::$app->request->isPost) {
            $user = Member::findOne(['username' => $request->post('username')]);
            if ($user && \Yii::$app->security->validatePassword($request->post('password'), $user->password_hash)) {
                \Yii::$app->user->login($user);
                //如果登录成功，将openid写入当前登录账户

                Member::updateAll(['openid' => $openid], 'id=' . $user->id);
                if (\Yii::$app->session->get('redirect')) {
//                    var_dump($user);exit;
                    return $this->redirect([\Yii::$app->session->get('redirect')]);
                }
                echo '绑定成功';

            } else {
                echo '登录失败';
                exit;
            }
        }

        return $this->renderPartial('login');
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

    //用户修改密码时的短信验证码处理
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


}
