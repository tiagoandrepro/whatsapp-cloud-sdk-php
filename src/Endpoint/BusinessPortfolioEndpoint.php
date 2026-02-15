<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\DTO\BusinessPortfolio\BusinessPortfolioResponse;
use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final class BusinessPortfolioEndpoint extends AbstractEndpoint
{
    /**
     * @param list<string>|null $fields
     */
    public function getBusinessPortfolio(string $businessId, ?array $fields = null): BusinessPortfolioResponse
    {
        Validator::assertNotEmpty($businessId, 'businessId');

        $path = $businessId . $this->buildQuery($this->buildFieldsParams($fields));
        $payload = $this->transport->requestJson('GET', $path);

        return BusinessPortfolioResponse::fromArray($payload);
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
