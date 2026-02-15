<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\DTO\BusinessProfile\BusinessProfileResponse;

final class BusinessProfilesEndpoint extends AbstractEndpoint
{
    /**
     * @param list<string>|null $fields
     */
    public function getProfile(?array $fields = null): BusinessProfileResponse
    {
        $path = $this->phoneNumberId . '/whatsapp_business_profile' . $this->buildQuery($this->buildFieldsParams($fields));
        $payload = $this->transport->requestJson('GET', $path);

        return new BusinessProfileResponse($payload);
    }

    /**
     * @param array<string, mixed> $profile
     */
    public function updateProfile(array $profile): BusinessProfileResponse
    {
        if ($profile === []) {
            throw new \InvalidArgumentException('profile must not be empty.');
        }

        if (isset($profile['messaging_product']) && $profile['messaging_product'] !== 'whatsapp') {
            throw new \InvalidArgumentException('messaging_product must be whatsapp.');
        }

        $profile['messaging_product'] = 'whatsapp';

        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/whatsapp_business_profile', $profile);

        return new BusinessProfileResponse($payload);
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
