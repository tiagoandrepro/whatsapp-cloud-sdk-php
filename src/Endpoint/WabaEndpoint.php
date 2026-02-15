<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\DTO\Waba\WabaResponse;
use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final class WabaEndpoint extends AbstractEndpoint
{
    /**
     * @param list<string>|null $fields
     */
    public function getWaba(string $wabaId, ?array $fields = null): WabaResponse
    {
        Validator::assertNotEmpty($wabaId, 'wabaId');

        $path = $wabaId . $this->buildQuery($this->buildFieldsParams($fields));
        $payload = $this->transport->requestJson('GET', $path);

        return new WabaResponse($payload);
    }

    /**
     * @param list<string>|null $fields
     */
    public function getOwnedWabas(string $businessId, ?array $fields = null): WabaResponse
    {
        Validator::assertNotEmpty($businessId, 'businessId');

        $path = $businessId . '/owned_whatsapp_business_accounts' . $this->buildQuery($this->buildFieldsParams($fields));
        $payload = $this->transport->requestJson('GET', $path);

        return new WabaResponse($payload);
    }

    /**
     * @param list<string>|null $fields
     */
    public function getSharedWabas(string $businessId, ?array $fields = null): WabaResponse
    {
        Validator::assertNotEmpty($businessId, 'businessId');

        $path = $businessId . '/client_whatsapp_business_accounts' . $this->buildQuery($this->buildFieldsParams($fields));
        $payload = $this->transport->requestJson('GET', $path);

        return new WabaResponse($payload);
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
