```markdown
# Business Portfolio

Retrieve information about the business portfolio.

## Get Business Portfolio

```php
<?php

declare(strict_types=1);

$portfolio = $client->businessPortfolio()->getBusinessPortfolio('<business-id>');
echo $portfolio->payload['name'];
```

## Get Business Portfolio With Fields

```php
<?php

declare(strict_types=1);

$portfolio = $client->businessPortfolio()->getBusinessPortfolio(
    businessId: '<business-id>',
    fields: ['id', 'name', 'owned_whatsapp_business_accounts', 'client_whatsapp_business_accounts']
);
```

```
