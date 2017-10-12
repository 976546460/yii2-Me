<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/21
 * Time: 11:11
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Address extends  ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }
    public function rules()
    {
        return [
            [['name',/*'province','city','county','city_intro',*/'tel'/*,'default_address'*/,'member_id'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['tel'], 'string', 'max' => 11],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '姓名',
            'tel' => '电话',
            'province'=>'省',
            'city' => '城市',
            'county'=>'县区',
            'city_intro' => '详细地址',
            'default_address' => '是否设置为默认收货地址',
        ];
    }


}