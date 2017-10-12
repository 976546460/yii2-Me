<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/16
 * Time: 16:24
 */

namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permission = [];

    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            ['permission', 'safe'],//表示该字段不需要验证
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '角色名称',
            'description' => '角色描述',
            'permission' => '角色权限'
        ];
    }

    //定义多选框的选项  操作
    public static function getPermission()
    {
        $authManager = \Yii::$app->authManager;
        return ArrayHelper::map($authManager->getPermissions(), 'name', 'description');
    }

    //添加角色
    public function addRole()
    {
        //  实列化组件
        $authManager = \Yii::$app->authManager;
        //判断角色是否存在
        if ($authManager->getRole($this->name)) {
            $this->addError('name', '角色已存在哦！');
        } else {//将角色保存
            $role = $authManager->createRole($this->name);
            $role->description = $this->description;
            if ($authManager->add($role)) {//保存角色
                //保存角色关联的权限
                if ($this->permission) {//判断是否给予了当前角色权限 没有则不进行遍历
                    foreach ($this->permission as $permissionName) {//遍历数组 获取选中的权限   可有 可无
                        $permission = $authManager->getPermission($permissionName);//根据权限名查询权限
                        if ($permission) {            //权限存在  保存到该角色下
                            $authManager->addChild($role, $permission);
                        }
                    }
                }
                return true;
            }
        }
        return false;
    }

    //将要修改的数据回显在表单模型中
    public function loadDate(Role $role)
    {
        $this->name = $role->name;
        $this->description = $role->description;
        //让多选框选中
        $permissions = \Yii::$app->authManager->getPermissionsByRole($role->name);// 包含多项权限  进行遍历
        foreach ($permissions as $permission) {
            $this->permission[] = $permission->name;
        }
    }

// 修改
    public function updateRole($name)
    {
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        //判断管理员是否存在
        if ($this->name != $role->name && $authManager->getRole($this->name)) {
            $this->addError('name', '管理员名称已存在了！');
        } else {
            $role->name = $this->name;
            $role->description = $this->description;
            //更新
            if ($authManager->update($name, $role)) {
                //去掉该角色原有的权限
                $authManager->removeChildren($role);
                // 重新关联新的角色权限
                if ($this->permission) {//判断是否给予了当前角色权限 没有则不进行遍历
                    foreach ($this->permission as $permissionName) {
                        $permission = $authManager->getPermission($permissionName); //查询权限并赋值给变量
                        if ($permission) {
                            $authManager->addChild($role, $permission);
                        }
                    }
                }
                return true;
            }
            return false;
        }
    }
}