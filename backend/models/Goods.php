<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "Goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $good_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $id_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }
/*
 * 建立与商品详情表的关系
 */

public function getGoodsIntro(){
    //一对一关系
    return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id']);

}
/*
 * 建立与品牌表的一对多
 */
    public function getBrand(){
        //一对一关系
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);

    }
  /*
 * 建立与商品图片墙的一对一关系
 */
    public function getGoodsPhoto(){
        //一对一关系
        return $this->hasOne(GoodsPhoto::className(),['goods_id'=>'id']);

    }

    /*
     *建立与商品分类表的关系 一对一
     */
    public function getGoodsCategory(){
        //一对一关系
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);

    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','market_price','shop_price','sort', 'stock','goods_category_id','brand_id','is_on_sale', 'status'],'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status','sort'], 'integer'],
            [['market_price', 'shop_price'], 'integer'],
            [['name', 'sn'], 'string', 'max' => 20,],
            [['logo'], 'string', 'max' => 225],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'LOGO图片',
            'goods_category_id' => '所属分类',
            'brand_id' => '所属品牌',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '添加时间',
        ];
    }
}
