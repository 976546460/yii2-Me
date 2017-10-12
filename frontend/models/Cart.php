<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/24
 * Time: 9:36
 */

namespace frontend\models;


use backend\models\Goods;
use yii\db\ActiveRecord;

class Cart extends ActiveRecord
{
    public static function tableName()
    {
        return 'cart';
    }

    public function rules()
    {
        return [

        ];
    }

//当购物车中没有数据时 新曾数据记录的方法
    public function insertCartData($user = "", $goods_id, $amount)
    {
        $this->member_id = $user;
        $this->goods_id = intval($goods_id);
        $this->amount = intval($amount);
        $this->insert();
    }
    //当购物车存在数据时 更新购物车数据的方法


    //建立与与商品表的关系
    public function getGoods(){
        return $this->hasOne(Goods::className(),['id'=>'goods_id']);


    }
}