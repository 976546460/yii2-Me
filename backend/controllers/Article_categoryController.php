<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\Article_category;
use yii\data\Pagination;

class Article_categoryController extends BaseController
{
    public function actionIndex()
    {
        //静态调用读取数据方法
        $model = Article_category::find()->where(['>=','status',0]);
        //实列化分页
        $pagination=new Pagination([
            'defaultPageSize'=>2,//默认每页显示两条数据
            'totalCount'=>$model->count()// 查询数据总条数
        ]);
        //将查询后的数据进行分页查询
        $model=$model->orderBy('id desc,name asc')//id降序排列  name升序
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        //加载视图模型
        return $this->render('index', ['model' => $model,'pagination'=>$pagination]);
    }

    //添加文章分类
    public function actionAdd()
    {
        //实列化模型
        $model = new Article_category();
        //判断是否是post提交数据
        if ($model->load(\Yii::$app->request->post())) {
            //判断数据有效性
            if ($model->validate()) {
                //验证通过 保存数据
                $model->save();
                //创建提示信息
                \Yii::$app->session->setFlash('success', '添加成功');
                //跳转至文章列表页
                return $this->redirect(['article_category/index']);
            }
        }
        //加载视图页面
        return $this->render('add', ['model' => $model]);
    }

    //修改
    public function actionEdit($id)
    {
        //实列化对象
        $model = Article_category::findOne($id);
        if ($model->load(\Yii::$app->request->post())) {
            //判断数据有效性
            if ($model->validate()) {
                //验证通过 保存数据
                $model->save();
                //创建提示信息
                \Yii::$app->session->setFlash('success', '修改成功');
                //跳转至文章列表页
                return $this->redirect(['article_category/index']);
            }
        }
        //加载视图文件
        return $this->render('add', ['model' => $model]);
    }

    // 删除文档  在这里将状态改为 -1
    public function actionDel($id)
    {
        //实列化对象
        $model = Article_category::findOne($id);
        //判断分类下面是否有文章。有文章那就不能删除
        // 实列化文章详情查询是否有数据数据
        $article = Article::find()->where(['article_category_id' => $id])->all();
        if ($article) {
            //  当该分类下面存在数据 就不能删除
            \Yii::$app->getSession()->setFlash('error', "\"$model->name\"" . '分类中包含文章数据，不能删除');
            //返回到列表页
            return $this->redirect(['article_category/index']);
        } else {
            //当查询该分类下面没有数据记录存在 那么久可以将该分类删除  将状态值改为-1;
            //将状态改为 -1
            $model->status = -1;
            //将更改后的值保存至数据库
            $rs = $model->save();
            if ($rs) {
                //  删除成功 提示
                \Yii::$app->getSession()->setFlash('success', "\"$model->name\"" . '分类删除成功！');
            } else {
                //  删除失败  提示
                \Yii::$app->getSession()->setFlash('error', "\"$model->name\"" . '分类删除失败');
            }
            //返回到列表页
            return $this->redirect(['article_category/index']);
        }
    }
}
