<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\DTO\ResumableUpload\UploadHandleResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\ResumableUpload\UploadSessionResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\ResumableUpload\UploadStatusResponse;
use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final class ResumableUploadEndpoint extends AbstractEndpoint
{
    public function createUploadSession(int $fileLength, string $fileType, ?string $fileName = null): UploadSessionResponse
    {
        if ($fileLength <= 0) {
            throw new \InvalidArgumentException('fileLength must be greater than zero.');
        }
        Validator::assertNotEmpty($fileType, 'fileType');

        $params = [
            'file_length' => (string) $fileLength,
            'file_type' => $fileType,
        ];

        if ($fileName !== null) {
            $params['file_name'] = $fileName;
        }

        $path = 'app/uploads' . $this->buildQuery($params);
        $payload = $this->transport->requestJson('POST', $path);

        return UploadSessionResponse::fromArray($payload);
    }

    public function uploadFileData(string $uploadId, string $filePath, string $mimeType, int $fileOffset = 0): UploadHandleResponse
    {
        Validator::assertNotEmpty($uploadId, 'uploadId');
        Validator::assertNotEmpty($mimeType, 'mimeType');

        if (!is_file($filePath)) {
            throw new \InvalidArgumentException('File does not exist: ' . $filePath);
        }

        $contents = file_get_contents($filePath);
        if ($contents === false) {
            throw new \RuntimeException('Failed to read file: ' . $filePath);
        }

        return $this->uploadData($uploadId, $contents, $mimeType, $fileOffset);
    }

    public function uploadData(string $uploadId, string $contents, string $mimeType, int $fileOffset = 0): UploadHandleResponse
    {
        Validator::assertNotEmpty($uploadId, 'uploadId');
        Validator::assertNotEmpty($mimeType, 'mimeType');

        if ($fileOffset < 0) {
            throw new \InvalidArgumentException('fileOffset must be zero or greater.');
        }

        $response = $this->transport->requestRaw('POST', $uploadId, $contents, [
            'Content-Type' => $mimeType,
            'file_offset' => (string) $fileOffset,
        ]);

        $payload = json_decode((string) $response->getBody(), true);
        if (!is_array($payload)) {
            throw new \RuntimeException('Upload response was not valid JSON.');
        }

        return UploadHandleResponse::fromArray($payload);
    }

    public function queryUploadStatus(string $uploadId): UploadStatusResponse
    {
        Validator::assertNotEmpty($uploadId, 'uploadId');

        $payload = $this->transport->requestJson('GET', $uploadId);

        return UploadStatusResponse::fromArray($payload);
    }

    /**
     * @param array<string, string> $params
     */
    private function buildQuery(array $params): string
    {
        if ($params === []) {
            return '';
        }

        return '?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986);
    }
}
