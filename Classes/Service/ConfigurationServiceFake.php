<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Service;

class ConfigurationServiceFake extends ConfigurationService
{
    protected function findConfiguration(): array
    {
        // .... ggf. umgebungsvariablen
        return [
            'apiKey' => 'asdasd',
            'serviceId' => 'valid',
        ];
    }
}
