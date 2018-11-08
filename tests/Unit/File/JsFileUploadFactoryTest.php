<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaTrackingComponent\Tests\Unit\File;

use OxidEsales\EcondaTrackingComponent\File\JsFileUploadFactory;
use PHPUnit\Framework\TestCase;

class JsFileUploadFactoryTest extends TestCase
{
    public function testGetFileUploader()
    {
        $factory = new JsFileUploadFactory('test_dir_path', 'test_file.js');
        $_FILES['file_to_upload'] = ['name' => 'any'];

        $this->assertInstanceOf(\FileUpload\FileUpload::class, $factory->makeFileUploader());
    }
}
