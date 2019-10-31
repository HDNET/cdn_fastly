<?php

/**
 * AbstractService.
 */

declare(strict_types=1);

namespace HDNET\CdnFastly\Service;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * AbstractService.
 */
abstract class AbstractService implements SingletonInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
}
