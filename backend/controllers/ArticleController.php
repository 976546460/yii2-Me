<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\Article_category;
use backend\models\Article_detail;
use kucha\ueditor\Uploader;
use xj\uploadify\UploadAction;
use yii\base\Model;
use yii\data\Pagination;

class ArticleController extends BaseController
{
    public function actionIndex()
    {
        // 实列化
        $model = Article::find()->where(['>=','status',0]);
        //分页
        //实列化分页对象 Pagination
        $pagination= new Pagination([
            'defaultPageSize'=>2,//默认每页显示两条数据
            'totalCount'=>$model->count(),
        ]);
        //将查询后的数据惊进行分页查询
        $model=$model->orderBy(['id' => SORT_DESC])//数组方式让查询结果倒序排列
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->all();
        //加载视图文件 分配数据
        return $this->render('index', ['model' => $model,'pagination'=>$pagination]);
    }

    //添加文章
    public function actionAdd()
    {
        //实列化对象
        $model = new Article();
        //实列化分类对象 查询分类asArray():将对象转为数组
        $article_category = Article_category::find()->where(['status'=>1])->asArray()->all();
        //实列化文章内容模型
        $article_detail = new Article_detail();
        //两个数据表模型同时保存数据 分别用两个对象同时接收数据，并且调用模型,验证数据有效性
        if ($model->load(\Yii::$app->request->post())
            && $article_detail->load(\Yii::$app->request->post())
            && Model::validateMultiple([$model, $article_detail])
        ){
            //保存到数据库
            $model->save(false);
            $article_detail->article_id=$model->id;
            $article_detail->save(false);
            //跳转到列表页
            return $this->redirect(['article/index']);
        }
//////////////////
        //判断是否是post提交的数据
        /*  if($model->load(\Yii::$app->request->post())){
             var_dump(\Yii::$app->request->post());exit;

             //验证$model提交的数据有效性
         /* if($model->validate()){
                 //验证$article_detail的数据有效性
                 if($article_detail->validate()) {
                     //保存数据
 //                    var_dump(\Yii::$app->request->post()['Article_detail']);exit;
 //                    $model->save();
 //                    $article_detail->save();
                     //跳转到文章列表页
                     return $this->redirect(['article/index']);
                 }
             }
        }*/

        //加载视图页面 @article_category:分配分类数据
        return $this->render('add', ['model' => $model, 'article_category' => $article_category, 'article_detail' => $article_detail]);
    }

    //修改文章
    public function actionEdit($id)
    {

        //实列化文章模型
        $model = Article::findOne(['id'=>$id]);
        //实列化文章内容模型
        $article_detail =Article_detail::findOne(["id"=>$id]);
        //实列化分类对象 查询分类asArray():将对象转为数组
        $article_category = Article_category::find()->asArray()->all();

        //两个表同时保存数据 分别调用对应的模型接收数据 并验证数据有效性；
      /*  if($model->load(\Yii::$app->request->post())){
            $model->intro='已修改';
        }*/
        if($model->load(\Yii::$app->request->post())
            && $article_detail->load(\Yii::$app->request->post())
            && Model::validateMultiple([$model,$article_detail]))
        {
            var_dump(  \Yii::$app->request->post() );
            //验证通过  分别使用相应的模型保存数据
            $model->save(false);
            $article_detail->save(false);
            //跳转到文章列表页
            return $this->redirect(['article/index']);
        }

      /*  //判断是否是post提交的数据
        if ($model->load(\Yii::$app->request->post())) {
            //验证数据有效性
            if ($model->validate()) {
                //保存数据
                $model->save();
                //跳转到文章列表页
                return $this->redirect(['article/index']);
            }
        }*/
        //加载视图页面 @article_category:分配分类数据
        return $this->render('add', ['model' => $model, 'article_category' => $article_category,'article_detail'=>$article_detail]);
    }

    // 删除文档  在这里将状态改为 -1
    public function actionDel($id)
    {
        //实列化对象
        $model = Article::findOne($id);
        //将状态改为 -1
        $model->status = -1;
        //将更改后的值保存至数据库
        $model->save();
        //返回到列表页
        return $this->redirect(['article/index']);
    }

//ueditor 百度编辑器组件配置
    public function actions()
    {
        $dirName=time().uniqid() ;
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrl"=> "http://up.qiniu.com/",
                    "imageCompressEnable" => true,  //开启图片压缩机制
                    "imageUrlPrefix"  =>'', //图片访问路径前缀
                    "imagePathFormat" =>'/baiduImg/'.$dirName, //上传保存路径

                ],//    '/baiduImg/'.$dirName, //上传保存路径
            ]
        ];
    }
}
