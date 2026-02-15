<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\DTO\Message\AudioMessageRequest;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\Contact\Contact;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\ContactMessageRequest;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\DocumentMessageRequest;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive\CatalogMessageRequest;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive\ListMessageRequest;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive\ListSection;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive\ProductListMessageRequest;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive\ProductMessageRequest;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive\ProductSection;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive\ReplyButton;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive\ReplyButtonsMessageRequest;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\LocationMessageRequest;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\MarkAsReadRequest;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\MediaMessageRequest;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\MediaType;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\MessageResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\ReactionMessageRequest;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\StickerMessageRequest;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\TemplateMessageRequest;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\TextMessageRequest;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\TypingIndicatorRequest;
use Tiagoandrepro\WhatsAppCloud\DTO\Template\TemplateComponent;

final class MessagesEndpoint extends AbstractEndpoint
{
    public function sendText(
        string $to,
        string $text,
        bool $previewUrl = false,
        ?string $contextMessageId = null
    ): MessageResponse {
        $request = new TextMessageRequest($to, $text, $previewUrl, $contextMessageId);
        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/messages', $request->toArray());

        return MessageResponse::fromArray($payload);
    }

    /**
     * @param list<TemplateComponent> $components
     */
    public function sendTemplate(
        string $to,
        string $templateName,
        string $language,
        array $components = [],
        ?string $contextMessageId = null
    ): MessageResponse {
        $request = new TemplateMessageRequest($to, $templateName, $language, $components, $contextMessageId);
        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/messages', $request->toArray());

        return MessageResponse::fromArray($payload);
    }

    public function sendMedia(
        string $to,
        MediaType $mediaType,
        ?string $mediaId = null,
        ?string $link = null,
        ?string $caption = null,
        ?string $contextMessageId = null
    ): MessageResponse {
        $request = new MediaMessageRequest($to, $mediaType, $mediaId, $link, $caption, $contextMessageId);
        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/messages', $request->toArray());

        return MessageResponse::fromArray($payload);
    }

    public function sendAudio(
        string $to,
        ?string $mediaId = null,
        ?string $link = null,
        ?bool $voice = null,
        ?string $contextMessageId = null
    ): MessageResponse {
        $request = new AudioMessageRequest($to, $mediaId, $link, $voice, $contextMessageId);
        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/messages', $request->toArray());

        return MessageResponse::fromArray($payload);
    }

    public function sendSticker(
        string $to,
        ?string $mediaId = null,
        ?string $link = null,
        ?string $contextMessageId = null
    ): MessageResponse {
        $request = new StickerMessageRequest($to, $mediaId, $link, $contextMessageId);
        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/messages', $request->toArray());

        return MessageResponse::fromArray($payload);
    }

    public function sendDocument(
        string $to,
        ?string $mediaId = null,
        ?string $link = null,
        ?string $caption = null,
        ?string $filename = null,
        ?string $contextMessageId = null
    ): MessageResponse {
        $request = new DocumentMessageRequest($to, $mediaId, $link, $caption, $filename, $contextMessageId);
        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/messages', $request->toArray());

        return MessageResponse::fromArray($payload);
    }

    /**
     * @param list<Contact> $contacts
     */
    public function sendContacts(string $to, array $contacts, ?string $contextMessageId = null): MessageResponse
    {
        $request = new ContactMessageRequest($to, $contacts, $contextMessageId);
        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/messages', $request->toArray());

        return MessageResponse::fromArray($payload);
    }

    public function sendLocation(
        string $to,
        float $latitude,
        float $longitude,
        ?string $name = null,
        ?string $address = null,
        ?string $contextMessageId = null
    ): MessageResponse {
        $request = new LocationMessageRequest($to, $latitude, $longitude, $name, $address, $contextMessageId);
        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/messages', $request->toArray());

        return MessageResponse::fromArray($payload);
    }

    public function sendReaction(string $to, string $messageId, string $emoji): MessageResponse
    {
        $request = new ReactionMessageRequest($to, $messageId, $emoji);
        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/messages', $request->toArray());

        return MessageResponse::fromArray($payload);
    }

    /**
     * @param list<ReplyButton> $buttons
     */
    public function sendReplyButtons(
        string $to,
        string $bodyText,
        array $buttons,
        ?string $contextMessageId = null
    ): MessageResponse {
        $request = new ReplyButtonsMessageRequest($to, $bodyText, $buttons, $contextMessageId);
        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/messages', $request->toArray());

        return MessageResponse::fromArray($payload);
    }

    /**
     * @param list<ListSection> $sections
     */
    public function sendList(
        string $to,
        string $buttonText,
        string $bodyText,
        array $sections,
        ?string $headerText = null,
        ?string $footerText = null,
        ?string $contextMessageId = null
    ): MessageResponse {
        $request = new ListMessageRequest($to, $buttonText, $bodyText, $sections, $headerText, $footerText, $contextMessageId);
        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/messages', $request->toArray());

        return MessageResponse::fromArray($payload);
    }

    public function sendProduct(
        string $to,
        string $catalogId,
        string $productRetailerId,
        ?string $bodyText = null,
        ?string $footerText = null,
        ?string $contextMessageId = null
    ): MessageResponse {
        $request = new ProductMessageRequest(
            $to,
            $catalogId,
            $productRetailerId,
            $bodyText,
            $footerText,
            $contextMessageId
        );
        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/messages', $request->toArray());

        return MessageResponse::fromArray($payload);
    }

    /**
     * @param list<ProductSection> $sections
     */
    public function sendProductList(
        string $to,
        string $catalogId,
        array $sections,
        string $bodyText,
        ?string $headerText = null,
        ?string $footerText = null,
        ?string $contextMessageId = null
    ): MessageResponse {
        $request = new ProductListMessageRequest(
            $to,
            $catalogId,
            $sections,
            $bodyText,
            $headerText,
            $footerText,
            $contextMessageId
        );
        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/messages', $request->toArray());

        return MessageResponse::fromArray($payload);
    }

    public function sendCatalog(
        string $to,
        string $bodyText,
        string $thumbnailProductRetailerId,
        ?string $footerText = null,
        ?string $contextMessageId = null
    ): MessageResponse {
        $request = new CatalogMessageRequest($to, $bodyText, $thumbnailProductRetailerId, $footerText, $contextMessageId);
        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/messages', $request->toArray());

        return MessageResponse::fromArray($payload);
    }

    public function sendTypingIndicatorReadReceipt(string $messageId, string $indicatorType = 'text'): void
    {
        $request = new TypingIndicatorRequest($messageId, $indicatorType);
        $this->transport->requestJson('POST', $this->phoneNumberId . '/messages', $request->toArray());
    }

    public function markAsRead(string $messageId): void
    {
        $request = new MarkAsReadRequest($messageId);
        $this->transport->requestJson('PUT', $this->phoneNumberId . '/messages', $request->toArray());
    }
}
