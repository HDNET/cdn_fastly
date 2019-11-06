<?php
/**
 *
 */

namespace HDNET\CdnFastly\Service;


interface ConfigurationServiceInterface
{

    public function getApiKey():string;

    public function getServiceId():string;

}
