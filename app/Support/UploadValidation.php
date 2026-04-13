<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Throwable;

class UploadValidation
{
    private const MAIN_CONTENT_IMAGE_EXTENSIONS = [
        'jpg',
        'jpeg',
        'png',
        'webp',
        'gif',
    ];

    private const IMAGE_EXTENSIONS = [
        'jpg',
        'jpeg',
        'png',
        'bmp',
        'gif',
        'svg',
        'webp',
    ];

    private const VIDEO_EXTENSIONS = [
        'mp4',
        'webm',
        'ogg',
        'ogv',
        'mov',
    ];

    public static function mainContentMediaRules(): array
    {
        return [
            'file',
            self::mimeValidationRule(
                'mimetypes:image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/ogg,video/quicktime',
                array_merge(self::MAIN_CONTENT_IMAGE_EXTENSIONS, self::VIDEO_EXTENSIONS),
            ),
            'max:512000',
        ];
    }

    public static function imageRules(int $maxKilobytes = 10240): array
    {
        return [
            'file',
            self::mimeValidationRule(
                'image',
                self::IMAGE_EXTENSIONS,
            ),
            'max:' . $maxKilobytes,
        ];
    }

    public static function detectMainContentMediaType(UploadedFile $file): string
    {
        if (self::mimeGuessingAvailable()) {
            try {
                $mimeType = (string) $file->getMimeType();

                if (str_starts_with($mimeType, 'video/')) {
                    return 'video';
                }

                if (str_starts_with($mimeType, 'image/')) {
                    return 'image';
                }
            } catch (Throwable) {
                // Fallback to extension-based detection when MIME guessing is unavailable.
            }
        }

        return self::isVideoExtension($file->getClientOriginalExtension()) ? 'video' : 'image';
    }

    public static function storeUploadedFile(UploadedFile $file, string $path, string $disk = 'public', ?string $root = null): string
    {
        $normalizedPath = trim(str_replace('\\', '/', $path), '/');
        $targetDirectory = $root ?: self::diskRoot($disk);

        if ($normalizedPath !== '') {
            $targetDirectory .= DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $normalizedPath);
        }

        if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0777, true) && !is_dir($targetDirectory)) {
            throw new \RuntimeException('Unable to create upload directory: ' . $targetDirectory);
        }

        $storedFileName = self::storedFileName($file);

        $file->move($targetDirectory, $storedFileName);

        return $normalizedPath !== '' ? $normalizedPath . '/' . $storedFileName : $storedFileName;
    }

    public static function storedFileName(UploadedFile $file): string
    {
        $extension = self::storedExtension($file);
        $fileName = Str::random(40);

        return $extension !== '' ? $fileName . '.' . $extension : $fileName;
    }

    private static function mimeValidationRule(string $mimeRule, array $fallbackExtensions): string
    {
        if (self::mimeGuessingAvailable()) {
            return $mimeRule;
        }

        return 'extensions:' . implode(',', $fallbackExtensions);
    }

    private static function mimeGuessingAvailable(): bool
    {
        return class_exists('finfo');
    }

    private static function diskRoot(string $disk): string
    {
        $configuredRoot = config('filesystems.disks.' . $disk . '.root');

        if (is_string($configuredRoot) && $configuredRoot !== '') {
            return $configuredRoot;
        }

        return storage_path('app/' . trim($disk, '/'));
    }

    private static function storedExtension(UploadedFile $file): string
    {
        if (self::mimeGuessingAvailable()) {
            try {
                $extension = strtolower((string) $file->guessExtension());

                if ($extension !== '') {
                    return $extension;
                }
            } catch (Throwable) {
                // Fallback to the client extension when MIME-based extension guessing fails.
            }
        }

        return self::sanitizeExtension($file->getClientOriginalExtension());
    }

    private static function isVideoExtension(string $extension): bool
    {
        return in_array(self::sanitizeExtension($extension), self::VIDEO_EXTENSIONS, true);
    }

    private static function sanitizeExtension(string $extension): string
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '', $extension));
    }
}
