<?php

namespace backend\controllers;

use backend\components\SphinxClient;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\GoodsPhoto;
use backend\models\GoodsSearchForm;
use xj\uploadify\UploadAction;
use yii\base\Model;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

class GoodsController extends BaseController //继承权限控制类
{

    public function actionIndex()
    {
        $models = new GoodsSearchForm();
        $query = Goods::find();
        $models->search($query);

        /*************/
        if ($keyName = \Yii::$app->request->get('keyName')) {
            $cl = new SphinxClient();
            $cl->SetServer('127.0.0.1', 9312);//链接服务器的参数
//$cl->SetServer ( '10.8.8.2', 9312);
            $cl->SetConnectTimeout(10);//设置链接服务器超时时间
            $cl->SetArrayResult(true);//设置查询后是否以数组形式返回数据
// $cl->SetMatchMode ( SPH_MATCH_ANY);
            $cl->SetMatchMode(SPH_MATCH_ALL);//设置搜索匹配模式 查看说明文档
            $cl->SetLimits(0, 1000);//设置最多读取数据
            $info = $keyName;//将要搜索的词
            $res = $cl->Query($info, 'goods');//在配置文件中设置的索引名称
//            var_dump($res);exit;
            if (empty($res['matches'])) {
                $ids = 0;
            } else {
                $ids = ArrayHelper::map($res['matches'], 'id', 'id');
                $query->where(['in', 'id', $ids]);
                $models->keyName = $keyName;//数据回显

            }
        }
        /**************************************/
        //分页读取类别数据
        /* $model = Article::find()->with('cate');*/
        $pagination = new Pagination([
            'defaultPageSize' => 2,
            'totalCount' => $query->count(),
        ]);

        $model = $query->orderBy('id ASC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
//        var_dump($model);exit;
//        var_dump($model);exit;
        return $this->render('index', [
            'model' => $model,
            'models' => $models,
            'pagination' => $pagination,
        ]);
    }

    //添加商品
    public function actionAdd()
    {
        if (\Yii::$app->user->isGuest) {

        }
        if (!\Yii::$app->authManager->checkAccess(\Yii::$app->user->id, 'goods/add')) {
            throw new ForbiddenHttpException('没有权限操作,赶快打怪升级!');
        }

        //实列化商品列表
        $model = new Goods();
        //实列化商品分类
        $goodsIntro = new GoodsIntro(); //商品详情
        $goodsPhoto = new GoodsPhoto();//图片墙
        if ($model->load(\Yii::$app->request->post())
            && $goodsIntro->load(\Yii::$app->request->post())
            && $goodsPhoto->load(\Yii::$app->request->post())
            && $model->validate()
            && $goodsIntro->validate()
//            && $goodsPhoto->validate()
        ) {
            //保存
            $rs = $model->save();
            //获取刚刚插入goods数据表里面的id作为goods_Intro(商品详情表)的id
            $goodsIntro->goods_id = $model->id;
            $result = $goodsIntro->save();//保存商品详情

            //当商品添加成功后获取商品的数量
            if ($rs && $result) {
                //判断当前的商品是不是今天第一次添加
                $day = GoodsDayCount::find()->where(['day' => date('Ymd')])->asArray()->all();
                if ($day) {
                    //存在 更新当前count（商品）的数量
                    //得到当前添加的商品数量
                    $num = $day[0]['count'] + 1;
                    $customer = GoodsDayCount::findOne(['day' => date('Ymd')]);
                    $customer->count = $day[0]['count'] + 1;
                    $customer->save();
                    //更新sn （商品编号） 默认年月日后面有4位数
                    $sn = date('Ymd') . str_pad($num, 4, 0, STR_PAD_LEFT);
                } else {//当天不存在记录就创建当天的记录 每天执行一次
                    $GoodsDayCount = new GoodsDayCount();
                    $GoodsDayCount->day = date('Ymd');
                    $GoodsDayCount->count = 1;
                    $GoodsDayCount->save();
                    $sn = date('Ymd') . str_pad(1, 4, 0, STR_PAD_LEFT);
                }
                //添加商品货号和当前添加时间
                $model->sn = $sn;
                $model->create_time = time();
                $model->save();
                /*--------保存商品图片墙------*/
                $goodsPhoto->fileImage = UploadedFile::getInstances($goodsPhoto, 'fileImage');
                $num = $goodsPhoto->upload($model->id);
                if ($num) { //文件上传成功，遍历路径数组 将图片路径保存至数据库
                    $goods_id = $model->id;//goods的id提取出来 保存到变量
                    foreach ($num as $value) {//循环出数组里面当前数据的id
                        $Obj = GoodsPhoto::find()->where(['id' => $value])->all(); //找到对应的数据
                        foreach ($Obj as $imgObj)//将对象遍历出来
                            $imgObj->goods_id = $goods_id;//将id值赋值给对应的goods_id字段 使商品包和商品图片表产生联系
                        $imgObj->update(false);//执行update更新
                    }
                }
            } else { //添加商品数量出错
                //打印错误
                var_dump($model->getErrors());
            }
            return $this->redirect(['goods/index']);
        }

        $goodsCategory = GoodsCategory::find()->asArray()->all();//商品分类
        $brand = Brand::find()->all();
        //加载视图页面
        return $this->render('add', [
            'model' => $model,
            'goodsCategory' => $goodsCategory,
            'goodsIntro' => $goodsIntro,
            'brand' => $brand,
            'goodsPhoto' => $goodsPhoto
        ]);
    }

//修改、、
    public function actionEdit($id)
    {
        $model = Goods::findOne(['id' => $id]);
        $goodsPhoto = GoodsPhoto::find()->where(['goods_id' => $id])->all();
        //商品详情
        if (GoodsIntro::findOne(['goods_id' => $id])) {
            $goodsIntro = GoodsIntro::findOne(['goods_id' => $id]);
        } else {
            $goodsIntro = new GoodsIntro();
        }
        if ($model->load(\Yii::$app->request->post())
            && $goodsIntro->load(\Yii::$app->request->post())
            && $model->validate()
            && $goodsIntro->validate()
        ) {
            //保存
            $model->save();
            //获取刚刚插入goods数据表里面的id作为goods_Intro(商品详情表)的id
            $goodsIntro->goods_id = $model->id;
            $goodsIntro->save();
            return $this->redirect(['goods/index']);
        }

        $goodsCategory = GoodsCategory::find()->asArray()->all();//商品分类
        $brand = Brand::find()->all();
//        var_dump($goodsPhoto);exit;
        return $this->render('add', [
            'model' => $model,
            'goodsCategory' => $goodsCategory,
            'goodsIntro' => $goodsIntro,
            'brand' => $brand,
            'goodsPhoto' => $goodsPhoto,
        ]);
    }

    //查看详情表
    public function actionShow($id)
    {
        //实列化
        $goods = Goods::findOne($id);//商品表
        $goodsCategory = GoodsCategory::findOne(['id' => $goods->goods_category_id]);//商品分类表
        $brand = Brand::findOne(['id' => $goods->brand_id]);//品牌表
        $goodsIntro = GoodsIntro::findOne(['goods_id' => $goods->id]);//商品详情表
        $goods_photo = GoodsPhoto::findAll(['goods_id' => $id]);

        return $this->render('show', [
            'goods' => $goods,
            'goodsCategory' => $goodsCategory,
            'brand' => $brand,
            'goodsIntro' => $goodsIntro,
            'goodsPhoto' => $goods_photo,
        ]);
    }


    //图片上传组件操作
    public function actions()
    {
        //这个是upload插件配置
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {
                },
                'beforeSave' => function (UploadAction $action) {
                },
                'afterSave' => function (UploadAction $action) {
                    //<<<-----七牛云组件开始---->>>>>>
                    //保存当前的图片路径
                    $imgUrl = $action->getWebUrl();
                    //调用七牛云组件 将图  片保存在七牛云
                    $qiniu = \Yii::$app->qiniu;
                    $qiniu->UploadFile(\Yii::getAlias('@webroot') . $imgUrl, $imgUrl);
                    //获取该图片在七牛云的地址
                    $url = $qiniu->getLink($imgUrl);
                    //将七牛云返回的url地址传给前台js
                    $action->output['fileUrl'] = $url;
                    //<<<-----七牛云组件结束---->>>>>>
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
            //ueditor 百度编辑器组件配置
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrl" => "http://up.qiniu.com/",
                    "imageCompressEnable" => true,  //开启图片压缩机制
                    "imageUrlPrefix" => '', //图片访问路径前缀
                    "imagePathFormat" => '/images/goods/' . uniqid(), //上传保存路径
                ],
            ],
        ];
    }

    public function actionText()
    {

        $cl = new SphinxClient();
        $cl->SetServer('127.0.0.1', 9312);//链接服务器的参数
//$cl->SetServer ( '10.8.8.2', 9312);
        $cl->SetConnectTimeout(10);//设置链接服务器超时时间
        $cl->SetArrayResult(true);//设置查询后是否以数组形式返回数据
// $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode(SPH_MATCH_ALL);//设置搜索匹配模式 查看说明文档
        $cl->SetLimits(0, 1000);//设置最多读取数据
        $info = '包子';//将要搜索的词
        $res = $cl->Query($info, 'goods');//在配置文件中设置的索引名称
//print_r($cl);
        var_dump($res);

    }

}


