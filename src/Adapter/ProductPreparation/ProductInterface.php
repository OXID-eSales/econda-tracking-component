<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaTrackingComponent\Adapter\ProductPreparation;

/**
 * Product model interface, implemented classes provides additional product information.
 */
interface ProductInterface
{
    public function oeEcondaTrackingHasVariants();

    public function oeEcondaTrackingGetSku();

    public function oeEcondaTrackingGetProductId();
}
