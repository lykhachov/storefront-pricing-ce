<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

use Magento\Framework\App\ResourceConnection;
use Magento\PricingStorefront\Model\PriceBookRepository;
use Magento\PricingStorefront\Model\Storage\PriceRepository;
use Magento\TestFramework\Helper\Bootstrap;

$objectManager = Bootstrap::getObjectManager();

/* @var ResourceConnection $resourceConnection */
$resourceConnection = $objectManager->create(ResourceConnection::class);

$priceBooks = [
    [
        PriceBookRepository::KEY_ID => PriceBookRepository::DEFAULT_PRICE_BOOK_ID,
        PriceBookRepository::KEY_PARENT_ID => null,
        PriceBookRepository::KEY_NAME => 'Default',
        PriceBookRepository::KEY_WEBSITE_IDS => null,
        PriceBookRepository::KEY_CUSTOMER_GROUP_IDS => null
    ],
    [
        PriceBookRepository::KEY_ID => 'w-1-g-1',
        PriceBookRepository::KEY_PARENT_ID => PriceBookRepository::DEFAULT_PRICE_BOOK_ID,
        PriceBookRepository::KEY_NAME => 'w-1-g-1',
        PriceBookRepository::KEY_WEBSITE_IDS => 1,
        PriceBookRepository::KEY_CUSTOMER_GROUP_IDS => 1
    ],
    [
        PriceBookRepository::KEY_ID => 'w-1-g-12',
        PriceBookRepository::KEY_PARENT_ID => 'w-1-g-1',
        PriceBookRepository::KEY_NAME => 'w-1-g-1',
        PriceBookRepository::KEY_WEBSITE_IDS => 1,
        PriceBookRepository::KEY_CUSTOMER_GROUP_IDS => 2
    ]
];

$prices = [
    [
        'entity_id' => 'same_as_default_price_book_price_product',
        'pricebook_id' => PriceBookRepository::DEFAULT_PRICE_BOOK_ID,
        'minimum_price_regular' => 5.0,
        'minimum_price_final' => 5.0,
        'maximum_price_regular' => 5.0,
        'maximum_price_final' => 5.0,
        'qty' => 1.0
    ],
    [
        'entity_id' => 'different_then_default_price_product',
        'pricebook_id' => PriceBookRepository::DEFAULT_PRICE_BOOK_ID,
        'minimum_price_regular' => 12.0,
        'minimum_price_final' => 12.0,
        'maximum_price_regular' => 12.0,
        'maximum_price_final' => 12.0,
        'qty' => 1.0
    ],
    [
        'entity_id' => 'same_as_parent_price_product',
        'pricebook_id' => PriceBookRepository::DEFAULT_PRICE_BOOK_ID,
        'minimum_price_regular' => 77.0,
        'minimum_price_final' => 77.0,
        'maximum_price_regular' => 77.0,
        'maximum_price_final' => 77.0,
        'qty' => 1.0
    ],
    [
        'entity_id' => 'same_as_parent_price_product',
        'pricebook_id' => 'w-1-g-1',
        'minimum_price_regular' => 15.0,
        'minimum_price_final' => 15.0,
        'maximum_price_regular' => 15.0,
        'maximum_price_final' => 15.0,
        'qty' => 1.0
    ],
    [
        'entity_id' => 'new_price_same_parent_price_product',
        'pricebook_id' => PriceBookRepository::DEFAULT_PRICE_BOOK_ID,
        'minimum_price_regular' => 31.0,
        'minimum_price_final' => 31.0,
        'maximum_price_regular' => 31.0,
        'maximum_price_final' => 31.0,
        'qty' => 1.0
    ],
    [
        'entity_id' => 'new_price_same_parent_price_product',
        'pricebook_id' => 'w-1-g-12',
        'minimum_price_regular' => 20.0,
        'minimum_price_final' => 20.0,
        'maximum_price_regular' => 20.0,
        'maximum_price_final' => 20.0,
        'qty' => 1.0
    ],
    [
        'entity_id' => 'new_price_same_parent_price_product',
        'pricebook_id' => 'w-1-g-1',
        'minimum_price_regular' => 25.0,
        'minimum_price_final' => 25.0,
        'maximum_price_regular' => 25.0,
        'maximum_price_final' => 25.0,
        'qty' => 1.0
    ]
];

// create price books
$resourceConnection->getConnection()->delete(PriceBookRepository::PRICES_BOOK_TABLE_NAME, []);

$resourceConnection->getConnection()->insertMultiple(
    PriceBookRepository::PRICES_BOOK_TABLE_NAME,
    $priceBooks
);

// create prices
$resourceConnection->getConnection()->delete(PriceRepository::PRICES_TABLE_NAME, []);

$resourceConnection->getConnection()->insertMultiple(
    PriceRepository::PRICES_TABLE_NAME,
    $prices
);
