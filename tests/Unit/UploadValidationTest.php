<?php

use App\Support\UploadValidation;
use Illuminate\Http\UploadedFile;

test('it keeps a safe extension when generating stored file names', function () {
    $file = UploadedFile::fake()->image('banner.png');

    $storedFileName = UploadValidation::storedFileName($file);

    expect($storedFileName)->toEndWith('.png');
});

test('it detects mp4 uploads as video', function () {
    $file = UploadedFile::fake()->create('clip.mp4', 256, 'video/mp4');

    expect(UploadValidation::detectMainContentMediaType($file))->toBe('video');
});

test('it stores uploads without using the filesystem driver', function () {
    $temporaryRoot = getcwd() . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '.tmp' . DIRECTORY_SEPARATOR . 'public';
    $temporaryBase = dirname($temporaryRoot);
    $sourceFile = tempnam(sys_get_temp_dir(), 'upload-validation-');
    $deleteDirectory = function (string $directory) use (&$deleteDirectory): void {
        if (!is_dir($directory)) {
            return;
        }

        foreach (array_diff(scandir($directory), ['.', '..']) as $item) {
            $path = $directory . DIRECTORY_SEPARATOR . $item;

            if (is_dir($path)) {
                $deleteDirectory($path);
                continue;
            }

            @unlink($path);
        }

        @rmdir($directory);
    };

    $deleteDirectory($temporaryBase);
    file_put_contents($sourceFile, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9sX6lzQAAAAASUVORK5CYII='));

    $file = new UploadedFile(
        $sourceFile,
        'gallery-photo.png',
        'image/png',
        null,
        true,
    );

    $storedPath = UploadValidation::storeUploadedFile($file, 'galleries', 'public', $temporaryRoot);

    expect($storedPath)->toStartWith('galleries/')
        ->and(file_exists($temporaryRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $storedPath)))->toBeTrue();
});
