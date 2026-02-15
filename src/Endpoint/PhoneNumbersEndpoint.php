<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\DTO\Common\SuccessResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\PhoneNumber\DisplayNameStatusResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\PhoneNumber\PhoneNumberResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\PhoneNumber\PhoneNumbersResponse;
use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final class PhoneNumbersEndpoint extends AbstractEndpoint
{
    /**
     * @param list<string>|null $fields
     */
    public function listAll(string $wabaId, ?array $fields = null, ?string $filtering = null): PhoneNumbersResponse
    {
        Validator::assertNotEmpty($wabaId, 'wabaId');

        $params = $this->buildFieldsParams($fields);
        if ($filtering !== null) {
            $params['filtering'] = $filtering;
        }

        $path = $wabaId . '/phone_numbers' . $this->buildQuery($params);
        $payload = $this->transport->requestJson('GET', $path);

        return PhoneNumbersResponse::fromArray($payload);
    }

    /**
     * @param list<string>|null $fields
     */
    public function getById(string $phoneNumberId, ?array $fields = null): PhoneNumberResponse
    {
        Validator::assertNotEmpty($phoneNumberId, 'phoneNumberId');

        $path = $phoneNumberId . $this->buildQuery($this->buildFieldsParams($fields));
        $payload = $this->transport->requestJson('GET', $path);

        return PhoneNumberResponse::fromArray($payload);
    }

    public function getDisplayNameStatus(string $phoneNumberId): DisplayNameStatusResponse
    {
        Validator::assertNotEmpty($phoneNumberId, 'phoneNumberId');

        $path = $phoneNumberId . $this->buildQuery(['fields' => 'name_status']);
        $payload = $this->transport->requestJson('GET', $path);

        return DisplayNameStatusResponse::fromArray($payload);
    }

    public function requestVerificationCode(string $phoneNumberId, string $codeMethod, string $locale): SuccessResponse
    {
        Validator::assertNotEmpty($phoneNumberId, 'phoneNumberId');
        Validator::assertNotEmpty($codeMethod, 'codeMethod');
        Validator::assertNotEmpty($locale, 'locale');

        $normalized = strtoupper($codeMethod);
        if (!in_array($normalized, ['SMS', 'VOICE'], true)) {
            throw new \InvalidArgumentException('codeMethod must be SMS or VOICE.');
        }

        $payload = $this->transport->requestJson('POST', $phoneNumberId . '/request_code', [
            'code_method' => $normalized,
            'locale' => $locale,
        ]);

        return SuccessResponse::fromArray($payload);
    }

    public function verifyCode(string $phoneNumberId, string $code): SuccessResponse
    {
        Validator::assertNotEmpty($phoneNumberId, 'phoneNumberId');
        Validator::assertNotEmpty($code, 'code');

        $payload = $this->transport->requestJson('POST', $phoneNumberId . '/verify_code', [
            'code' => $code,
        ]);

        return SuccessResponse::fromArray($payload);
    }

    public function setTwoStepVerificationCode(string $phoneNumberId, string $pin): SuccessResponse
    {
        Validator::assertNotEmpty($phoneNumberId, 'phoneNumberId');
        $this->assertPin($pin);

        $payload = $this->transport->requestJson('POST', $phoneNumberId, [
            'pin' => $pin,
        ]);

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

    private function assertPin(string $pin): void
    {
        Validator::assertNotEmpty($pin, 'pin');
        if (!preg_match('/^\d{6}$/', $pin)) {
            throw new \InvalidArgumentException('pin must be a 6-digit numeric string.');
        }
    }
}
