<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Client;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;
use Tiagoandrepro\WhatsAppCloud\Auth\StaticTokenProvider;
use Tiagoandrepro\WhatsAppCloud\Endpoint\AnalyticsEndpoint;
use Tiagoandrepro\WhatsAppCloud\Endpoint\BillingEndpoint;
use Tiagoandrepro\WhatsAppCloud\Endpoint\BlockUsersEndpoint;
use Tiagoandrepro\WhatsAppCloud\Endpoint\BusinessPortfolioEndpoint;
use Tiagoandrepro\WhatsAppCloud\Endpoint\BusinessProfilesEndpoint;
use Tiagoandrepro\WhatsAppCloud\Endpoint\CommerceSettingsEndpoint;
use Tiagoandrepro\WhatsAppCloud\Endpoint\FlowsEndpoint;
use Tiagoandrepro\WhatsAppCloud\Endpoint\MediaEndpoint;
use Tiagoandrepro\WhatsAppCloud\Endpoint\MessagesEndpoint;
use Tiagoandrepro\WhatsAppCloud\Endpoint\PhoneNumbersEndpoint;
use Tiagoandrepro\WhatsAppCloud\Endpoint\QrCodesEndpoint;
use Tiagoandrepro\WhatsAppCloud\Endpoint\RegistrationEndpoint;
use Tiagoandrepro\WhatsAppCloud\Endpoint\ResumableUploadEndpoint;
use Tiagoandrepro\WhatsAppCloud\Endpoint\TemplatesEndpoint;
use Tiagoandrepro\WhatsAppCloud\Endpoint\WabaEndpoint;
use Tiagoandrepro\WhatsAppCloud\Endpoint\Webhooks;
use Tiagoandrepro\WhatsAppCloud\Endpoint\WebhookSubscriptionsEndpoint;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;
use Tiagoandrepro\WhatsAppCloud\Transport\Psr18Transport;
use Tiagoandrepro\WhatsAppCloud\Util\ErrorMapper;
use Tiagoandrepro\WhatsAppCloud\Util\RetryPolicy;
use Tiagoandrepro\WhatsAppCloud\Util\SafeLogger;

final class WhatsAppClient
{
    private ClientConfig $config;
    private Psr18Transport $transport;

    private ?MessagesEndpoint $messagesEndpoint = null;
    private ?MediaEndpoint $mediaEndpoint = null;
    private ?TemplatesEndpoint $templatesEndpoint = null;
    private ?Webhooks $webhooks = null;
    private ?RegistrationEndpoint $registrationEndpoint = null;
    private ?PhoneNumbersEndpoint $phoneNumbersEndpoint = null;
    private ?WebhookSubscriptionsEndpoint $webhookSubscriptionsEndpoint = null;
    private ?BusinessProfilesEndpoint $businessProfilesEndpoint = null;
    private ?ResumableUploadEndpoint $resumableUploadEndpoint = null;
    private ?WabaEndpoint $wabaEndpoint = null;
    private ?CommerceSettingsEndpoint $commerceSettingsEndpoint = null;
    private ?QrCodesEndpoint $qrCodesEndpoint = null;
    private ?AnalyticsEndpoint $analyticsEndpoint = null;
    private ?FlowsEndpoint $flowsEndpoint = null;
    private ?BillingEndpoint $billingEndpoint = null;
    private ?BlockUsersEndpoint $blockUsersEndpoint = null;
    private ?BusinessPortfolioEndpoint $businessPortfolioEndpoint = null;

    public function __construct(
        ClientInterface $client,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        ClientConfig $config
    ) {
        $this->config = $config;

        $serializer = new JsonSerializer();
        $errorMapper = new ErrorMapper();
        $logger = new SafeLogger($config->getLogger());

        $this->transport = new Psr18Transport(
            $client,
            $requestFactory,
            $streamFactory,
            $config->getTokenProvider(),
            $serializer,
            $errorMapper,
            $logger,
            $config->getRetryPolicy(),
            $config->getBaseUrl(),
            $config->getGraphApiVersion()
        );
    }

    /**
     * @param list<string>|null $allowedHosts
     */
    public static function fromDefaults(
        string $token,
        string $phoneNumberId,
        string $graphApiVersion = 'v24.0',
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null,
        ?LoggerInterface $logger = null,
        ?RetryPolicy $retryPolicy = null,
        ?string $baseUrl = null,
        ?array $allowedHosts = null,
        float $connectTimeoutSeconds = 5.0,
        float $totalTimeoutSeconds = 30.0
    ): self {
        $tokenProvider = new StaticTokenProvider($token);
        $retryPolicy = $retryPolicy ?? new RetryPolicy();
        $baseUrl = $baseUrl ?? 'https://graph.facebook.com';
        $allowedHosts = $allowedHosts ?? ['graph.facebook.com'];

        $httpClient = $httpClient ?? self::discoverPsr18Client();
        $requestFactory = $requestFactory ?? self::discoverRequestFactory();
        $streamFactory = $streamFactory ?? self::discoverStreamFactory();

        $config = new ClientConfig(
            $baseUrl,
            $graphApiVersion,
            $phoneNumberId,
            $tokenProvider,
            $retryPolicy,
            $logger,
            $allowedHosts,
            $connectTimeoutSeconds,
            $totalTimeoutSeconds
        );

        return new self($httpClient, $requestFactory, $streamFactory, $config);
    }

