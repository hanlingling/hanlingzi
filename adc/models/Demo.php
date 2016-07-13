<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "demo".
 *
 * @property integer $aid
 * @property string $title
 * @property string $times
 * @property string $status
 */
class Demo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'demo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'times', 'status'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aid' => 'Aid',
            'title' => 'Title',
            'times' => 'Times',
            'status' => 'Status',
        ];
    }
}
