# Messages Usage

## Send Text

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$response = $client->messages()->sendText(
	to: '+15551234567',
	text: 'Hello from WhatsApp Cloud API',
	previewUrl: false
);
```

## Send Template

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;
use WhatsAppCloud\DTO\Template\TemplateComponent;
use WhatsAppCloud\DTO\Template\TemplateParameter;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$components = [
	new TemplateComponent('body', [
		new TemplateParameter('text', ['text' => 'Jane']),
	]),
];

$response = $client->messages()->sendTemplate(
	to: '+15551234567',
	templateName: 'hello_world',
	language: 'en_US',
	components: $components
);
```

## Send Media

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;
use WhatsAppCloud\DTO\Message\MediaType;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$response = $client->messages()->sendMedia(
	to: '+15551234567',
	mediaType: MediaType::Image,
	link: 'https://example.com/image.jpg',
	caption: 'Sample image'
);
```

## Send Audio

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$response = $client->messages()->sendAudio(
	to: '+15551234567',
	link: 'https://example.com/audio.ogg',
	voice: true
);
```

## Send Sticker

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$response = $client->messages()->sendSticker(
	to: '+15551234567',
	link: 'https://example.com/sticker.webp'
);
```

## Send Document

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$response = $client->messages()->sendDocument(
	to: '+15551234567',
	link: 'https://example.com/guide.pdf',
	caption: 'User guide',
	filename: 'guide.pdf'
);
```

## Send Contacts

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;
use WhatsAppCloud\DTO\Message\Contact\Contact;
use WhatsAppCloud\DTO\Message\Contact\ContactName;
use WhatsAppCloud\DTO\Message\Contact\ContactPhone;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$contacts = [
	new Contact(
		name: new ContactName('Jane Doe', 'Jane', 'Doe'),
		phones: [new ContactPhone('+15551234567')]
	),
];

$response = $client->messages()->sendContacts('+15551234567', $contacts);
```

## Send Location

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$response = $client->messages()->sendLocation(
	to: '+15551234567',
	latitude: -23.5505,
	longitude: -46.6333,
	name: 'Sao Paulo',
	address: 'SP, Brazil'
);
```

## Send Reaction

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$response = $client->messages()->sendReaction(
	to: '+15551234567',
	messageId: 'wamid.HBgL...',
	emoji: ':thumbs_up:'
);
```

## Send Interactive Reply Buttons

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;
use WhatsAppCloud\DTO\Message\Interactive\ReplyButton;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$buttons = [
	new ReplyButton('btn_1', 'Yes'),
	new ReplyButton('btn_2', 'No'),
];

$response = $client->messages()->sendReplyButtons(
	to: '+15551234567',
	bodyText: 'Are you interested?',
	buttons: $buttons
);
```

## Send Interactive List

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;
use WhatsAppCloud\DTO\Message\Interactive\ListRow;
use WhatsAppCloud\DTO\Message\Interactive\ListSection;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$sections = [
	new ListSection('Options', [
		new ListRow('opt_1', 'Option 1', 'First option'),
		new ListRow('opt_2', 'Option 2', 'Second option'),
	]),
];

$response = $client->messages()->sendList(
	to: '+15551234567',
	buttonText: 'Choose',
	bodyText: 'Pick one option:',
	sections: $sections,
	headerText: 'Menu',
	footerText: 'Thanks'
);
```

## Send Product Message

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$response = $client->messages()->sendProduct(
	to: '+15551234567',
	catalogId: '<catalog-id>',
	productRetailerId: '<product-retailer-id>',
	bodyText: 'Check this product'
);
```

## Send Product List Message

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;
use WhatsAppCloud\DTO\Message\Interactive\ProductItem;
use WhatsAppCloud\DTO\Message\Interactive\ProductSection;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$sections = [
	new ProductSection('Featured', [
		new ProductItem('sku_1'),
		new ProductItem('sku_2'),
	]),
];

$response = $client->messages()->sendProductList(
	to: '+15551234567',
	catalogId: '<catalog-id>',
	sections: $sections,
	bodyText: 'Browse our catalog'
);
```

## Send Catalog Message

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$response = $client->messages()->sendCatalog(
	to: '+15551234567',
	bodyText: 'See our catalog',
	thumbnailProductRetailerId: '<product-retailer-id>',
	footerText: 'Thanks'
);
```

## Send Typing Indicator + Read Receipt

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$client->messages()->sendTypingIndicatorReadReceipt('wamid.HBgL...');
```

## Mark As Read

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$client->messages()->markAsRead('wamid.HBgL...');
```
