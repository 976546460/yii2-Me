<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $sort
 * @property integer $status
 * @property integer $is_help
 */
class Article_category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_category';
    }

    /*
     * 建立分类与文章的对应关系 一对多
     */
    public function setArticles(){
        return $this->hasOne(Article::className(),['article_category_id'=>'id']);


    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['intro','sort','status','is_help','name'],'required'],
            [['intro'], 'string'],
            [['sort', 'status', 'is_help'], 'integer'],
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
            'name' => '分类名称',
            'intro' => '简介',
            'sort' => '排序',
            'status' => '状态',
            'is_help' => '类型',
        ];
    }
}
