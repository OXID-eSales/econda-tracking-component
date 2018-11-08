<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaTrackingComponent\TrackingCodeGenerator;

/**
 * Responsible for generating Econda tracking script.
 */
interface TrackingCodeGeneratorInterface
{
    /**
     * Generates JS script for Econda tracking.
     *
     * @return string
     */
    public function generateCode(): string;
}
