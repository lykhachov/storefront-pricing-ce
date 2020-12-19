<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types = 1);

use Magento\Framework\App\ResourceConnection;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\PricingStorefront\Model\Storage\PriceRepository;
use Magento\PricingStorefront\Model\PriceBookRepository;

$objectManager = Bootstrap::getObjectManager();

/* @var ResourceConnection $resourceConnection */
$resourceConnection = $objectManager->create(ResourceConnection::class);

$resourceConnection->getConnection()->delete(PriceRepository::PRICES_TABLE_NAME, []);
$resourceConnection->getConnection()->delete(PriceBookRepository::PRICES_BOOK_TABLE_NAME, []);
