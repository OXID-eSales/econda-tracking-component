<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaTrackingComponent\Tests\Unit\File;

use FileUpload\File;
use OxidEsales\EcondaTrackingComponent\File\Validator\ContentValidator;
use PHPUnit\Framework\TestCase;

class ContentValidatorTest extends TestCase
{
    use VirtualFileHelperTrait;

    public function testValidFile()
    {
        $pathToVirtualFile = $this->createVirtualFile(
            'file',
            'someTestingStringWith' . ContentValidator::NEEDLE . ' in it'
        );
        list($file, $isValid) = $this->executeValidation($pathToVirtualFile);
        $this->assertTrue($isValid);
        $this->assertEmpty($file->error);
    }

    public function testInvalidFile()
    {
        $pathToVirtualFile = $this->createVirtualFile(
            'file',
            'someTestingStringWithOut needle in it'
        );
        list($file, $isValid) = $this->executeValidation($pathToVirtualFile);
        $this->assertFalse($isValid);
        $this->assertSame($file->error, 'Provided file is not file_name.js file');
    }

    /**
     * @param $pathToCreateDirectory
     * @return array
     */
    protected function executeValidation($pathToCreateDirectory)
    {
        $validator = new ContentValidator('file_name.js');
        $file = new File($pathToCreateDirectory);
        $isValid = $validator->validate($file);

        return [$file, $isValid];
    }
}
