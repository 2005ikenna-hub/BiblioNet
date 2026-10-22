<?php

namespace App\Core;

use RuntimeException;

class ImageUpload
{
    private const MAX_BYTES = 5242880;
    private const COLLECTIONS = ['livres', 'adherents'];
    private const MIME_TYPES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    public static function store(string $field, string $collection): ?string
    {
        if (!in_array($collection, self::COLLECTIONS, true)) {
            throw new RuntimeException('Type d’image non autorisé.');
        }

        $upload = $_FILES[$field] ?? null;
        if (!is_array($upload) || !isset($upload['error'])) {
            throw new RuntimeException('Le fichier envoyé est invalide.');
        }
        if ($upload['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($upload['error'] !== UPLOAD_ERR_OK || !is_string($upload['tmp_name'] ?? null)) {
            throw new RuntimeException('Le téléversement de l’image a échoué.');
        }
        if (!is_uploaded_file($upload['tmp_name'])) {
            throw new RuntimeException('Le fichier envoyé est invalide.');
        }

        $size = filesize($upload['tmp_name']);
        if ($size === false || $size > self::MAX_BYTES) {
            throw new RuntimeException('L’image ne doit pas dépasser 5 Mo.');
        }

        $mimeType = (new \finfo(FILEINFO_MIME_TYPE))->file($upload['tmp_name']);
        $imageInfo = @getimagesize($upload['tmp_name']);
        if (!isset(self::MIME_TYPES[$mimeType]) || !$imageInfo || ($imageInfo['mime'] ?? '') !== $mimeType) {
            throw new RuntimeException('Format d’image invalide. Utilisez JPEG, PNG ou WebP.');
        }

        $relativeDirectory = 'uploads/' . $collection;
        $uploadDirectory = APP_ROOT . '/public/' . $relativeDirectory;
        if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
            throw new RuntimeException('Le dossier de stockage des images est indisponible.');
        }

        $fileName = bin2hex(random_bytes(16)) . '.' . self::MIME_TYPES[$mimeType];
        if (!move_uploaded_file($upload['tmp_name'], $uploadDirectory . '/' . $fileName)) {
            throw new RuntimeException('Impossible d’enregistrer l’image.');
        }

        return $relativeDirectory . '/' . $fileName;
    }

    public static function delete(?string $imagePath, string $collection): void
    {
        if (!in_array($collection, self::COLLECTIONS, true) || $imagePath === null) {
            return;
        }

        $prefix = 'uploads/' . $collection . '/';
        $fileName = substr($imagePath, strlen($prefix));
        if (!str_starts_with($imagePath, $prefix) || !preg_match('/\A[a-f0-9]{32}\.(jpg|png|webp)\z/', $fileName)) {
            return;
        }

        $filePath = APP_ROOT . '/public/' . $imagePath;
        if (is_file($filePath)) {
            unlink($filePath);
        }
    }
}