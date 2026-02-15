<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Psr\Http\Message\ResponseInterface;
use Tiagoandrepro\WhatsAppCloud\DTO\Media\MediaUploadResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\Media\MediaUrlResponse;
use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final class MediaEndpoint extends AbstractEndpoint
{
    public function uploadFile(string $filePath, string $mimeType): MediaUploadResponse
    {
        if (!is_file($filePath)) {
            throw new \InvalidArgumentException('File does not exist: ' . $filePath);
        }

        $contents = file_get_contents($filePath);
        if ($contents === false) {
            throw new \RuntimeException('Failed to read file: ' . $filePath);
        }

        return $this->uploadContents($contents, basename($filePath), $mimeType);
    }

    /**
     * @param resource $stream
     */
    public function uploadStream($stream, string $filename, string $mimeType): MediaUploadResponse
    {
        if (!is_resource($stream)) {
            throw new \InvalidArgumentException('Stream must be a valid resource.');
        }

        $contents = stream_get_contents($stream);
        if ($contents === false) {
            throw new \RuntimeException('Failed to read from stream.');
        }

        return $this->uploadContents($contents, $filename, $mimeType);
    }

    public function retrieveUrl(string $mediaId): MediaUrlResponse
    {
        Validator::assertNotEmpty($mediaId, 'mediaId');
        $path = $mediaId . '?phone_number_id=' . rawurlencode($this->phoneNumberId);
        $payload = $this->transport->requestJson('GET', $path);

        return MediaUrlResponse::fromArray($payload);
    }

    public function download(string $mediaUrl): ResponseInterface
    {
        Validator::assertNotEmpty($mediaUrl, 'mediaUrl');
        return $this->transport->requestAbsolute('GET', $mediaUrl, ['Accept' => '*/*']);
    }

    private function uploadContents(string $contents, string $filename, string $mimeType): MediaUploadResponse
    {
        Validator::assertNotEmpty($mimeType, 'mimeType');

        $boundary = '----WhatsAppCloudSDK' . bin2hex(random_bytes(8));
        $body = $this->buildMultipartBody($boundary, $contents, $filename, $mimeType);

        $response = $this->transport->requestRaw(
            'POST',
            $this->phoneNumberId . '/media',
            $body,
            [
                'Content-Type' => 'multipart/form-data; boundary=' . $boundary,
                'Accept' => 'application/json',
            ]
        );

        $payload = json_decode((string) $response->getBody(), true);
        if (!is_array($payload)) {
            throw new \RuntimeException('Upload response was not valid JSON.');
        }

        return MediaUploadResponse::fromArray($payload);
    }

    private function buildMultipartBody(string $boundary, string $contents, string $filename, string $mimeType): string
    {
        $eol = "\r\n";

        $fields = [
            [
                'name' => 'messaging_product',
                'value' => 'whatsapp',
            ],
        ];

        $body = '';
        foreach ($fields as $field) {
            $body .= '--' . $boundary . $eol;
            $body .= 'Content-Disposition: form-data; name="' . $field['name'] . '"' . $eol . $eol;
            $body .= $field['value'] . $eol;
        }

        $body .= '--' . $boundary . $eol;
        $body .= 'Content-Disposition: form-data; name="file"; filename="' . $filename . '"' . $eol;
        $body .= 'Content-Type: ' . $mimeType . $eol . $eol;
        $body .= $contents . $eol;
        $body .= '--' . $boundary . '--' . $eol;

        return $body;
    }
}
