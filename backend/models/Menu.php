<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property integer $parent_id
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label'], 'required'],
            [['parent_id', 'sort'], 'integer'],
            [['label'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 225],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '名称',
            'url' => '地址/路由',
            'parent_id' => '上级菜单',
            'sort' => '排序',
        ];
    }

    //建立关系获取子菜单
    public function getChildren()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'id']);
    }

    public static function getParent_idDropDow()
    {
        $all = self::find()->where(['parent_id' => 0])->all();
        return ArrayHelper::map($all, 'id', 'label');
    }

    //检测修改是否符合逻辑
    public function editAction($id)
    {
        if ($this->parent_id == "") { //添加分类时 选择了顶级菜单 就将顶级菜单的parent_id=0
            $this->parent_id = 0;
        }
        $child = $model = Menu::findOne(['parent_id' => $id]);
        if ($child) { //存在分类 不能修改分类
            //添加错误
            $this->addError('parent_id', '当前菜单下有子级,不能修改菜单级别');
            return false;
        }
        return true;
    }

    //递归查看列表



       public function getShowList( $child=array(), $pid = 0)
       {
           $list= $this->find()->where(['parent_id' => $pid])->asArray()->all();
           $child = [];
           if (!empty($list)) {
               foreach ($list as $k => &$v) {
                   if ($v['parent_id'] == $pid) {
                       $v['child'] = $this->getShowList( $child, $v['id']);
                       $child[] = $v;
                   }
               }
           }
           var_dump($child);
           return $child;
       }

}