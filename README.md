# EXT:cdn_fastly

Extension for handling TYPO3 requests for better CDN integration in fastly.

## Installation

Run `composer require hdnet/cdn-fastly` in your project root.
It will automatically install the extension and all its dependencies.

## Development

* Clone repository `git clone git@github.com:HDNET/cdn_fastly.git`
* Install dependencies `composer install`
* Run tests `composer test`

## Configuration

- Install the extension ([Documentation](https://docs.typo3.org/m/typo3/reference-coreapi/8.7/en-us/ExtensionArchitecture/Installation/Index.html))
- Include the static TypoScript of the extension
    - traditional syntax: `<INCLUDE_TYPOSCRIPT: source="FILE:EXT:cdn_fastly/Configuration/TypoScript/Main/setup.txt">`
    - modern syntax: `@import 'EXT:cdn_fastly/Configuration/TypoScript/Main/setup.txt`
- Set the following TypoScript variables via constant editor in the TYPO3 Template module
    - plugin.tx_cdnfastly.settings.fastly.apiKey
    - plugin.tx_cdnfastly.settings.fastly.serviceId

## Fastly

* [Cache control tutorial Guide](https://docs.fastly.com/en/guides/cache-control-tutorial)
* [How caching and CNDs work](https://docs.fastly.com/en/guides/how-caching-and-cdns-work)

## TYPO3 

* [Caching framework](https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ApiOverview/CachingFramework/Index.html)
* [Testing framework](https://github.com/TYPO3/testing-framework)

## PSR Information

* [PSR-7 HTTP Message Interface](https://www.php-fig.org/psr/psr-7/)
* [PSR-15 HTTP Middleware](https://www.php-fig.org/psr/psr-15/)
