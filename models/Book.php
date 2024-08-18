<?php

namespace app\models;

use Exception;
use Yii;
use yii\web\UploadedFile;

class Book extends \yii\db\ActiveRecord
{
    /**
     * Виртуальное свойство для хранения выбранных авторов
     * @var array<int>
     */
    public $authorIds = [];

    /**
     * @var ?UploadedFile|string $coverImageFile
     */
    public $coverImageFile = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'year', 'isbn'], 'required'],
            [['year'], 'integer'],
            [['description'], 'string'],
            [['title', 'isbn', 'cover_image'], 'string', 'max' => 255],
            [['authorIds'], 'safe'],
            [['coverImageFile'], 'file', 'extensions' => 'png, jpg, jpeg', 'skipOnEmpty' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'year' => 'Year',
            'description' => 'Description',
            'isbn' => 'Isbn',
            'cover_image' => 'Cover Image',
        ];
    }

    /**
     * @return void
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->authorIds = $this->getAuthors()->select('id')->column();
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->coverImageFile instanceof UploadedFile) {
                $this->uploadCoverImage();
            }
            return true;
        }
        return false;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->saveAuthors();
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('book_author', ['book_id' => 'id']);
    }

    /**
     * @return array{id:int,full_name:string}
     */
    public function getAuthorsList(): array
    {
        $authors = Author::find()->all();
        return \yii\helpers\ArrayHelper::map($authors, 'id', 'full_name');
    }

    /**
     * Save book_author link
     *
     * @return void
     */
    public function saveAuthors()
    {
        \Yii::$app->db->createCommand()
            ->delete('book_author', ['book_id' => $this->id])
            ->execute();

        if (!empty($this->authorIds)) {
            foreach ($this->authorIds as $authorId) {
                \Yii::$app->db->createCommand()
                    ->insert('book_author', ['book_id' => $this->id, 'author_id' => $authorId])
                    ->execute();
            }
        }
    }

    /**
     * @return bool
     */
    public function uploadCoverImage(): bool
    {
        if ($this->validate()) {
            if (empty($this->coverImageFile->extension)) {
                return false;
            }
            $uploadPath = Yii::getAlias('@webroot/uploads/'); // Путь для сохранения изображений
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true); // Создаем директорию, если она не существует
            }
            $fileName = uniqid() . '.' . $this->coverImageFile->extension;
    
            if ($this->coverImageFile->saveAs($uploadPath . $fileName, false)) {
                $this->cover_image = $fileName;
                return true;
            }
        }
        return false;
    }

    /**
     * @return string|false|null
     */
    public function getCoverImageUrl()
    {
        if ($this->cover_image) {
            return Yii::getAlias('@web') . '/' . $this->cover_image;
        }
        return null;
    }
}
