<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article_detail".
 *
 * @property integer $article_id
 * @property string $content
 */
class Article_detail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_detail';
    }

    /*
     * 建立文章内容和文章标题的对应关系 1对1
     */
    public function getArticles(){
        //一对一的关系 应hasOne
        return $this->hasOne(Article::className(),['id'=>'article_id']);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => '文章ID',
            'content' => '文章内容：',
        ];
    }
}
