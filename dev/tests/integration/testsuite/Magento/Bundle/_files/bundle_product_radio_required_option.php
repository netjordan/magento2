<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

use Magento\Bundle\Model\Product\Price;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\Type\AbstractType;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ProductFactory;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\TestFramework\Bundle\Model\PrepareBundleLinks;
use Magento\TestFramework\Helper\Bootstrap;

require __DIR__ . '/../../../Magento/Catalog/_files/product_simple_duplicated.php';

$objectManager = Bootstrap::getObjectManager();
/** @var WebsiteRepositoryInterface $websiteRepository */
$websiteRepository = $objectManager->get(WebsiteRepositoryInterface::class);
$baseWebsiteId = $websiteRepository->get('base')->getId();
/** @var ProductRepositoryInterface $productRepository */
$productRepository = $objectManager->get(ProductRepositoryInterface::class);
$productRepository->cleanCache();
/** @var PrepareBundleLinks $prepareBundleLinks */
$prepareBundleLinks = $objectManager->get(PrepareBundleLinks::class);
/** @var ProductFactory $productFactory */
$productFactory = $objectManager->get(ProductFactory::class);
$bundleProduct = $productFactory->create();
$bundleProduct->setTypeId(Type::TYPE_BUNDLE)
    ->setAttributeSetId($product->getDefaultAttributeSetId())
    ->setWebsiteIds([$baseWebsiteId])
    ->setName('Bundle Product')
    ->setSku('bundle-product')
    ->setVisibility(Visibility::VISIBILITY_BOTH)
    ->setStatus(Status::STATUS_ENABLED)
    ->setStockData(['use_config_manage_stock' => 1, 'qty' => 100, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
    ->setPriceView(0)
    ->setSkuType(1)
    ->setWeightType(1)
    ->setPriceType(Price::PRICE_TYPE_DYNAMIC)
    ->setPrice(10.0)
    ->setShipmentType(AbstractType::SHIPMENT_TOGETHER);

$bundleOptionsData = [
    [
        'title' => 'Radio Options',
        'default_title' => 'Radio Options',
        'type' => 'radio',
        'required' => 1,
        'delete' => '',
    ],
];
$bundleSelectionsData = [
    [
        'sku' => $product->getSku(),
        'selection_qty' => 1,
        'selection_price_value' => 0,
        'selection_can_change_qty' => 1,
        'delete' => '',
        'option_id' => 1,
    ],
];

$bundleProduct = $prepareBundleLinks->execute($bundleProduct, $bundleOptionsData, [$bundleSelectionsData]);
$productRepository->save($bundleProduct);
