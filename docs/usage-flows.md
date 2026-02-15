```markdown
# Flows

Create, manage, publish, and analyze WhatsApp Flows for the configured WABA.

## Create Flow

```php
<?php

declare(strict_types=1);

$flow = $client->flows()->createFlow(
    wabaId: '<waba-id>',
    name: 'Order Flow',
    categories: ['BOOKING', 'CUSTOMER_SERVICE']
);
echo $flow->payload['id'];
```

## Create Flow from Clone

```php
<?php

declare(strict_types=1);

$flow = $client->flows()->createFlow(
    wabaId: '<waba-id>',
    name: 'Cloned Flow',
    categories: ['BOOKING'],
    cloneFlowId: '<source-flow-id>'
);
```

## Migrate Flows

```php
<?php

declare(strict_types=1);

$result = $client->flows()->migrateFlows(
    wabaId: '<target-waba-id>',
    sourceWabaId: '<source-waba-id>',
    sourceFlowNames: ['Order Flow', 'Support Flow']
);
echo $result->payload['flows_migrated'];
```

## Get Flow

```php
<?php

declare(strict_types=1);

$flow = $client->flows()->getFlow('<flow-id>');
echo $flow->payload['id'];
```

## List Flows

```php
<?php

declare(strict_types=1);

$flows = $client->flows()->listFlows('<waba-id>');
foreach ($flows->payload['data'] as $flow) {
    echo $flow['id'] . PHP_EOL;
}
```

## Update Flow Metadata

```php
<?php

declare(strict_types=1);

$result = $client->flows()->updateFlowMetadata(
    flowId: '<flow-id>',
    name: 'Updated Order Flow',
    categories: ['BOOKING', 'SALES']
);
```

## Upload Flow JSON

```php
<?php

declare(strict_types=1);

$result = $client->flows()->uploadFlowJson(
    flowId: '<flow-id>',
    filePath: '/path/to/flow.json',
    assetName: 'flow.json'
);
```

## List Assets

```php
<?php

declare(strict_types=1);

$assets = $client->flows()->listAssets('<flow-id>');
foreach ($assets->payload['assets'] as $asset) {
    echo $asset['name'] . PHP_EOL;
}
```

## Publish Flow

```php
<?php

declare(strict_types=1);

$result = $client->flows()->publishFlow('<flow-id>');
```

## Deprecate Flow

```php
<?php

declare(strict_types=1);

$result = $client->flows()->deprecateFlow('<flow-id>');
```

## Delete Flow

```php
<?php

declare(strict_types=1);

$result = $client->flows()->deleteFlow('<flow-id>');
```

## Set Encryption Public Key

```php
<?php

declare(strict_types=1);

$result = $client->flows()->setEncryptionPublicKey('<public-key>');
```

## Get Encryption Public Key

```php
<?php

declare(strict_types=1);

$key = $client->flows()->getEncryptionPublicKey();
echo $key->payload['public_key'];
```

## Get Endpoint Metrics

```php
<?php

declare(strict_types=1);

$metrics = $client->flows()->getEndpointMetrics(
    flowId: '<flow-id>',
    fieldsExpression: 'endpoints.health.status,endpoints.health.last_health_check_time'
);
print_r($metrics->payload);
```

```
