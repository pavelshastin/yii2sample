<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gender".
 *
 * @property int $id
 * @property string $gender_name
 *
 * @property Profile[] $profiles
 */
class Gender extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gender';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gender_name'], 'required'],
            [['gender_name'], 'string', 'max' => 65],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gender_name' => 'Gender Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::className(), ['gender_id' => 'id']);
    }
}
