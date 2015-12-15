<?php
/**
 *                  ___________       __            __
 *                  \__    ___/____ _/  |_ _____   |  |
 *                    |    |  /  _ \\   __\\__  \  |  |
 *                    |    | |  |_| ||  |   / __ \_|  |__
 *                    |____|  \____/ |__|  (____  /|____/
 *                                              \/
 *          ___          __                                   __
 *         |   |  ____ _/  |_   ____ _______   ____    ____ _/  |_
 *         |   | /    \\   __\_/ __ \\_  __ \ /    \ _/ __ \\   __\
 *         |   ||   |  \|  |  \  ___/ |  | \/|   |  \\  ___/ |  |
 *         |___||___|  /|__|   \_____>|__|   |___|  / \_____>|__|
 *                  \/                           \/
 *                  ________
 *                 /  _____/_______   ____   __ __ ______
 *                /   \  ___\_  __ \ /  _ \ |  |  \\____ \
 *                \    \_\  \|  | \/|  |_| ||  |  /|  |_| |
 *                 \______  /|__|    \____/ |____/ |   __/
 *                        \/                       |__|
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@totalinternetgroup.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@totalinternetgroup.nl for more information.
 *
 * @copyright   Copyright (c) 2015 Total Internet Group B.V. (http://www.totalinternetgroup.nl)
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
namespace TIG\Buckaroo\Test\Unit\Model\Config\Source;

use TIG\Buckaroo\Model\Config\Source\AllOrSpecificCountries;
use TIG\Buckaroo\Test\BaseTest;

class AllOrSpecificCountriesTest extends BaseTest
{
    /**
     * @var AllOrSpecificCountries
     */
    protected $object;

    public function setUp()
    {
        parent::setUp();

        $this->object = new AllOrSpecificCountries();
    }

    public function testToOptionArray()
    {
        $this->assertTrue(count($this->object->toOptionArray()) >= 2);

        $shouldHaveOptions = [
            __('All Allowed Countries'),
            __('Specific Countries')
        ];

        $result = $this->object->toOptionArray();
        foreach($shouldHaveOptions as $key => $option)
        {
            foreach($result as $optionContents)
            {
                if($optionContents['label']->getText() == $option->getText())
                {
                    unset($shouldHaveOptions[$key]);
                    break;
                }
            }
        }

        $this->assertEquals(0, count($shouldHaveOptions));
    }

    public function testToArray()
    {
        $options = $this->object->toArray();

        $this->assertEquals(__('All Allowed Countries'), $options[0]);
        $this->assertEquals(__('Specific Countries'), $options[1]);
    }
}