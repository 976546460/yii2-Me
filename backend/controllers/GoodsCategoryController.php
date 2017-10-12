<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class GoodsCategoryController extends BaseController
{
    public function actionIndex()
    {
        $model= GoodsCategory::find()->where(['>','status',0])->orderBy('tree','lft')->all();
        return $this->render('index',['model'=>$model]);
    }
    /*
     * 添加商品分类
     */
    public function actionAdd(){
        $model=new GoodsCategory();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断是否是一级分类（parent_id是否为0）
            if($model->parent_id){
                //不为0  不是一级目录
                //首先找到上一级分类
                $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                $model->prependTo($parent);//将该分类添加到上一级分类下面
            }else{
                // 等于0 是一级目录
                $model->makeRoot();//将分类作为一级目录保存
            };
                //提示信息
                \Yii::$app->session->setFlash('success','保存成功');
            //返回列表页
            return $this->redirect(['goods-category/index']);
            }
        //获取所有分类的选项（选择商品的类别）->转化为数组分配到页面的下拉框中
        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]], GoodsCategory::find()->asArray()->all());//这个是无限极分内插件的方法
//        $options = ArrayHelper::map(GoodsCategory::find()->asArray()->all(),'id','name');//这个是普通下拉框的数据分配
        return $this->render('add',['model'=>$model,'categories' =>  $categories]);
    }

    // 商品分类的修改
    public function actionEdit($id){

        $model=GoodsCategory::findOne($id);
        if($model==null){
            throw new NotFoundHttpException('分类不存在');//检测分类是否存在 ，抛出404错误
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断是否是一级分类（parent_id是否为0）
            if($model->parent_id){
                //不为0  不是一级目录
                //首先找到上一级分类
                $parent=GoodsCategory::findOne(['id'=>1]);
                $model->prependTo($parent);//将该分类添加到上一级分类下面
            }else{
                // 等于0 是一级目录
                $model->makeRoot();//将分类作为一级目录保存
            };
            //提示信息
            \Yii::$app->session->setFlash('success','修改成功');
            //返回列表页
            return $this->redirect(['goods-category/index']);
        }
        //获取所有分类的选项（选择商品的类别）->转化为数组分配到页面ztree
        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]], GoodsCategory::find()->asArray()->all());//这个是无限极分内插件的方法
//        $options = ArrayHelper::map(GoodsCategory::find()->asArray()->all(),'id','name');//这个是普通下拉框的数据分配
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }

/// 删除分类
public function actionDel($id){
    $model=GoodsCategory::findOne($id);

    if(GoodsCategory::find()->where(['parent_id'=>$id])->all()){ //查询该分类下面是否有子分类  有——》不能删除
        \Yii::$app->session->setFlash('success','当前分类包含子分类，不能删除');
        return $this->redirect(['goods-category/index']);
    }else{
        //判断当前分类是否存在
        if($model==null){
            throw new NotFoundHttpException('分类不存在');
        }
        $result=$model->deleteWithChildren();//调用插件里面的删除方法,删除之后重新计算该分类的左值和右值
        if($result) {
            \Yii::$app->session->setFlash('success', '删除成功'); //提示
            return $this->redirect(['goods-category/index']);//返回
        }
    }
}



    /*
     * 测试 Nestedset 的无级限分类插件
     */
    public function actionTest(){
      /*
       * 将当前分类创建为一级分类
       */
        /*  $jydq=new GoodsCategory();
        $jydq->name='家用电器';
        $jydq->parent_id=0;
        $jydq->makeRoot();//将当前分类创建为一级分类*/


        /*
         * 将当前分类创建为二级分类
         */
      /*$parent=GoodsCategory::findOne(['id'=>1]);
        $xjd=new GoodsCategory();
        $xjd->name='小家电';
        $xjd->parent_id=$parent->id;
        $xjd->prependTo($parent);*/

      /*
       * 获取所有一级分类
       */
        /*$roots = GoodsCategory::find()->roots()->all();
        var_dump($roots);*/

        /*
         * 获取该节点下的所有子孙分类
         */
       /* $all=GoodsCategory::findOne(['id'=>1]);//找到父节点
       $childAll= $all->leaves()->all();
        var_dump($childAll);*/
    }

    public function actionZtree(){
        $categories = GoodsCategory::find()->asArray()->all();
        return $this->renderPartial('ztree',['categories'=>$categories]);//renderPartial -> 不加载布局文件

    }
}
