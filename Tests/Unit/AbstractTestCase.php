<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Tests\Unit;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

abstract class AbstractTestCase extends UnitTestCase
{
    public function tearDown(): void
    {
        $this->resetSingletonInstances = true;
        parent::tearDown();
    }
}
