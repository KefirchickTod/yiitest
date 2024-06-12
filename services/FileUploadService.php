<?php

declare(strict_types=1);

namespace app\services;

use Yii;
use yii\web\UploadedFile;

class FileUploadService
{
    public function uploadFile(UploadedFile $file, string $destination): string|bool
    {
        $filePath = $destination . '/' . $file->baseName . '.' . $file->extension;
        if ($file->saveAs($filePath)) {
            return $filePath;
        }

        return false;
    }

    public function deleteFile(string $filePath): void
    {
        $absolutePath = Yii::getAlias('@webroot') . '/' . $filePath;
        if (file_exists($absolutePath)) {
            unlink($absolutePath);
        }
    }
}
