<?php
namespace frontend\controllers;


use frontend\models\Address;
use yii\web\NotFoundHttpException;

class AddressController  extends CommonController
{
    public $layout='index';//关闭yii2 系统自带布局文件

    public function actionIndex(){
        $model=new Address();
         return $this->render('index');
    }

    //添加
    public function actionAdd(){

        $model=new Address();
        $modelData=Address::find()->where(['member_id'=>\Yii::$app->user->id])->asArray()->all();
        if(\Yii::$app->request->post()){


            $data=\Yii::$app->request->post();

            $model->name=$data['address']['name'];
            $model->province=$data['address']['province'];
            $model->city=$data['address']['city'];
            $model->county=$data['address']['county'];
            $model->city_intro=$data['address']['city_intro'];
            $model->tel=$data['address']['tel'];
            $model->member_id=\Yii::$app->user->id;
            if(isset($data['address']['default_address'])){
                $oldData=Address::findOne(['default_address'=>1,'member_id'=>\Yii::$app->user->id]);
                $oldData->default_address=0;
                $oldData->update();
                $model->default_address=1;
            }else{
                $model->default_address=0;
            }
            if($model->validate()==false && $model->getErrors()){
                $error="";
                foreach($model->getErrors() as $arr){
                    foreach($arr as $v){
                        $error.=$v;
                    }
                }
                //跳转到错误提示
                $this->success($error,[['address','add.html']],3);
            }else{
                $model->save();
                    //提示跳转
                $this->success('添加成功',[['address','add.html']],2);
            }
        }

        return $this->render('index',['model'=>$model,'date'=>$modelData]);
    }

    //修改
    public function actionEdit($id){
        $modelEdit=Address::findOne(['id'=>$id]);
        if($modelEdit==null){
                throw new NotFoundHttpException('没有数据记录');
            }
        $model=new Address();
        $modelData=Address::find()->asArray()->all();
        if(\Yii::$app->request->post()){
//            var_dump(\Yii::$app->request->post());exit;
            $data=\Yii::$app->request->post();
            $modelEdit->name=$data['address']['name'];
            $modelEdit->province=$data['address']['province'];
            $modelEdit->city=$data['address']['city'];
            $modelEdit->county=$data['address']['county'];
            $modelEdit->city_intro=$data['address']['city_intro'];
            $modelEdit->tel=$data['address']['tel'];
            if(isset($data['address']['default_address'])){
                $oldData=Address::findOne(['default_address'=>1,'member_id'=>\Yii::$app->user->id]);
                $oldData->default_address=0;
                $oldData->update();
                //更改了默认地址后将所有该用户的
                $modelEdit->default_address=1;
            }else{
                $modelEdit->default_address=0;
            }
            if($modelEdit->validate()==false && $modelEdit->getErrors()){
                $error="";
                foreach($modelEdit->getErrors() as $arr){
                    foreach($arr as $v){
                        $error.=$v;
                    }
                }
                //跳转到错误提示
                $this->success($error,[['address','edit.html?id='.$id]],3);
            }else{
                $modelEdit->update();
                //提示跳转
                $this->success('修改成功',[['address','add.html']],2);
            }
        }
        return $this->render('index',['model'=>$model,'date'=>$modelData,'edit'=>$modelEdit]);
    }
    //删除
    public function actionDel($id){
        $model=Address::findOne($id);
        if($model->delete()){
            //跳转到错误提示
            $this->success('删除成功',[['address','add.html']],3);
        }
    }

    //将当前地址设置为默认地址哦
    public function actionDefault($id){

        $model=Address::findAll(['default_address'=>1]);
        foreach($model as $arr){
            $arr->default_address=0;
            $r=$arr->save();
        }
        $newDefaultAddress=Address::findOne(['id'=>$id]);
        $newDefaultAddress->default_address=1;
       if( $newDefaultAddress->save()){
           //跳转到提示
           $this->success('设置成功',[['address','add.html']],3);
       };
    }

}