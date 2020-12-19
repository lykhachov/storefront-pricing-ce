<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\PricingStorefront\Test\Api;

use Magento\PricingStorefront\Model\Storage\PriceRepository;
use Magento\PricingStorefrontApi\Api\Data\AssignPricesRequestMapper;
use Magento\PricingStorefrontApi\Api\Data\GetPricesRequestMapper;
use Magento\PricingStorefrontApi\Api\Data\GetPricesOutputArrayMapper;
use Magento\PricingStorefrontApi\Api\PriceBookServiceServerInterface;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\PricingStorefront\Model\PriceBookRepository;
use Magento\PricingStorefront\Model\PriceManagement;

class PriceServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var PriceBookServiceServerInterface
     */
    private $priceBookService;

    /**
     * @var AssignPricesRequestMapper
     */
    private $assignPricesRequestMapper;

    /**
     * @var PriceManagement
     */
    private $priceManagement;

    /**
     * @var GetPricesRequestMapper
     */
    private $getPricesRequestMapper;

    /**
     * @var GetPricesOutputArrayMapper
     */
    private $getPricesOutputArrayMapper;

    /**
     * @inheridoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->priceBookService = Bootstrap::getObjectManager()->create(PriceBookServiceServerInterface::class);
        $this->assignPricesRequestMapper = Bootstrap::getObjectManager()->create(AssignPricesRequestMapper::class);
        $this->getPricesRequestMapper = Bootstrap::getObjectManager()->create(GetPricesRequestMapper::class);
        $this->getPricesOutputArrayMapper = Bootstrap::getObjectManager()->create(GetPricesOutputArrayMapper::class);
        $this->priceManagement = Bootstrap::getObjectManager()->create(PriceManagement::class);
    }

    /**
     * Test Pricing Service's assign prices endpoint.
     *
     * @magentoDataFixture Magento_PricingStorefront::Test/Api/_files/prices.php
     * @magentoDbIsolation disabled
     * @param array $request
     * @param array $expected
     * @dataProvider assignPricesDataProvider
     */
    public function testAssignPrices(array $request, array $expected): void
    {
        $assignRequest = $this->assignPricesRequestMapper->setData($request)->build();

        $priceResponse = $this->priceBookService->assignPrices($assignRequest);
        $status = $priceResponse->getStatus();

        self::assertEquals($expected['status']['code'], $status->getCode());
        self::assertEquals($expected['status']['message'], $status->getMessage());

        $actualPriceArray = array_pop($request['prices']);

        try {
            $actualPrice = $this->priceManagement->fetchPrice(
                (string)$actualPriceArray['entity_id'],
                (string)$request['price_book_id'],
                (float)$actualPriceArray['qty']
            );
        } catch (\Throwable $e) {
            return;
        }

        self::assertEquals((string)$expected['price_book_id'], (string)$actualPrice['pricebook_id']);
        $minRegular = (float)$actualPrice[PriceRepository::KEY_MINIMUM_PRICE][PriceRepository::KEY_REGULAR] ?? 0;
        $minFinal = (float)$actualPrice[PriceRepository::KEY_MINIMUM_PRICE][PriceRepository::KEY_FINAL] ?? 0;
        $maxRegular = (float)$actualPrice[PriceRepository::KEY_MAXIMUM_PRICE][PriceRepository::KEY_REGULAR] ?? 0;
        $maxFinal = (float)$actualPrice[PriceRepository::KEY_MAXIMUM_PRICE][PriceRepository::KEY_FINAL] ?? 0;
        self::assertEquals(
            $expected['price'],
            [
                PriceRepository::KEY_PRODUCT_ID => $actualPrice[PriceRepository::KEY_PRODUCT_ID],
                PriceRepository::KEY_MINIMUM_PRICE => [
                    PriceRepository::KEY_REGULAR => $minRegular,
                    PriceRepository::KEY_FINAL => $minFinal
                ],
                PriceRepository::KEY_MAXIMUM_PRICE => [
                    PriceRepository::KEY_REGULAR => $maxRegular,
                    PriceRepository::KEY_FINAL => $maxFinal
                ],
                PriceRepository::KEY_QTY => (float)$actualPrice[PriceRepository::KEY_QTY]
            ]
        );
    }

    /**
     * @return array
     */
    public function assignPricesDataProvider(): array
    {
        return [
            [// case: If default book has no price set - exception will be thrown
                [
                    'price_book_id' => 'w-1-g-12',
                    'prices' => [
                        [
                            'entity_id' => 'no_default_price_product',
                            'minimum_price' => [
                                'regular' => 1.0,
                                'final' => 1.0
                            ],
                            'maximum_price' => [
                                'regular' => 1.0,
                                'final' => 1.0
                            ],
                            'qty' => 1.0
                        ]
                    ]
                ],
                [
                    'price_book_id' => null,
                    'price' => [],
                    'status' => [
                        'code' => '2',
                        'message' => \json_encode([
                            [
                                "entity_id" => "no_default_price_product",
                                "error" => "Price for price book default and product no_default_price_product doesn't exist"
                            ]
                        ])
                    ]
                ]
            ],
            [// case: book has no own price, parent has no own price, price same as default - nothing inserted
                [
                    'price_book_id' => 'w-1-g-12',
                    'prices' => [
                        [
                            'entity_id' => 'same_as_default_price_book_price_product',
                            'minimum_price' => [
                                'regular' => 5.0,
                                'final' => 5.0
                            ],
                            'maximum_price' => [
                                'regular' => 5.0,
                                'final' => 5.0
                            ],
                            'qty' => 1.0
                        ]
                    ]
                ],
                [
                    'price_book_id' => PriceBookRepository::DEFAULT_PRICE_BOOK_ID,
                    'price' => [
                        'entity_id' => 'same_as_default_price_book_price_product',
                        'minimum_price' => [
                            'regular' => 5.0,
                            'final' => 5.0
                        ],
                        'maximum_price' => [
                            'regular' => 5.0,
                            'final' => 5.0
                        ],
                        'qty' => 1.0
                    ],
                    'status' => [
                        'code' => '0',
                        'message' => 'Prices was assigned with success.'
                    ]
                ]
            ],
            [// case: book has no own price, parent has no own price - price different then default - assign to book
                [
                    'price_book_id' => 'w-1-g-12',
                    'prices' => [
                        [
                            'entity_id' => 'different_then_default_price_product',
                            'minimum_price' => [
                                'regular' => 10.0,
                                'final' => 10.0
                            ],
                            'maximum_price' => [
                                'regular' => 10.0,
                                'final' => 10.0
                            ],
                            'qty' => 1.0
                        ]
                    ]
                ],
                [
                    'price_book_id' => 'w-1-g-12',
                    'price' => [
                        'entity_id' => 'different_then_default_price_product',
                        'minimum_price' => [
                            'regular' => 10.0,
                            'final' => 10.0
                        ],
                        'maximum_price' => [
                            'regular' => 10.0,
                            'final' => 10.0
                        ],
                        'qty' => 1.0
                    ],
                    'status' => [
                        'code' => '0',
                        'message' => 'Prices was assigned with success.'
                    ]
                ]
            ],
            [// case: if book has no own price, but some parent has the same price - nothing will be inserted
                [
                    'price_book_id' => 'w-1-g-12',
                    'prices' => [
                        [
                            'entity_id' => 'same_as_parent_price_product',
                            'minimum_price' => [
                                'regular' => 15.0,
                                'final' => 15.0
                            ],
                            'maximum_price' => [
                                'regular' => 15.0,
                                'final' => 15.0
                            ],
                            'qty' => 1.0
                        ]
                    ]
                ],
                [
                    'price_book_id' => 'w-1-g-1',
                    'price' => [
                        'entity_id' => 'same_as_parent_price_product',
                        'minimum_price' => [
                            'regular' => 15.0,
                            'final' => 15.0
                        ],
                        'maximum_price' => [
                            'regular' => 15.0,
                            'final' => 15.0
                        ],
                        'qty' => 1.0
                    ],
                    'status' => [
                        'code' => '0',
                        'message' => 'Prices was assigned with success.'
                    ]
                ]
            ],
            [// case: book has own price, new price same as parent price - current book price row will be removed
                [
                    'price_book_id' => 'w-1-g-12',
                    'prices' => [
                        [
                            'entity_id' => 'new_price_same_parent_price_product',
                            'minimum_price' => [
                                'regular' => 25.0,
                                'final' => 25.0
                            ],
                            'maximum_price' => [
                                'regular' => 25.0,
                                'final' => 25.0
                            ],
                            'qty' => 1.0
                        ]
                    ]
                ],
                [
                    'price_book_id' => 'w-1-g-1',
                    'price' => [
                        'entity_id' => 'new_price_same_parent_price_product',
                        'minimum_price' => [
                            'regular' => 25.0,
                            'final' => 25.0
                        ],
                        'maximum_price' => [
                            'regular' => 25.0,
                            'final' => 25.0
                        ],
                        'qty' => 1.0
                    ],
                    'status' => [
                        'code' => '0',
                        'message' => 'Prices was assigned with success.'
                    ]
                ]
            ]

        ];
    }

    /**
     * Test Pricing Service's get prices endpoint.
     *
     * @magentoDataFixture Magento_PricingStorefront::Test/Api/_files/prices.php
     * @magentoDbIsolation disabled
     * @param array $request
     * @param array $expected
     * @throws \Throwable
     * @dataProvider getPricesDataProvider
     */
    public function testGetPrices(array $request, array $expected): void
    {
        $getRequest = $this->getPricesRequestMapper->setData($request)->build();
        $priceResponse = $this->priceBookService->getPrices($getRequest);
        $prices = $this->getPricesOutputArrayMapper->convertToArray($priceResponse);

        self::assertEquals($expected, $prices['prices']);
    }

    /**
     * @return array
     */
    public function getPricesDataProvider(): array
    {
        return [
            [// case: price for book that has no price assigned to default
                [
                    'price_book_id' => 'w-1-g-12',
                    'ids' => ['no_default_price_product']
                ],
                [
                    [
                        'entity_id' => 'no_default_price_product',
                        'qty' => 0.00
                    ]
                ]
            ],
            [// case: price for book that has only default price
                [
                    'price_book_id' => 'w-1-g-12',
                    'ids' => ['same_as_default_price_book_price_product']
                ],
                [
                    [
                        'entity_id' => 'same_as_default_price_book_price_product',
                        'minimum_price' => [
                            'regular' => 5.0,
                            'final' => 5.0
                        ],
                        'maximum_price' => [
                            'regular' => 5.0,
                            'final' => 5.0
                        ],
                        'qty' => 1.0
                    ]
                ]
            ],
            [// case: book has parent price
                [
                    'price_book_id' => 'w-1-g-12',
                    'ids' => ['same_as_parent_price_product']
                ],
                [
                    [
                        'entity_id' => 'same_as_parent_price_product',
                        'minimum_price' => [
                            'regular' => 15.0,
                            'final' => 15.0
                        ],
                        'maximum_price' => [
                            'regular' => 15.0,
                            'final' => 15.0
                        ],
                        'qty' => 1.0
                    ]
                ]
            ],
            [// case: book has own price
                [
                    'price_book_id' => 'w-1-g-12',
                    'ids' => ['new_price_same_parent_price_product']
                ],
                [
                    [
                        'entity_id' => 'new_price_same_parent_price_product',
                        'minimum_price' => [
                            'regular' => 20.0,
                            'final' => 20.0
                        ],
                        'maximum_price' => [
                            'regular' => 20.0,
                            'final' => 20.0
                        ],
                        'qty' => 1.0
                    ]
                ]
            ]
        ];
    }
}
