<?php

namespace backend\controllers;


use backend\models\Article;
use backend\models\Article_detail;

class Article_detailController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }


    //查看文章详情
    public function actionShow($id){
        //实列化对象  查询文章详情
        $content=Article_detail::findOne(['article_id'=>$id]);
        //实列化对象 查询文章名称.添加时间等 信息
//        $article=Article::findOne($id);
        //加载视图模板
        return $this ->render('content',['model'=>$content/*,'article'=>$article*/]);
    }

}
