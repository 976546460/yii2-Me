<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "User".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password;//确认密码
    public $password2;
    public $name = []; // 定义角色身份
    public $description;//当前管理员角色的说明

    /*
     * 定义场景 当添加用户的时候 密码必须填写
     *           当修改资料的时候  用户若不修改密码   这将会密码框中的验证规则必须改变
     */
    const SCENARIO_ADD = 'add';
    /* public function scenarios()//简单的场景定义不需要单独定义规则；
     {
         return [
             'create' => ['title', 'image', 'content'],
             'update' => ['title', 'content'],
         ];
     }*/
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'User';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'required'],
            [['name', 'description'], 'safe'],//当前字段可以为空 也可以提交、、定义当前管理员角色的验证
            [['password_hash', 'password',], 'required', 'on' => self::SCENARIO_ADD],
            [['status',], 'integer'],
            // 限制密码长度 3 -》 10
            [['password','password2'], 'string', 'max' => 10, 'min' => 3],//['password','string','length'=>[4,10],/*不为空-》*/ 'skipOnEmpty'=>false,/*提示语句 密码短-》*/'tooShot'=>'密码太短了',/*还有对*/
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username', 'email'], 'unique'],
            [['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码',
            'password_reset_token' => '密码重置令牌',
            'email' => '邮箱',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '最后修改时间',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最近登录ip地址',
            'password' => '确认密码',
            'name' => '角色权限',
            'password2'=>'密码'
        ];
    }

    //保存之前执行的代码
    public function beforeSave($insert)
    {
        if ($insert) {//只在添加的时候设置   判断是添加还是修改
            if ($this->password == $this->password2  && $this->password2 != "") { //判断 两次密码 都一致 且不为空
                //加盐加密
                $this->password_hash = \Yii::$app->security->generatePasswordHash($this->password2);
                //密码重置令牌暂不做处理
                //注册时默认当前状态是正常
                $this->status = 1;
                $this->created_at = time();//添加创建时间
                $this->updated_at = //添加跟新时间为当前时间
                $this->last_login_time = time(); // 最后登录时间为当前时间
                //记录当前登陆的IP
                $this->last_login_ip = \Yii::$app->request->getUserIP();
                // 生成水机字符串   将用于cookie验证
                $this->auth_key = Yii::$app->security->generateRandomString();
            } else {
                //两次密码不一致 添加错误
                $this->addError('password', '两次密码输入不一致');
                return false;
            }
        } else {
            //更新 ,如果密码被修改，则重新加密。如果密码没有改，不需要操作

            //密码重置令牌暂不做处理
            $this->updated_at =//添加跟新时间为当前时间
            $this->last_login_time = time();
            //记录当前登陆的IP // 最后登录时间为当前
            $this->last_login_ip = \Yii::$app->request->getUserIP();
            $oldPassword = $this->getOldAttribute('password_hash');//获取旧属性
            //当输入了新密码并且 旧密码输入正确才能改密码
//            if ($this->password_hash != null
//                && $this->password == $this->password_hash
//                && Yii::$app->security->validatePassword($this->password_hash, $oldPassword)
//            ) {//            }
            //在这里直接改密码 没有进行旧密码验证

            if ($this->password2 == '' and  $this->password == '') {//修改时密码为空则不修改密码
                return true;
            }else{

                if ($this->password == $this->password2 and  $this->password2!='') {
                    // c重新加密
                    $this->password_hash = Yii::$app->security->generatePasswordHash($this->password2);
                } else {
                    //两次密码不一致 添加错误
                    $this->addError('password2', '两次密码输入不一致');
                    return false;
                }
            }
        }
        return parent::beforeSave($insert);
    }


    /**
     * 根据给到的ID查询身份。
     * @param string|integer $id 被查询的ID
     * @return IdentityInterface|null 通过ID匹配到的身份对象
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * 根据 token 查询身份。
     * @param string $token 被查询的 token
     * @return IdentityInterface|null 通过 token 得到的身份对象
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string 当前用户ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string 当前用户的（cookie）认证密钥
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

//处理角色权限 多选框
    public static function getRoleAction()
    {
        $authManager = Yii::$app->authManager;
        return ArrayHelper::map($authManager->getRoles(), 'name', 'name');
    }

    //处理角色回显的操作


    public function loadDate($id)
    {
        $roles = Yii::$app->authManager->getRolesByUser($id);
        if ($roles != null) {
            //遍历数组 得到的是每一个身份角色
            foreach ($roles as $key => $role) {
                //将当前的角色回显到模板中
                $this->name[$key] = $key;
            }
        }
    }

    //查询当前的管理员包含的角色  传入当前user的id
    public static function getNowUserRole($id)
    {
        $authManager = Yii::$app->authManager->getRolesByUser($id);
        $roles = [];
        foreach ($authManager as $roleObj) {
            if ($roleObj->name != null) {
                $roles[] = $roleObj->name;
            };
        }
        return $roles;
    }

    //添加管理员角色
    public function addUserRole($id)
    {
        if ($this->name != null) {
            $authManager = Yii::$app->authManager;
            foreach ($this->name as $userRole) {
                $role = $authManager->getRole($userRole);
                if ($authManager->assign($role, $id)) {
                };
            }
            return true;
        } else { // 没有选择管理角色时 直接跳过
            return true;
        }
    }


    //  修改管理员角色
    public function updateUserRole($id, $oldName)
    {
        if ($this != null && $this->name != $oldName) {
            //清除有关当前用户角色
            $authManager = Yii::$app->authManager;
            $authManager->revokeAll($id);
            foreach ($this->name as $userRole) {
                $role = $authManager->getRole($userRole);
                if ($authManager->assign($role, $id)) {

                };
            }
            return true;
        } else { // 没有选择管理角色时 直接跳过
            return true;
        }
    }

    //删除用户关联角色
    public function deleteUserRole($id)
    {
        $authManager = Yii::$app->authManager;
        $role = $authManager->getRolesByUser($id);
        if ($role != null) {
            $authManager->revokeAll($id);
        }
        return true;
    }

}
