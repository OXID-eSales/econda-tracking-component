<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaTrackingComponent\Tests\Unit\File;

use FileUpload\File;
use OxidEsales\EcondaTrackingComponent\File\Validator\PermissionsValidator;
use PHPUnit\Framework\TestCase;

class PermissionsValidatorTest extends TestCase
{
    use VirtualFileHelperTrait;

    public function testValidPermissions()
    {
        $pathToCreateDirectory = $this->createVirtualFile('writable_file', 'contents');
        $validator = new PermissionsValidator($pathToCreateDirectory, 'writable_file');
        $this->assertTrue($validator->validate(new File('')));
    }

    public function testInValidPermissionsWhenDirectoryNotWritable()
    {
        $pathToCreateDirectory = $this->createVirtualPath();
        chmod($pathToCreateDirectory, 555);
        $validator = new PermissionsValidator($pathToCreateDirectory, 'file');
        $this->assertFalse($validator->validate(new File('')));
    }

    public function testInValidPermissionsWhenFileNotWritable()
    {
        $pathToCreateDirectory = $this->createVirtualPath();
        chmod("$pathToCreateDirectory/file", 555);
        $validator = new PermissionsValidator($pathToCreateDirectory, 'file');
        $this->assertFalse($validator->validate(new File('')));
    }
}
