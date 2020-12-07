<?php
# Generated by the Magento PHP proto generator.  DO NOT EDIT!

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\PricingStorefrontApi\Api\Data;

/**
 * Autogenerated description for Scope class
 *
 * phpcs:disable Magento2.PHP.FinalImplementation
 * @SuppressWarnings(PHPMD)
 * @SuppressWarnings(PHPCPD)
 */
final class Scope implements ScopeInterface
{

    /**
     * @var array
     */
    private $website;

    /**
     * @var array
     */
    private $customerGroup;
    
    /**
     * @inheritdoc
     *
     * @return string[]
     */
    public function getWebsite(): array
    {
        return (array) $this->website;
    }
    
    /**
     * @inheritdoc
     *
     * @param string[] $value
     * @return void
     */
    public function setWebsite(array $value): void
    {
        $this->website = $value;
    }
    
    /**
     * @inheritdoc
     *
     * @return string[]
     */
    public function getCustomerGroup(): array
    {
        return (array) $this->customerGroup;
    }
    
    /**
     * @inheritdoc
     *
     * @param string[] $value
     * @return void
     */
    public function setCustomerGroup(array $value): void
    {
        $this->customerGroup = $value;
    }
}