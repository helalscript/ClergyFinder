<?php

namespace App\Services\Clergy;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use RuntimeException;

class ClergyProfileService
{
    public function normalizeStringOrArrayField(mixed $value): ?array
    {
        if (is_string($value)) {
            return array_values(array_filter(array_map('trim', explode(',', $value))));
        }

        if (is_array($value)) {
            return array_values(array_filter($value));
        }

        return null;
    }

    /**
     * Upload clergy credential files via multipart form (credential_documents[]).
     *
     * @return list<string> Public-relative paths (e.g. uploads/clergy/credentials/...)
     */
    public function uploadCredentialDocuments(Request $request, ?array $replacePaths = null): array
    {
        $files = $request->file('credential_documents');

        if ($files instanceof UploadedFile) {
            $files = [$files];
        }

        if (! is_array($files) || count($files) === 0) {
            throw new RuntimeException('no_credential_files');
        }

        $paths = [];

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile || ! $file->isValid()) {
                continue;
            }

            $path = Helper::fileUpload(
                $file,
                'clergy/credentials',
                time().'_'.getFileName($file)
            );

            if ($path) {
                $paths[] = $path;
            }
        }

        if ($paths === []) {
            throw new RuntimeException('no_credential_files');
        }

        if ($replacePaths !== null) {
            $this->deleteCredentialDocumentPaths($replacePaths);
        }

        return $paths;
    }

    /**
     * @param  list<string>|null  $paths
     */
    public function deleteCredentialDocumentPaths(?array $paths): void
    {
        if (! is_array($paths)) {
            return;
        }

        foreach ($paths as $path) {
            if (! is_string($path) || $path === '') {
                continue;
            }

            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                $path = ltrim((string) parse_url($path, PHP_URL_PATH), '/');
            }

            $fullPath = public_path($path);
            if (file_exists($fullPath)) {
                Helper::fileDelete($fullPath);
            }
        }
    }
}
