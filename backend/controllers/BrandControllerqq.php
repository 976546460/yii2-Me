<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;//上传图片插件空间命名
use crazyfd\qiniu\Qiniu;//七牛云的储存空间命名

class BrandController extends BaseController
{
    public function actionIndex()
    {
        //实列化模型对象
        $model = Brand::find()->where(['status'=>1]);
        //实列化Pagination对象分页
        $pagination = new Pagination([
            'defaultPageSize' => 2,// 默认每页显示条数
            'totalCount' => $model->count(),// 通过对象查询出总条数
        ]);
        //将查询后的数据进行分页
        $model = $model->orderBy('id DESC')
                        ->offset($pagination->offset)
                        ->limit($pagination->limit)
                        ->all();

        return $this->render('index', ['model' => $model,'pagination'=>$pagination]);
    }

    // 添加品牌
    public function actionAdd()
    {
        //实列化模型对象
        $model = new Brand();
        //检查是否是post提交
        if ($model->load(\Yii::$app->request->post())) {
            // 获取上传的图片
//            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
            if ($model->validate()) {
               /* if ($model->imgFile) {// 判断是否上传了图片
                    //如果上传了图片就建立图片的的保存路径和图片的名称
                    $fileName = '/images/brand/' . uniqid() . '.' . $model->imgFile->extension;
                    //用Yii::getAlias 得到文件所在硬盘的绝对路径   用saveAs（）将上传到临时文件的图片转移到$nameFile指定的路径
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
                    //将图片保存的路径存入数据库
                    $model->logo = $fileName;
                }*/
                //将提交的数据保存到数据库
                $model->save();
                // 设置提示信息
                \Yii::$app->session->setFlash('success', '添加成功');
                //跳转到品牌列表页
                return $this->redirect(['brand/index']);
            }
        }
        // 渲染添加视图
        return $this->render('add', ['model' => $model]);
    }

    //修改视图
    public function actionEdit($id)
    {
        //实列化对象
        $model = Brand::findOne(['id' => $id]);
        // 判断是否是post提交
        if ($model->load(\Yii::$app->request->post())) {
            //获取上传的图片
//            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
            //验证数据有效性
            if ($model->validate()) {
                //判断是更新了图片
                /*if ($model->imgFile) {//判断模型里面的imgFile是否有值 存在-》保存 不存在-》跳过忽略
                    //创建文件保存路径   $model->imgFile->extension-----此方法用来获取扩展名后缀
                    $fileName = '/images/brand/' . uniqid() . '.' . $model->imgFile->extension;
                    //用saveAs转移在临时文件夹中的文件保存到$fileName指定的路径 yii::getAlias(@web) 获得@web在硬盘中的绝对路径
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
                    //将文件路径保存至数据库
                    $model->logo = $fileName;
                }*/
                //将数据更新到数据库
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '修改成功');
                // 跳转到指定列表页面
                return $this->redirect(['brand/index']);
            }
        }
        //加载视图模板
        return $this->render('add', ['model' => $model]);
    }

        //删除功能
    public function actionDel($id){
        //=删除数据之前查询数据数据中的图片地址，然后删除
        $model=Brand::findOne($id);
        //删除数据库数据
        //模型静态调用deleteAll（$id）方法删除id对应数据
        Brand::deleteAll(['id'=>$id]);
        //Yii::getAlias(@webroot)  获取@webroot在用盘中的绝对路径
        if($model->logo){
            $a=\Yii::getAlias('@webroot').$model->logo; //得到LOGO图片的绝对地址
            //执行删除图片操作
            unlink($a);
        }
        //跳转到列表页
        return  $this->redirect(['brand/index']);
    }

    //图片上传组件操作
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
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
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //<<<-----七牛云组件开始---->>>>>>
                    //保存当前的图片路径
                    $imgUrl=$action->getWebUrl();
                  //调用七牛云组件 将图  片保存在七牛云
                    $qiniu=\Yii::$app->qiniu;
                    $qiniu->UploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);
                    //获取该图片在七牛云的地址
                    $url=$qiniu->getLink($imgUrl);
                    //将七牛云返回的url地址传给前台js
                    $action->output['fileUrl'] = $url;
                    //<<<-----七牛云组件结束---->>>>>>
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ]
        ];
    }
}
