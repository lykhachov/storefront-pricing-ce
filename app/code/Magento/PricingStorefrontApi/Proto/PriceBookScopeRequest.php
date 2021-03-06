<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: prices.proto

namespace Magento\PricingStorefrontApi\Proto;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>magento.pricingStorefrontApi.proto.PriceBookScopeRequest</code>
 */
class PriceBookScopeRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.magento.pricingStorefrontApi.proto.Scope scopes = 1;</code>
     */
    protected $scopes = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Magento\PricingStorefrontApi\Proto\Scope $scopes
     * }
     */
    public function __construct($data = null)
    {
        \Magento\PricingStorefrontApi\Metadata\Prices::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.magento.pricingStorefrontApi.proto.Scope scopes = 1;</code>
     * @return \Magento\PricingStorefrontApi\Proto\Scope
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * Generated from protobuf field <code>.magento.pricingStorefrontApi.proto.Scope scopes = 1;</code>
     * @param \Magento\PricingStorefrontApi\Proto\Scope $var
     * @return $this
     */
    public function setScopes($var)
    {
        GPBUtil::checkMessage($var, \Magento\PricingStorefrontApi\Proto\Scope::class);
        $this->scopes = $var;

        return $this;
    }
}
