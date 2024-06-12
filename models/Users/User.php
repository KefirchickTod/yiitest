<?php

namespace app\models\Users;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $phone
 * @property string $last_name
 * @property string $first_name
 * @property string $middle_name
 * @property string $document
 */
class User extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $documentFile;

    public static function tableName(): string
    {
        return 'user';
    }

    public function rules(): array
    {
        return [
            [['last_name', 'first_name'], 'required'],
            [['email', 'phone', 'last_name', 'first_name', 'middle_name'], 'string', 'max' => 255],
            [['documentFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf, doc, docx'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'email' => 'Email',
            'phone' => 'Phone',
            'last_name' => 'Last Name',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'document' => 'Document',
            'documentFile' => 'Upload Document',
        ];
    }
}
