<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\DTO\Common\SuccessResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\Flow\FlowAssetsResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\Flow\FlowListResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\Flow\FlowMetricResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\Flow\FlowMigrationResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\Flow\FlowResponse;
use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final class FlowsEndpoint extends AbstractEndpoint
{
    /**
     * @param list<string> $categories
     */
    public function createFlow(
        string $wabaId,
        string $name,
        array $categories,
        ?string $cloneFlowId = null,
        ?string $endpointUri = null
    ): FlowResponse {
        Validator::assertNotEmpty($wabaId, 'wabaId');
        Validator::assertNotEmpty($name, 'name');
        if ($categories === []) {
            throw new \InvalidArgumentException('categories must not be empty.');
        }

        $fields = [
            'name' => $name,
            'categories' => json_encode($categories, JSON_THROW_ON_ERROR),
        ];

        if ($cloneFlowId !== null) {
            Validator::assertNotEmpty($cloneFlowId, 'cloneFlowId');
            $fields['clone_flow_id'] = $cloneFlowId;
        }

        if ($endpointUri !== null) {
            Validator::assertNotEmpty($endpointUri, 'endpointUri');
            $fields['endpoint_uri'] = $endpointUri;
        }

        $payload = $this->requestMultipartJson('POST', $wabaId . '/flows', $fields);

        return FlowResponse::fromArray($payload);
    }

    /**
     * @param list<string>|null $sourceFlowNames
     */
    public function migrateFlows(string $wabaId, string $sourceWabaId, ?array $sourceFlowNames = null): FlowMigrationResponse
    {
        Validator::assertNotEmpty($wabaId, 'wabaId');
        Validator::assertNotEmpty($sourceWabaId, 'sourceWabaId');

        $fields = ['source_waba_id' => $sourceWabaId];
        if ($sourceFlowNames !== null) {
            $fields['source_flow_names'] = json_encode($sourceFlowNames, JSON_THROW_ON_ERROR);
        }

        $payload = $this->requestMultipartJson('POST', $wabaId . '/migrate_flows', $fields);

        return FlowMigrationResponse::fromArray($payload);
    }

    /**
     * @param list<string>|null $fields
     */
    public function getFlow(string $flowId, ?array $fields = null): FlowResponse
    {
        Validator::assertNotEmpty($flowId, 'flowId');

        $payload = $this->transport->requestJson('GET', $flowId . $this->buildQuery($this->buildFieldsParams($fields)));

        return FlowResponse::fromArray($payload);
    }

    public function getPreviewUrl(string $flowId, bool $invalidate = false, ?string $dateFormat = null): FlowResponse
    {
        Validator::assertNotEmpty($flowId, 'flowId');

        $params = ['fields' => 'preview.invalidate(' . ($invalidate ? 'true' : 'false') . ')'];
        if ($dateFormat !== null) {
            $params['date_format'] = $dateFormat;
        }

        $payload = $this->transport->requestJson('GET', $flowId . $this->buildQuery($params));

        return FlowResponse::fromArray($payload);
    }

    public function listFlows(string $wabaId): FlowListResponse
    {
        Validator::assertNotEmpty($wabaId, 'wabaId');

        $payload = $this->transport->requestJson('GET', $wabaId . '/flows');

        return FlowListResponse::fromArray($payload);
    }

    /**
     * @param list<string> $categories
     */
    public function updateFlowMetadata(string $flowId, string $name, array $categories, ?string $endpointUri = null): SuccessResponse
    {
        Validator::assertNotEmpty($flowId, 'flowId');
        Validator::assertNotEmpty($name, 'name');
        if ($categories === []) {
            throw new \InvalidArgumentException('categories must not be empty.');
        }

        $fields = [
            'name' => $name,
            'categories' => json_encode($categories, JSON_THROW_ON_ERROR),
        ];

        if ($endpointUri !== null) {
            Validator::assertNotEmpty($endpointUri, 'endpointUri');
            $fields['endpoint_uri'] = $endpointUri;
        }

        $payload = $this->requestMultipartJson('POST', $flowId, $fields);

        return SuccessResponse::fromArray($payload);
    }

    public function uploadFlowJson(string $flowId, string $filePath, string $assetName = 'flow.json'): FlowResponse
    {
        Validator::assertNotEmpty($flowId, 'flowId');
        Validator::assertNotEmpty($assetName, 'assetName');

        if (!is_file($filePath)) {
            throw new \InvalidArgumentException('File does not exist: ' . $filePath);
        }

        $contents = file_get_contents($filePath);
        if ($contents === false) {
            throw new \RuntimeException('Failed to read file: ' . $filePath);
        }

        $payload = $this->requestMultipartJson(
            'POST',
            $flowId . '/assets',
            [
                'name' => $assetName,
                'asset_type' => 'FLOW_JSON',
            ],
            [
                'file' => [
                    'filename' => $assetName,
                    'contents' => $contents,
                    'mime_type' => 'application/json',
                ],
            ]
        );

        return FlowResponse::fromArray($payload);
    }

    public function listAssets(string $flowId): FlowAssetsResponse
    {
        Validator::assertNotEmpty($flowId, 'flowId');

        $payload = $this->transport->requestJson('GET', $flowId . '/assets');

        return FlowAssetsResponse::fromArray($payload);
    }

    public function publishFlow(string $flowId): SuccessResponse
    {
        Validator::assertNotEmpty($flowId, 'flowId');

        $payload = $this->transport->requestJson('POST', $flowId . '/publish');

        return SuccessResponse::fromArray($payload);
    }

    public function deprecateFlow(string $flowId): SuccessResponse
    {
        Validator::assertNotEmpty($flowId, 'flowId');

        $payload = $this->transport->requestJson('POST', $flowId . '/deprecate');

        return SuccessResponse::fromArray($payload);
    }

    public function deleteFlow(string $flowId): SuccessResponse
    {
        Validator::assertNotEmpty($flowId, 'flowId');

        $payload = $this->transport->requestJson('DELETE', $flowId);

        return SuccessResponse::fromArray($payload);
    }

    public function setEncryptionPublicKey(string $publicKey): SuccessResponse
    {
        Validator::assertNotEmpty($publicKey, 'publicKey');

        $payload = $this->requestMultipartJson('POST', $this->phoneNumberId . '/whatsapp_business_encryption', [
            'business_public_key' => $publicKey,
        ]);

        return SuccessResponse::fromArray($payload);
    }

    public function getEncryptionPublicKey(): FlowResponse
    {
        $payload = $this->transport->requestJson('GET', $this->phoneNumberId . '/whatsapp_business_encryption');

        return new FlowResponse($payload);
    }

    public function getEndpointMetrics(string $flowId, string $fieldsExpression): FlowMetricResponse
    {
        Validator::assertNotEmpty($flowId, 'flowId');
        Validator::assertNotEmpty($fieldsExpression, 'fieldsExpression');

        $payload = $this->transport->requestJson('GET', $flowId . $this->buildQuery(['fields' => $fieldsExpression]));

        return FlowMetricResponse::fromArray($payload);
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

    /**
     * @param array<string, string> $fields
     * @param array<string, array{filename: string, contents: string, mime_type: string}> $files
     * @return array<string, mixed>
     */
    private function requestMultipartJson(string $method, string $path, array $fields, array $files = []): array
    {
        $boundary = '----WhatsAppCloudSDK' . bin2hex(random_bytes(8));
        $body = $this->buildMultipartBody($boundary, $fields, $files);

        $response = $this->transport->requestRaw($method, $path, $body, [
            'Content-Type' => 'multipart/form-data; boundary=' . $boundary,
            'Accept' => 'application/json',
        ]);

        $payload = json_decode((string) $response->getBody(), true);
        if (!is_array($payload)) {
            throw new \RuntimeException('Response was not valid JSON.');
        }

        return $payload;
    }

    /**
     * @param array<string, string> $fields
     * @param array<string, array{filename: string, contents: string, mime_type: string}> $files
     */
    private function buildMultipartBody(string $boundary, array $fields, array $files): string
    {
        $eol = "\r\n";
        $body = '';

        foreach ($fields as $name => $value) {
            $body .= '--' . $boundary . $eol;
            $body .= 'Content-Disposition: form-data; name="' . $name . '"' . $eol . $eol;
            $body .= $value . $eol;
        }

        foreach ($files as $name => $file) {
            $body .= '--' . $boundary . $eol;
            $body .= 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $file['filename'] . '"' . $eol;
            $body .= 'Content-Type: ' . $file['mime_type'] . $eol . $eol;
            $body .= $file['contents'] . $eol;
        }

        $body .= '--' . $boundary . '--' . $eol;

        return $body;
    }
}
