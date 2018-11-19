<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaTrackingComponent\Adapter\ProductPreparation;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Registry;

/**
 * Prepares product data for later use in another component.
 */
class ProductDataPreparator
{
    /**
     * @var ProductTitlePreparator
     */
    private $productTitlePreparator;

    /**
     * @param ProductTitlePreparator $productTitlePreparator
     */
    public function __construct($productTitlePreparator)
    {
        $this->productTitlePreparator = $productTitlePreparator;
    }

    /**
     * @param Article $product
     * @param string  $categoryPath
     *
     * @return array
     */
    public function prepareForDetailsPage($product, $categoryPath = 'NULL')
    {
        return $this->prepareData($product, $categoryPath);
    }

    /**
     * @param Article $product
     * @param string  $categoryPath
     * @param int     $amount
     *
     * @return array
     */
    public function prepareForTransaction($product, $categoryPath = 'NULL', $amount = 1)
    {
        $data = $this->prepareData($product, $categoryPath);
        $dataWithCount = array_merge(
            $data,
            ['count' => $amount]
        );

        return $dataWithCount;
    }

    /**
     * @param Article|ProductInterface $product
     * @param string                   $categoryPath
     *
     * @return array
     */
    protected function prepareData($product, $categoryPath)
    {
        $currentProductId = $product->oeEcondaTrackingGetProductId();
        $productData = [
            'pid' => $currentProductId,
            'sku' => $product->oeEcondaTrackingGetSku(),
        ];

        $currency = Registry::getConfig()->getActShopCurrencyObject();
        $productMergedData = array_merge(
            $productData,
            [
                'name'  => $this->getProductTitlePreparator()->prepareProductTitle($product),
                'group' => "{$categoryPath}/{$product->oxarticles__oxtitle->value}",
                'price' => $product->getPrice()->getBruttoPrice() * (1 / $currency->rate),
                'var1' => $product->getVendor() ? $product->getVendor()->getTitle() : "NULL",
                'var2' => $product->getManufacturer() ? $product->getManufacturer()->getTitle() : "NULL",
                'var3' => $product->getId(),
            ]
        );

        return $productMergedData;
    }

    /**
     * @return ProductTitlePreparator
     */
    protected function getProductTitlePreparator()
    {
        return $this->productTitlePreparator;
    }
}
