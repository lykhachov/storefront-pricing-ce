<?php
# Generated by the Magento PHP proto generator.  DO NOT EDIT!

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\PricingStorefrontApi\Api;

use \Magento\PricingStorefrontApi\Api\Data\PriceBookScopeRequestInterface;
use \Magento\PricingStorefrontApi\Api\Data\PriceBookResponseInterface;
use \Magento\PricingStorefrontApi\Api\Data\PriceBookCreateRequestInterface;
use \Magento\PricingStorefrontApi\Api\Data\PriceBookCreateResponseInterface;
use \Magento\PricingStorefrontApi\Api\Data\PriceBookDeleteRequestInterface;
use \Magento\PricingStorefrontApi\Api\Data\PriceBookDeleteResponseInterface;
use \Magento\PricingStorefrontApi\Api\Data\AssignPricesRequestInterface;
use \Magento\PricingStorefrontApi\Api\Data\PriceBookAssignPricesResponseInterface;
use \Magento\PricingStorefrontApi\Api\Data\UnassignPricesRequestInterface;
use \Magento\PricingStorefrontApi\Api\Data\PriceBookUnassignPricesResponseInterface;
use \Magento\PricingStorefrontApi\Api\Data\GetPricesRequestInterface;
use \Magento\PricingStorefrontApi\Api\Data\GetPricesOutputInterface;

/**
 * Autogenerated description for PriceBookServiceServerInterface interface
 */
interface PriceBookServiceServerInterface
{
    /**
     * Autogenerated description for findPriceBook interface method
     *
     * @param PriceBookScopeRequestInterface $request
     * @return PriceBookResponseInterface
     * @throws \Throwable
     */
    public function findPriceBook(PriceBookScopeRequestInterface $request): PriceBookResponseInterface;

    /**
     * Autogenerated description for createPriceBook interface method
     *
     * @param PriceBookCreateRequestInterface $request
     * @return PriceBookCreateResponseInterface
     * @throws \Throwable
     */
    public function createPriceBook(PriceBookCreateRequestInterface $request): PriceBookCreateResponseInterface;

    /**
     * Autogenerated description for deletePriceBook interface method
     *
     * @param PriceBookDeleteRequestInterface $request
     * @return PriceBookDeleteResponseInterface
     * @throws \Throwable
     */
    public function deletePriceBook(PriceBookDeleteRequestInterface $request): PriceBookDeleteResponseInterface;

    /**
     * Autogenerated description for assignPrices interface method
     *
     * @param AssignPricesRequestInterface $request
     * @return PriceBookAssignPricesResponseInterface
     * @throws \Throwable
     */
    public function assignPrices(AssignPricesRequestInterface $request): PriceBookAssignPricesResponseInterface;

    /**
     * Autogenerated description for unassignPrices interface method
     *
     * @param UnassignPricesRequestInterface $request
     * @return PriceBookUnassignPricesResponseInterface
     * @throws \Throwable
     */
    public function unassignPrices(UnassignPricesRequestInterface $request): PriceBookUnassignPricesResponseInterface;

    /**
     * Autogenerated description for getPrices interface method
     *
     * @param GetPricesRequestInterface $request
     * @return GetPricesOutputInterface
     * @throws \Throwable
     */
    public function getPrices(GetPricesRequestInterface $request): GetPricesOutputInterface;

    /**
     * Autogenerated description for getTierPrices interface method
     *
     * @param GetPricesRequestInterface $request
     * @return GetPricesOutputInterface
     * @throws \Throwable
     */
    public function getTierPrices(GetPricesRequestInterface $request): GetPricesOutputInterface;
}
