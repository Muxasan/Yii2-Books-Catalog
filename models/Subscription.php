<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subscription".
 *
 * @property int $id
 * @property int $user_id
 * @property int $author_id
 * @property string|null $created_at
 *
 * @property Author $author
 * @property User $user
 */
class Subscription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscription';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'author_id'], 'required'],
            [['user_id', 'author_id'], 'integer'],
            [['created_at'], 'safe'],
            [['phone'], 'string', 'max' => 15],
            [['phone'], 'match', 'pattern' => '/^\+?\d{10,15}$/'],  // Валидация номера телефона
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'author_id' => 'Author ID',
            'created_at' => 'Created At',
            'phone' => 'Phone Number',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function sendSmsNotification()
    {
        $author = Author::findOne($this->author_id);
        if ($this->phone) {
            $message = 'New book release by ' . $author->full_name . '. Check it out!';
            $apiKey = 'YZ91G1D1K66KK9L028020B30E985W9XBE23OJ2CE8LGO3CX2PMXX9GD8HL972BU3';
            $url = 'https://smspilot.ru/api.php?send&phone=' . urlencode($this->phone) . '&message=' . urlencode($message) . '&apikey=' . $apiKey;
            file_get_contents($url);
        }
    }
}
