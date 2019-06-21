<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

use app\models\Role;
use app\models\Status;
use app\models\UserType;
use app\models\Profile;

class User extends ActiveRecord implements IdentityInterface
{
    
    const STATUS_ACTIVE = 10;

      
    public static function tablename() {

        return 'user';
    }


    public function behaviors(){

        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ],
                'value' => new Expression('NOW()')    
            ]
        ];
    }

    public function roles(){
        return [
            ['role_id', 'default', 'value' => 10],
            [['role_id'], 'in', 'value' => array_keys($this->getRoleList())],

            ['status_id', 'default', self::STATUS_ACTIVE],
            [['status_id'], 'in', 'value' => array_keys($this->getStatusList())],

            ['user_type_id', 'default', 'value' => 10],
            [['user_type_id'], 'in', 'value' => array_keys($this->getUserTypeList())],

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'unique'],
            ['email', 'email'], 

            


        ];
    }


    public function attributeLabels() {

        return [
            /* Your other attribute labels */
            'roleName' => Yii::t('app', 'Role'),
            'statusName' => Yii::t('app', 'Status'),
            'profileId' => Yii::t('app', 'Profile'),
            'profileLink' => Yii::t('app', 'Profile'),
            'userLink' => Yii::t('app', 'User'),
            'username' => Yii::t('app', 'User'),
            'userTypeName' => Yii::t('app', 'User Type'),
            'userTypeId' => Yii::t('app', 'User Type'),
            'userIdLink' => Yii::t('app', 'ID'),
            ];
       
    }


    public function getRole(){
        
        return $this->hasOne(Role::className(), ['role_value' => 'role_id']);
    }


    public function getRoleName(){

        return $this->role ? $this->role->role_name : "- no role -";     
    }


    public function getRoleList(){
        $droptions = Role::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'role_value', 'role_name');        
    }


    public function getStatus(){

        return $this->hasOne(Status::className(), ['status_value' => 'status_id']);
    }

    public function getStatusName(){

        return $this->status ? $this->status->status_name : '- no status -';
    }

    public function getStatusList(){
        $droptions = Status::find()->asArray()->all();
        return ArrayHelper::map($droptions, ['status_value', 'status_name']);
    }


    public function getUserType(){

        return $this->hasOne(UserType::className(), 'user_type_value', 'user_type_id');
    }


    public function getUserTypeName(){

        return $this->userType ? $this->userType->user_type_name : '- no user type -';
    }


    public function getUserTypeList(){

        $droption = UserType::find()->asArray()->all();
        return ArrayHelper::map($droption, ['user_type_name', 'user_type_value']);
    }

    public function getUserTypeId(){

        return $this->userType ? $this->userType->id : '- no user_type id -';
    }


    public function getProfile() {

        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    public function getProfileId(){

        return $this->profile ? $this->profile->id : "none";
    } 


    public function getProfileLink(){
        $url = Url::to(["profile/view", 'id' => $this->profileId]);
        $options = [];

        return Html::a($this->profile ? 'profile': 'none', $url, $options);
    }


    public function getUserIdLink(){
        $url = Url::to(['user/update', 'id' => $this->id]);
        $options = [];

        return Html::a($this->id, $url, $options);
    }


    public function getUserLink(){
        $url = Url::to(['user\view', 'id' => $this->id]);
        $options = [];

        return Html::a($this->username, $url, $options);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status_id' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status_id' => self::STATUS_ACTIVE]);
    }


    public static function findByPasswordResetToken($token) {

        $expire = Yii::$app->params('user.passwordResetToken');
        $parts = explode("_", $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            return null;
        } 

        return findOne(['password_reset_token' => $token, 'status_id' => self::ACTIVE_STATUS]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    // *
    //  * {@inheritdoc}
     
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }


    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }


    public function generateAuthKey(){
        $this->auth_key = Yii::$app->security->generateRandomString();
    } 

    public function generatePasswordResetToken(){
       $this->password_reset_token = Yii::$app->security->generateRandomString() + time();
    }

    public function removePasswordResetToken(){
        $this->password_reset_token = null;
    }







}
