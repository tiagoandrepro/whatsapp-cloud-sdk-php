# Analytics

Retrieve analytics for a WABA using the Graph API analytics fields syntax.

## Get Analytics

```php
<?php

declare(strict_types=1);

$fields = 'analytics.start(1680503760).end(1680564980).granularity(DAY).phone_numbers([]).country_codes(["US","BR"])';
$result = $client->analytics()->getAnalytics('<waba-id>', $fields);
print_r($result->payload);
```

## Get Conversation Analytics

```php
<?php

declare(strict_types=1);

$fields = 'conversation_analytics.start(1656661480).end(1674859480).granularity(MONTHLY).conversation_directions(["business_initiated"]).dimensions(["conversation_type","conversation_direction"])';
$result = $client->analytics()->getConversationAnalytics('<waba-id>', $fields);
print_r($result->payload);
```
