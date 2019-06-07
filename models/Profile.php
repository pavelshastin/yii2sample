<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\helplers\Html;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use \yii\db\ActiveRecord;

use app\models\User;
use app\models\Gender;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $birthday
 * @property int $gender_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Gender $gender
 */
class Profile extends ActiveRecord
{
    


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'first_name', 'last_name', 'birthday', 'gender_id'], 'required'],
            [['user_id', 'gender_id'], 'integer'],
            [['first_name', 'last_name'], 'string'],
            [['birthday', 'created_at', 'updated_at'], 'safe'],
            [['gender_id'], 'exist', 'skipOnError' => true, 'targetClass' => Gender::className(), 'targetAttribute' => ['gender_id' => 'id']],
            [['gender_id'], 'in', 'range' => array_keys($this->getGenderList())]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [ 
            'id' => 'ID',
            'user_id' => 'User ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'birthday' => 'Birthday',
            'gender_id' => 'Gender ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'genderName' => Yii::t("app", "Gender"),
            'userLink' => Yii::t("app", "User"),
            'profileIdLink' => Yii::t("app", 'Profile')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGender()
    {
        return $this->hasOne(Gender::className(), ['id' => 'gender_id']);
    }

    public function getGenderName(){

        return $this->gender->gender_name;
    }

    public function getGenderList(){

        $droption = Gender::find()->asArray()->all();
        return ArrayHelper::map($droption, 'id', 'gender_name');
    }


    public function getUser(){

        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    public function getUserName(){

        return $this->user->username;
    }

    public function getUserId(){

        return $this->user ? $this->user->id : "none";
    }

    public function getUserLink(){
        $url = Url::to(['user/view', 'id' => $this->UserId]);
        $options = [];

        return Html::a($this->getUserName(), $url, $options);
    }

    public function getProfileIdLink(){
        $url = Url::to(['profile/update', 'id' => $this->id]);
        $options = [];

        return Html::a($this->id, $url, $options);
    }
}
