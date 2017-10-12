<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $article_category_id
 * @property integer $sort
 * @property integer $status
 * @property integer $create_time
 */
class Article extends \yii\db\ActiveRecord
{

    public $body;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /*
     * 文章详情表对文章分类表  对对1 的关系
     */
    public function getArticle_categorys(){
        //
        return $this->hasOne(Article_category::className(),['id'=>'article_category_id']);


    }


    /*
     * 文章详情表对应文章内容表
     */
    public function getArticle_detail(){
        return $this->hasOne(Article_detail::className(),['article_id'=>'id']);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','intro','article_category_id','status'],'required'],
            [['intro'], 'string'],
            [['article_category_id', 'sort', 'status', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '文章名称',
            'intro' => '简介',
            'article_category_id' => '文章分类',
            'sort' => '排序',
            'status' => '状态（-1删除 0隐藏 1正常）',
            'create_time' => '创建时间',
        ];
    }
}
