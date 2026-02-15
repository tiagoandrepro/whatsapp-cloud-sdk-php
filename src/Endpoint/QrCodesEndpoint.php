<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\DTO\Common\SuccessResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\QrCode\QrCodeResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\QrCode\QrCodesResponse;
use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final class QrCodesEndpoint extends AbstractEndpoint
{
    public function getQrCode(string $code): QrCodeResponse
    {
        Validator::assertNotEmpty($code, 'code');

        $payload = $this->transport->requestJson('GET', $this->phoneNumberId . '/message_qrdls/' . rawurlencode($code));

        return QrCodeResponse::fromArray($payload);
    }

    /**
     * @param list<string>|null $fields
     */
    public function listQrCodes(?array $fields = null, ?string $code = null): QrCodesResponse
    {
        $params = $this->buildFieldsParams($fields);
        if ($code !== null) {
            Validator::assertNotEmpty($code, 'code');
            $params['code'] = $code;
        }

        $payload = $this->transport->requestJson('GET', $this->phoneNumberId . '/message_qrdls' . $this->buildQuery($params));

        return QrCodesResponse::fromArray($payload);
    }

    public function createQrCode(string $prefilledMessage, ?string $generateQrImage = null): QrCodeResponse
    {
        Validator::assertNotEmpty($prefilledMessage, 'prefilledMessage');

        $body = ['prefilled_message' => $prefilledMessage];
        if ($generateQrImage !== null) {
            Validator::assertNotEmpty($generateQrImage, 'generateQrImage');
            $body['generate_qr_image'] = $generateQrImage;
        }

        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/message_qrdls', $body);

        return QrCodeResponse::fromArray($payload);
    }

    public function updateQrCode(string $code, string $prefilledMessage): QrCodeResponse
    {
        Validator::assertNotEmpty($code, 'code');
        Validator::assertNotEmpty($prefilledMessage, 'prefilledMessage');

        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/message_qrdls', [
            'prefilled_message' => $prefilledMessage,
            'code' => $code,
        ]);

        return QrCodeResponse::fromArray($payload);
    }

    public function deleteQrCode(string $code): SuccessResponse
    {
        Validator::assertNotEmpty($code, 'code');

        $payload = $this->transport->requestJson('DELETE', $this->phoneNumberId . '/message_qrdls/' . rawurlencode($code));

        return SuccessResponse::fromArray($payload);
    }

    /**
     * @param list<string>|null $fields
     * @return array<string, string>
     */
    private function buildFieldsParams(?array $fields): array
    {
        if ($fields === null || $fields === []) {
            return [];
        }

        return ['fields' => implode(',', $fields)];
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
