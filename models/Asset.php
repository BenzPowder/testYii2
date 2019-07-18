<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asset".
 *
 * @property int $id
 * @property string $type
 * @property string $number
 * @property string $name
 * @property string $create_date
 * @property string $image
 */
class Asset extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    
    public static function tableName()
    {
        return 'asset';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'string'],
            [['create_date'], 'safe'],
            [['number', 'name'], 'string', 'max' => 45],
            [['image'], 'file', 'extensions' => 'jpeg, jpg, png, gif'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'number' => 'Number',
            'name' => 'Name',
            'create_date' => 'Create Date',
            'image' => 'Upload Image',
        ];
    }
}
