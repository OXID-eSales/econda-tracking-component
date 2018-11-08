<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaTrackingComponent\Tests\Unit\File;

use FileUpload\File;
use OxidEsales\EcondaTrackingComponent\File\Validator\ExtensionValidator;
use PHPUnit\Framework\TestCase;

class ExtensionValidatorTest extends TestCase
{
    use VirtualFileHelperTrait;

    public function testValidExtension()
    {
        list($file, $isValid) = $this->executeValidation('emos.'.ExtensionValidator::EXTENSION);
        $this->assertTrue($isValid);
        $this->assertEmpty($file->error);
    }

    public function testValidExtensionWithDoubleDots()
    {
        list($file, $isValid) = $this->executeValidation('emos.anything.'.ExtensionValidator::EXTENSION);
        $this->assertTrue($isValid);
        $this->assertEmpty($file->error);
    }

    public function testInvalidExtension()
    {
        list($file, $isValid) = $this->executeValidation('emos.txt');
        $this->assertFalse($isValid);
        $this->assertNotEmpty($file->error);
    }

    public function testInvalidExtensionWithNonStandardEnding()
    {
        list($file, $isValid) = $this->executeValidation('emos.xjs');
        $this->assertFalse($isValid);
        $this->assertNotEmpty($file->error);
    }

    /**
     * @param $fileName
     * @return array
     */
    protected function executeValidation($fileName)
    {
        $filePath = $this->createVirtualFile($fileName, 'contents');
        $validator = new ExtensionValidator($filePath);
        $file = new File('');
        $isValid = $validator->validate($file);

        return [$file, $isValid];
    }
}
