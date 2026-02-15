<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\DTO\Billing\CreditLinesResponse;
use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final class BillingEndpoint extends AbstractEndpoint
{
    /**
     * @param list<string>|null $fields
     */
    public function getCreditLines(string $businessId, ?array $fields = null): CreditLinesResponse
    {
        Validator::assertNotEmpty($businessId, 'businessId');

        $path = $businessId . '/extendedcredits' . $this->buildQuery($this->buildFieldsParams($fields));
        $payload = $this->transport->requestJson('GET', $path);

        return CreditLinesResponse::fromArray($payload);
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
