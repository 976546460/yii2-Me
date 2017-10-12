<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/12
 * Time: 18:28
 */

namespace backend\models;


use yii\db\ActiveRecord;

class GoodsDayCount extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Goods_day_count';
    }


    public function rules()
    {
        return [
            ['count','required']
        ];
    }
}