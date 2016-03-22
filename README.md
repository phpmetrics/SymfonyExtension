# SymfonyExtension for PhpMetrics

This plugin add Symfony support directly in your [PhpMetrics](https://github.com/phpmetrics/phpmetrics) reports.

![Screenshot of PhpMetricsSymfonyExtension](https://cloud.githubusercontent.com/assets/1076296/13907012/9eebfd58-eee4-11e5-8fe0-6c3b5f6c81a1.png "Screenshot of PhpMetricsSymfonyExtension")

## Installation

**As phar archive**:

    wget https://raw.githubusercontent.com/phpmetrics/SymfonyExtension/master/symfony-extension.phar
    phpmetrics --plugins=symfony-extension.phar --report-html=report.html <my-folder>

or **with Composer**:

    composer require phpmetrics/phpmetrics phpmetrics/symfony-extension
    ./vendor/bin/phpmetrics --plugins=./vendor/phpmetrics/symfony-extension/SymfonyExtension.php --report-html=report.html <my-folder>

    
## License

Please see the LICENSE file