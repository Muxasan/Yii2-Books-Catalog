<?php

namespace app\models;

use yii\base\Model;

class ReportForm extends Model
{
    public $year;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // Проверка, что year является числом и длина составляет 4 цифры
            ['year', 'required'],
            ['year', 'integer', 'min' => 1900, 'max' => date('Y')],
            ['year', 'match', 'pattern' => '/^\d{4}$/', 'message' => 'Year must be a 4-digit number.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'year' => 'Year',
        ];
    }
}