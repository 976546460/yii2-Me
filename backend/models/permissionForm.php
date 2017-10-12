<?php
namespace backend\models;

use yii\base\Model;

class PermissionForm extends Model
{
    public $name;
    public $description;

    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '权限名称',
            'description' => '权限说明'
        ];
    }

    //添加权限时的逻辑判断和权限的添加入数据库
    public function addPermission()
    {
        $authManager = \Yii::$app->authManager;
        //创建权限
        //创建权限前先判断权限是否已存在 使用getPermission() 来从读取数据  若成功读取说明权限已存在
        if ($authManager->getPermission($this->name)) {
            //存在就添加错误提示
            $this->addError('name', '权限已存在');
        } else {// 权限不存在 就添加权限
            $permission = $authManager->createPermission($this->name);//添加权限
            $permission->description = $this->description;//添加权限说明
            //保存到数据表
            return $authManager->add($permission);
        }
        return false;
    }

    //此操作是修改表单的时候 将数据库中读取出来的权限赋值给表单模型   此表单模型继承一般的model 而不是活动记录 所以需要赋值回显
    public function loadDate($permission)
    {
        $this->name = $permission->name;
        $this->description = $permission->description;
    }

    //此操作是用来更新数据；
    public function updatePermission($name)
    {
        //实列化权限操作组件
        $authManager = \Yii::$app->authManager;
        //根据主键name 查询数据
        $permission = $authManager->getPermission($name);
        //判断名称是否修改后   并且  修改后的权限名称是 否存在！ 存在 就提示错误
        if ($name != $this->name && $authManager->getPermission($this->name)) {
            $this->addError('name', '权限名称已存在');
        } else {
            //将表单模型中的值 赋值给 权限操作模型
            $permission->name = $this->name;
            $permission->description = $this->description;
            //最后更新权限 $authManager 返回一个布尔值  所以可以直接返回（return）
            return $authManager->update($name,$permission);
        }
        return false;
    }



}