<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\DTO\Commerce\CommerceSettingsResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\Common\SuccessResponse;

final class CommerceSettingsEndpoint extends AbstractEndpoint
{
    public function getSettings(): CommerceSettingsResponse
    {
        $payload = $this->transport->requestJson('GET', $this->phoneNumberId . '/whatsapp_commerce_settings');

        return CommerceSettingsResponse::fromArray($payload);
    }

    public function updateSettings(bool $isCartEnabled, bool $isCatalogVisible): SuccessResponse
    {
        $query = $this->buildQuery([
            'is_cart_enabled' => $isCartEnabled ? 'true' : 'false',
            'is_catalog_visible' => $isCatalogVisible ? 'true' : 'false',
        ]);

        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/whatsapp_commerce_settings' . $query);

        return SuccessResponse::fromArray($payload);
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
