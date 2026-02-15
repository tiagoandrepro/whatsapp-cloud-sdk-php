<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\DTO\Common\SuccessResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\WebhookSubscription\WebhookSubscriptionsResponse;
use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final class WebhookSubscriptionsEndpoint extends AbstractEndpoint
{
    public function subscribe(string $wabaId): SuccessResponse
    {
        Validator::assertNotEmpty($wabaId, 'wabaId');

        $payload = $this->transport->requestJson('POST', $wabaId . '/subscribed_apps');

        return SuccessResponse::fromArray($payload);
    }

    public function listSubscriptions(string $wabaId): WebhookSubscriptionsResponse
    {
        Validator::assertNotEmpty($wabaId, 'wabaId');

        $payload = $this->transport->requestJson('GET', $wabaId . '/subscribed_apps');

        return new WebhookSubscriptionsResponse($payload);
    }

    public function unsubscribe(string $wabaId): SuccessResponse
    {
        Validator::assertNotEmpty($wabaId, 'wabaId');

        $payload = $this->transport->requestJson('DELETE', $wabaId . '/subscribed_apps');

        return SuccessResponse::fromArray($payload);
    }

    public function overrideCallback(string $wabaId, string $callbackUri, string $verifyToken): WebhookSubscriptionsResponse
    {
        Validator::assertNotEmpty($wabaId, 'wabaId');
        Validator::assertNotEmpty($callbackUri, 'callbackUri');
        Validator::assertNotEmpty($verifyToken, 'verifyToken');

        $payload = $this->transport->requestJson('POST', $wabaId . '/subscribed_apps', [
            'override_callback_uri' => $callbackUri,
            'verify_token' => $verifyToken,
        ]);

        return new WebhookSubscriptionsResponse($payload);
    }
}
