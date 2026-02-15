<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\DTO\Analytics\AnalyticsResponse;
use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final class AnalyticsEndpoint extends AbstractEndpoint
{
    public function getAnalytics(string $wabaId, string $fieldsExpression): AnalyticsResponse
    {
        Validator::assertNotEmpty($wabaId, 'wabaId');
        Validator::assertNotEmpty($fieldsExpression, 'fieldsExpression');

        $payload = $this->transport->requestJson('GET', $wabaId . $this->buildQuery(['fields' => $fieldsExpression]));

        return new AnalyticsResponse($payload);
    }

    public function getConversationAnalytics(string $wabaId, string $fieldsExpression): AnalyticsResponse
    {
        Validator::assertNotEmpty($wabaId, 'wabaId');
        Validator::assertNotEmpty($fieldsExpression, 'fieldsExpression');

        $payload = $this->transport->requestJson('GET', $wabaId . $this->buildQuery(['fields' => $fieldsExpression]));

        return new AnalyticsResponse($payload);
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
