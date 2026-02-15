<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\DTO\Template\TemplateResponse;
use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final class TemplatesEndpoint extends AbstractEndpoint
{
    public function getById(string $templateId): TemplateResponse
    {
        Validator::assertNotEmpty($templateId, 'templateId');
        $payload = $this->transport->requestJson('GET', $templateId);

        return new TemplateResponse($payload);
    }

    public function getByName(string $wabaId, string $name): TemplateResponse
    {
        Validator::assertNotEmpty($wabaId, 'wabaId');
        Validator::assertNotEmpty($name, 'name');

        $path = $wabaId . '/message_templates?name=' . rawurlencode($name);
        $payload = $this->transport->requestJson('GET', $path);

        return new TemplateResponse($payload);
    }

    public function listAll(string $wabaId): TemplateResponse
    {
        Validator::assertNotEmpty($wabaId, 'wabaId');
        $payload = $this->transport->requestJson('GET', $wabaId . '/message_templates');

        return new TemplateResponse($payload);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function create(string $wabaId, array $payload): TemplateResponse
    {
        Validator::assertNotEmpty($wabaId, 'wabaId');
        if ($payload === []) {
            throw new \InvalidArgumentException('payload must not be empty.');
        }

        $response = $this->transport->requestJson('POST', $wabaId . '/message_templates', $payload);

        return new TemplateResponse($response);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function update(string $templateId, array $payload): TemplateResponse
    {
        Validator::assertNotEmpty($templateId, 'templateId');
        if ($payload === []) {
            throw new \InvalidArgumentException('payload must not be empty.');
        }

        $response = $this->transport->requestJson('POST', $templateId, $payload);

        return new TemplateResponse($response);
    }

    public function deleteByName(string $wabaId, string $name): bool
    {
        Validator::assertNotEmpty($wabaId, 'wabaId');
        Validator::assertNotEmpty($name, 'name');

        $path = $wabaId . '/message_templates?name=' . rawurlencode($name);
        $payload = $this->transport->requestJson('DELETE', $path);

        return isset($payload['success']) && $payload['success'] === true;
    }

    public function deleteById(string $wabaId, string $hsmId, string $name): bool
    {
        Validator::assertNotEmpty($wabaId, 'wabaId');
        Validator::assertNotEmpty($hsmId, 'hsmId');
        Validator::assertNotEmpty($name, 'name');

        $path = $wabaId . '/message_templates?hsm_id=' . rawurlencode($hsmId) . '&name=' . rawurlencode($name);
        $payload = $this->transport->requestJson('DELETE', $path);

        return isset($payload['success']) && $payload['success'] === true;
    }
}