    public function messages(): MessagesEndpoint
    {
        return $this->messagesEndpoint ??= new MessagesEndpoint($this->transport, $this->config->getPhoneNumberId());
    }

    public function media(): MediaEndpoint
    {
        return $this->mediaEndpoint ??= new MediaEndpoint($this->transport, $this->config->getPhoneNumberId());
    }

    public function templates(): TemplatesEndpoint
    {
        return $this->templatesEndpoint ??= new TemplatesEndpoint($this->transport, $this->config->getPhoneNumberId());
    }

    public function webhooks(): Webhooks
    {
        return $this->webhooks ??= new Webhooks();
    }

    public function registration(): RegistrationEndpoint
    {
        return $this->registrationEndpoint ??= new RegistrationEndpoint($this->transport, $this->config->getPhoneNumberId());
    }

    public function phoneNumbers(): PhoneNumbersEndpoint
    {
        return $this->phoneNumbersEndpoint ??= new PhoneNumbersEndpoint($this->transport, $this->config->getPhoneNumberId());
    }

    public function webhookSubscriptions(): WebhookSubscriptionsEndpoint
    {
        return $this->webhookSubscriptionsEndpoint ??= new WebhookSubscriptionsEndpoint($this->transport, $this->config->getPhoneNumberId());
    }

    public function businessProfiles(): BusinessProfilesEndpoint
    {
        return $this->businessProfilesEndpoint ??= new BusinessProfilesEndpoint($this->transport, $this->config->getPhoneNumberId());
    }

    public function resumableUploads(): ResumableUploadEndpoint
    {
        return $this->resumableUploadEndpoint ??= new ResumableUploadEndpoint($this->transport, $this->config->getPhoneNumberId());
    }

    public function wabas(): WabaEndpoint
    {
        return $this->wabaEndpoint ??= new WabaEndpoint($this->transport, $this->config->getPhoneNumberId());
    }

    public function commerceSettings(): CommerceSettingsEndpoint
    {
        return $this->commerceSettingsEndpoint ??= new CommerceSettingsEndpoint($this->transport, $this->config->getPhoneNumberId());
    }

    public function qrCodes(): QrCodesEndpoint
    {
        return $this->qrCodesEndpoint ??= new QrCodesEndpoint($this->transport, $this->config->getPhoneNumberId());
    }

    public function analytics(): AnalyticsEndpoint
    {
        return $this->analyticsEndpoint ??= new AnalyticsEndpoint($this->transport, $this->config->getPhoneNumberId());
    }

    public function flows(): FlowsEndpoint
    {
        return $this->flowsEndpoint ??= new FlowsEndpoint($this->transport, $this->config->getPhoneNumberId());
    }

    public function billing(): BillingEndpoint
    {
        return $this->billingEndpoint ??= new BillingEndpoint($this->transport, $this->config->getPhoneNumberId());
    }

    public function blockUsers(): BlockUsersEndpoint
    {
        return $this->blockUsersEndpoint ??= new BlockUsersEndpoint($this->transport, $this->config->getPhoneNumberId());
    }

    public function businessPortfolio(): BusinessPortfolioEndpoint
    {
        return $this->businessPortfolioEndpoint ??= new BusinessPortfolioEndpoint($this->transport, $this->config->getPhoneNumberId());
    }

    private static function discoverPsr18Client(): ClientInterface
    {
        if (!class_exists(Psr18ClientDiscovery::class)) {
            throw new \RuntimeException('PSR-18 discovery not available. Provide a client explicitly.');
        }

        return Psr18ClientDiscovery::find();
    }

    private static function discoverRequestFactory(): RequestFactoryInterface
    {
        if (!class_exists(Psr17FactoryDiscovery::class)) {
            throw new \RuntimeException('PSR-17 discovery not available. Provide a request factory explicitly.');
        }

        return Psr17FactoryDiscovery::findRequestFactory();
    }

    private static function discoverStreamFactory(): StreamFactoryInterface
    {
        if (!class_exists(Psr17FactoryDiscovery::class)) {
            throw new \RuntimeException('PSR-17 discovery not available. Provide a stream factory explicitly.');
        }

        return Psr17FactoryDiscovery::findStreamFactory();
    }
}
