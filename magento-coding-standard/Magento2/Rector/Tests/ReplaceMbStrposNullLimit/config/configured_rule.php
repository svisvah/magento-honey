<?php
/**
 * Copyright 2022 Adobe
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

use Magento2\Rector\Src\ReplaceMbStrposNullLimit;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ReplaceMbStrposNullLimit::class);
};
