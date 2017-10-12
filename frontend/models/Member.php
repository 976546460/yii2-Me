<?php

namespace frontend\models;

use Yii;
use yii\web\IdentityInterface;

/**未修复bug:
 * 1.用户注册验
 *
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $email
 * @property string $tel
 * @property integer $last_login_time
 * @property integer $last_login_ip
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password;//密码明文
    public $password_new;//密码明文
    public $code;//验证码
    public $chb;//同意注册勾选框
    public $smsCode;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }
    const SCENARIO_REGISTER = 'register';//注册使用验证
    const SCENARIO_EDIT = 'userEdit';//修改密码时验证
    const SCENARIO_API_REGISTER = 'api_register';//api注册使用验证码
    const SCENARIO_WEIXIN_EDIT_PASSWORD='weixin';//微信公众品台验证场景(WeChat控制器);
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','password_hash','password','smsCode'], 'required','on'=>self::SCENARIO_REGISTER],
            [['last_login_time', 'last_login_ip', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['auth_key'], 'string', 'max' => 225],
            [['password_hash', 'email'], 'string', 'max' => 225],
            [['tel'], 'string', 'max' => 11],
            [['smsCode'],'required'],
            [['username','smsCode'], 'required','on'=>self::SCENARIO_EDIT],
            [['password_new','password'],'string','on'=>self::SCENARIO_EDIT],
            [['code'], 'captcha'],
            ['code','captcha','on'=>self::SCENARIO_API_REGISTER,'captchaAction'=>'api/captcha'],//使用api控制器中的验证码
            [['password_new','password'],'required','on'=>self::SCENARIO_WEIXIN_EDIT_PASSWORD],
            ['code','captcha','on'=>self::SCENARIO_WEIXIN_EDIT_PASSWORD,'captchaAction'=>'wechat/captcha'],//使用WeChat控制器中的验证码
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名 :',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码 :',
            'email' => '邮箱 :',
            'tel' => '电话 :',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
            'status' => 'status',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
            'password' => '确认密码 :',
            'password_new' => '新密码 :',
            'code' => '验证码 :',
            'smsCode'=>'短信验证:'
        ];
    }

    //保存之前 自动调用
    public function beforeSave($insert)
    {
        if($this->smsCode!=\Yii::$app->cache->get('tel_'.$this->tel)){//短信验证码
            $this->addError('smsCode','验证码错误!');
//            var_dump(\Yii::$app->cache->get('tel_'.$this->tel));exit;
            return false;
        }
        //判断是数据添加还是修改
        if ($insert) {
            if($this->password == $this->password_hash && $this->password_hash!='') {
                $this->created_at =
                $this->updated_at =
                $this->last_login_time = time();
                $this->last_login_ip = ip2long(\Yii::$app->request->getUserIP());
                // 生成水机字符串   将用于cookie验证
                $this->auth_key = Yii::$app->security->generateRandomString();
                //注册时默认当前状态是正常
                $this->status = 1;
                //加密处理
                $this->password_hash=\Yii::$app->security->generatePasswordHash($this->password_hash);

            }else{
                $this->addError('password','密码不一致!');
                return false;
            }
        } else {
            //判断是否修改了密码
            if($this->password_hash!=null) {//用户修改了密码
                if ($this->password == $this->password_hash) {
                    $this->password_hash = \Yii::$app->security->generatePasswordHash($this->password_hash); //重新加密
                }else{
                    $this->addError('password_hash','两此密码不一致');
                }
            }
            $this->updated_at =
            $this->last_login_time = time();
            $this->last_login_ip = ip2long(\Yii::$app->request->getUserIP());
            // 生成随机字符串   将用于cookie验证
            $this->auth_key = Yii::$app->security->generateRandomString();
            //注册时默认当前状态是正常
            $this->status = 1;
        }
        return  parent::beforeSave($insert);
    }


    /**
     * 根据给到的ID查询身份。
     *
     * @param string|integer $id 被查询的ID
     * @return IdentityInterface|null 通过ID匹配到的身份对象
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * 根据 token 查询身份。
     *
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
}