<?php

declare(strict_types=1);

namespace App\Services;

/**
 * File Upload Service
 * 
 * Handles all file upload operations with validation and security
 * Used across multiple controllers (User, Post, Category, etc.)
 */
class FileUploadService
{
    // Configuration constants
    private const UPLOAD_BASE_DIR = 'public/uploads/';
    private const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB
    private const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    /**
     * Upload avatar image
     * 
     * @param array $file File from $_FILES
     * @param string|null $oldFilePath Old file to delete
     * @return array ['success' => bool, 'path' => string|null, 'error' => string|null]
     */
    public function uploadAvatar(array $file, ?string $oldFilePath = null): array
    {
        return $this->uploadImage($file, 'avatars', $oldFilePath, 500, 500);
    }

    /**
     * Upload post thumbnail
     * 
     * @param array $file File from $_FILES
     * @param string|null $oldFilePath Old file to delete
     * @return array ['success' => bool, 'path' => string|null, 'error' => string|null]
     */
    public function uploadPostThumbnail(array $file, ?string $oldFilePath = null): array
    {
        return $this->uploadImage($file, 'posts', $oldFilePath, 800, 600);
    }

    /**
     * Generic image upload with validation and optimization
     * 
     * @param array $file File from $_FILES
     * @param string $directory Subdirectory name
     * @param string|null $oldFilePath Old file to delete
     * @param int $maxWidth Maximum width
     * @param int $maxHeight Maximum height
     * @return array ['success' => bool, 'path' => string|null, 'error' => string|null]
     */
    private function uploadImage(
        array $file,
        string $directory,
        ?string $oldFilePath = null,
        int $maxWidth = 1000,
        int $maxHeight = 1000
    ): array {
        // Validate file
        $validationError = $this->validateImage($file);
        if ($validationError) {
            return [
                'success' => false,
                'path' => null,
                'error' => $validationError
            ];
        }

        // Create upload directory
        $uploadDir = self::UPLOAD_BASE_DIR . $directory . '/' . date('Y/m') . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = $this->generateUniqueFilename($extension);
        $uploadPath = $uploadDir . $filename;

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return [
                'success' => false,
                'path' => null,
                'error' => 'Không thể lưu file'
            ];
        }

        // Optimize image
        $this->optimizeImage($uploadPath, $extension, $maxWidth, $maxHeight);

        // Delete old file if exists
        if ($oldFilePath && file_exists($oldFilePath)) {
            $this->deleteFile($oldFilePath);
        }

        return [
            'success' => true,
            'path' => $uploadPath,
            'error' => null
        ];
    }

    /**
     * Validate image file
     * 
     * @param array $file File from $_FILES
     * @return string|null Error message or null if valid
     */
    private function validateImage(array $file): ?string
    {
        // Check upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return match ($file['error']) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'File quá lớn',
                UPLOAD_ERR_PARTIAL => 'File tải lên không hoàn chỉnh',
                UPLOAD_ERR_NO_FILE => 'Không có file nào được tải lên',
                default => 'Lỗi tải file'
            };
        }

        // Check file size
        if ($file['size'] > self::MAX_FILE_SIZE) {
            return 'Kích thước file không được vượt quá ' . (self::MAX_FILE_SIZE / 1024 / 1024) . 'MB';
        }

        // Check file size minimum
        if ($file['size'] < 1024) { // Less than 1KB
            return 'File quá nhỏ, có thể bị lỗi';
        }

        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, self::ALLOWED_IMAGE_TYPES)) {
            return 'Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP)';
        }

        // Validate extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_IMAGE_EXTENSIONS)) {
            return 'Định dạng file không được hỗ trợ';
        }

        // Verify it's actually an image using getimagesize
        $imageInfo = @getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return 'File không phải là ảnh hợp lệ';
        }

        // Check image dimensions (not too small)
        if ($imageInfo[0] < 50 || $imageInfo[1] < 50) {
            return 'Ảnh quá nhỏ (tối thiểu 50x50 pixels)';
        }

        // Security: Check for PHP code in image
        $content = file_get_contents($file['tmp_name']);
        if (preg_match('/<\?php|<\?=|<script/i', $content)) {
            return 'File chứa nội dung không an toàn';
        }

        return null;
    }

    /**
     * Optimize and resize image
     * 
     * @param string $filePath Path to file
     * @param string $extension File extension
     * @param int $maxWidth Maximum width
     * @param int $maxHeight Maximum height
     * @return bool Success status
     */
    private function optimizeImage(
        string $filePath,
        string $extension,
        int $maxWidth = 1000,
        int $maxHeight = 1000
    ): bool {
        // Get image info
        list($width, $height, $type) = getimagesize($filePath);

        // Skip if image is small enough
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return true;
        }

        // Calculate new dimensions (maintain aspect ratio)
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = (int)($width * $ratio);
        $newHeight = (int)($height * $ratio);

        // Create image resource based on type
        $source = match ($extension) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($filePath),
            'png' => @imagecreatefrompng($filePath),
            'gif' => @imagecreatefromgif($filePath),
            'webp' => @imagecreatefromwebp($filePath),
            default => null
        };

        if (!$source) {
            return false;
        }

        // Create new image
        $destination = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG and GIF
        if (in_array($extension, ['png', 'gif'])) {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
            $transparent = imagecolorallocatealpha($destination, 0, 0, 0, 127);
            imagefilledrectangle($destination, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Resize
        imagecopyresampled(
            $destination,
            $source,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );

        // Save with compression
        $result = match ($extension) {
            'jpg', 'jpeg' => imagejpeg($destination, $filePath, 85),
            'png' => imagepng($destination, $filePath, 8),
            'gif' => imagegif($destination, $filePath),
            'webp' => imagewebp($destination, $filePath, 85),
            default => false
        };

        // Free memory
        imagedestroy($source);
        imagedestroy($destination);

        return $result;
    }

    /**
     * Generate unique filename
     * 
     * @param string $extension File extension
     * @return string Unique filename
     */
    private function generateUniqueFilename(string $extension): string
    {
        return uniqid('', true) . '_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
    }

    /**
     * Delete file safely
     * 
     * @param string $filePath Path to file
     * @return bool Success status
     */
    public function deleteFile(string $filePath): bool
    {
        if (empty($filePath)) {
            return false;
        }

        // Security: Ensure file is within upload directory
        $realPath = realpath($filePath);
        $uploadBasePath = realpath(self::UPLOAD_BASE_DIR);

        if ($realPath && $uploadBasePath && str_starts_with($realPath, $uploadBasePath)) {
            return @unlink($realPath);
        }

        return false;
    }

    /**
     * Get file info
     * 
     * @param string $filePath Path to file
     * @return array|null File info or null
     */
    public function getFileInfo(string $filePath): ?array
    {
        if (!file_exists($filePath)) {
            return null;
        }

        $imageInfo = @getimagesize($filePath);

        return [
            'path' => $filePath,
            'size' => filesize($filePath),
            'width' => $imageInfo[0] ?? null,
            'height' => $imageInfo[1] ?? null,
            'mime' => $imageInfo['mime'] ?? null,
            'extension' => pathinfo($filePath, PATHINFO_EXTENSION),
            'created_at' => filectime($filePath),
            'modified_at' => filemtime($filePath)
        ];
    }
}
