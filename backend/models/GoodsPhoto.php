<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/13
 * Time: 14:59
 */

namespace backend\models;


use yii\db\ActiveRecord;
use yii\helpers\Url;

class GoodsPhoto extends ActiveRecord
{
    public $fileImage;

    /**
     * 设置该模型关联的数据表
     */
    public static function tableName()
    {
        return 'goods_photo';
    }

    /**
     * @建立验证规则
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['fileImage'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png,jpg,gif', 'maxFiles' => 6],
            ["fileImage", "required",],
//            [['photo'], 'string'],
        ];
    }

    public function upload($goods_id)
    {
        $dir = Url::to('@webroot') . "/images/goodsPhoto" . date("Ymd");
        if (!is_dir($dir)) {//建立文件夹
            mkdir($dir);
        }
        $num = [];
        if ($this->validate()) {

            foreach ($this->fileImage as $file) {
                $fileName = date(time()) . mt_rand(100, 999) . "." . $file->extension;
                $uploadPath = $dir . '/' . $fileName;//图片路径及全名
                $this->photoImage = str_replace(Url::to('@webroot'),"",$uploadPath);// 保存路劲为当前的网站目录的相对路径,所以就要将绝对路劲截取为相对路径
                $this->insert();
                $file->saveAs($uploadPath, false);
                $num[] = $this->id;
            }
            return $num;
        } else {
            return false;
        }
    }

    /**
     * @字段属性对应的字段名
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => '商品ID',
            'photo' => '图片墙',
        ];
    }

}