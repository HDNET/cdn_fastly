<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Service;

use Override;

class ConfigurationServiceFake extends ConfigurationService
{
    #[Override]
    protected function findConfiguration(): array
    {
        // .... ggf. umgebungsvariablen
        return [
            'apiKey' => 'asdasd',
            'serviceId' => 'valid',
        ];
    }
}
