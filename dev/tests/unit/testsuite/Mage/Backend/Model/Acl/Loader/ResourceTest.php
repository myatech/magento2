<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Mage_Backend
 * @subpackage  unit_tests
 * @copyright   Copyright (c) 2012 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Backend_Model_Acl_Loader_ResourceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Backend_Model_Acl_Loader_Resource
     */
    protected $_model;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $_configMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $_objectFactoryMock;

    public function setUp()
    {
        $this->_configMock = $this->getMock(
            'Mage_Backend_Model_Acl_Config', array('getAclResources'), array(), '', false
        );
        $this->_objectFactoryMock = $this->getMock('Mage_Core_Model_Config', array(), array(), '', false);
        $this->_model = new Mage_Backend_Model_Acl_Loader_Resource(array(
            'config' => $this->_configMock,
            'objectFactory' => $this->_objectFactoryMock
        ));
    }

    public function testPopulateAclAddsResourcesToAcl()
    {
        $this->_objectFactoryMock->expects($this->any())
            ->method('getModelInstance')
            ->with('Magento_Acl_Resource', $this->anything())
            ->will($this->returnCallback(
                function($class, $id) {
                    return new $class($id);
                }
            )
        );

        $resourcesDocument = new DOMDocument();
        $resourcesDocument->load(realpath(__DIR__)  .  '/../../_files/acl.xml');
        $xpath = new DOMXPath($resourcesDocument);

        $this->_configMock->expects($this->once())
            ->method('getAclResources')
            ->will($this->returnValue($xpath->query('/config/acl/resources/*')));

        $aclMock = $this->getMock('Magento_Acl');

        $aclMock->expects($this->exactly(5))
            ->method('addResource')
            ->with($this->isInstanceOf('Magento_Acl_Resource'));

        $this->_model->populateAcl($aclMock);
    }
}
