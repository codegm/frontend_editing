<?php
declare(strict_types = 1);
namespace TYPO3\CMS\FrontendEditing\Tests\Unit\ViewHelpers;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Nimut\TestingFramework\Rendering\RenderingContextFixture;
use Nimut\TestingFramework\TestCase\ViewHelperBaseTestcase;
use TYPO3\CMS\FrontendEditing\Tests\Unit\Fixtures\ContentEditableFixtures;
use TYPO3\CMS\FrontendEditing\ViewHelpers\CustomDropZoneViewHelper;

/**
 * Test case for TYPO3\CMS\FrontendEditing\ViewHelpers\CustomDropZoneViewHelper
 *
 * @extensionScannerIgnoreFile
 */
class CustomDropZoneViewHelperTest extends ViewHelperBaseTestcase
{

    /**
     * @test
     */
    public function testInitializeArgumentsRegistersExpectedArguments()
    {
        $instance = $this->getMock(CustomDropZoneViewHelper::class, ['registerArgument']);
        $instance->expects($this->at(0))->method('registerArgument')->with(
            'tables',
            'array',
            $this->anything(),
            true,
            false
        );
        $instance->expects($this->at(1))->method('registerArgument')->with(
            'fields',
            'array',
            $this->anything(),
            false,
            false
        );
        $instance->expects($this->at(2))->method('registerArgument')->with(
            'pageUid',
            'string',
            $this->anything(),
            false,
            false
        );
        $instance->expects($this->at(3))->method('registerArgument')->with(
            'prepend',
            'bool',
            $this->anything(),
            false,
            false
        );
        $instance->setRenderingContext(new RenderingContextFixture());
        $instance->initializeArguments();
    }

    /**
     * @dataProvider getRenderTestValuesWithoutFrontendEditionEnabled
     * @param mixed $value
     * @param array $arguments
     * @param string $expected
     */
    public function testRenderWithoutFrontendEditingEnabled($value, array $arguments, $expected)
    {
        $instance = $this->getMock(CustomDropZoneViewHelper::class, ['renderChildren']);
        $instance->expects($this->once())->method('renderChildren')->willReturn($value);
        $instance->setArguments($arguments);
        $instance->setRenderingContext(new RenderingContextFixture());
        $result = $instance->render();
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider getRenderTestValuesWithFrontendEditionEnabled
     * @param mixed $value
     * @param array $arguments
     * @param string $expected
     */
    public function testRenderWithFrontendEditingEnabled($value, array $arguments, $expected)
    {
        ContentEditableFixtures::setAccessServiceEnabled(true);
        $instance = $this->getMock(CustomDropZoneViewHelper::class, ['renderChildren']);
        $instance->expects($this->once())->method('renderChildren')->willReturn($value);
        $instance->setArguments($arguments);
        $instance->setRenderingContext(new RenderingContextFixture());
        $result = $instance->render();
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function getRenderTestValuesWithoutFrontendEditionEnabled()
    {
        $fixtures = new ContentEditableFixtures();

        return [
            [
                'My content',
                [
                    'tables' => $fixtures->getCustomTables(),
                    'fields' => [],
                    'pageUid' => 0,
                    'prepend' => false
                ],
                $fixtures->getWrapWithCustomDropzoneExpectedContent('My content')
            ]
        ];
    }

    /**
     * @return array
     */
    public function getRenderTestValuesWithFrontendEditionEnabled()
    {
        $fixtures = new ContentEditableFixtures();

        return [
            [
                'My content',
                [
                    'tables' => $fixtures->getCustomTables(),
                    'fields' => [],
                    'pageUid' => 0,
                    'prepend' => false
                ],
                $fixtures->getWrapWithCustomDropzoneExpectedContent('My content')
            ]
        ];
    }
}
