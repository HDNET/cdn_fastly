<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Service;

interface ConfigurationServiceInterface
{
    public function getApiKey(): string;

    public function getServiceId(): string;

    public function getSoftpurge(): bool;
}
