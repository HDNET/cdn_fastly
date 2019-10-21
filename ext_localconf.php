<?php

defined('TYPO3_MODE') || die();

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions'][] = \Pavel\CdnFastly\Hooks\FastlyClearCache::class . '->FastlyClearCache';
