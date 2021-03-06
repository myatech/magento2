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
 * @category   Magento
 * @package    tools
 * @copyright  Copyright (c) 2012 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Db migration logger. Output result put to file
 */
class Tools_Migration_Acl_Db_Logger_File extends Tools_Migration_Acl_Db_LoggerAbstract
{
    /**
     * Path to log file
     *
     * @var string
     */
    protected $_file = null;

    public function __construct($file)
    {
        $logDir = realpath(__DIR__ . '/../../') . '/log/';
        if (false == is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        if (false == is_writeable($logDir)) {
            throw new InvalidArgumentException('Directory ' . dirname($logDir) . ' is not writeable');
        }

        if (empty($file)) {
            throw new InvalidArgumentException('Log file name is required');
        }
        $this->_file = $logDir . $file;
    }

    /**
     * Put report to file
     */
    public function report()
    {
        file_put_contents($this->_file, (string)$this);
    }
}

