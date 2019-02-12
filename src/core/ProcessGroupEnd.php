<?php
/**
 * This file contains class for group end process
 * 
 * This class is supporting the ProcessGroup class
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
namespace koolreport\core;

/**
 * Class for group end process
 * 
 * This class is supporting the ProcessGroup class
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class ProcessGroupEnd extends Process
{
    /**
     * Receiving input data
     * 
     * @param array $data Data to be received
     * 
     * @return null
     */
    public function onInput($data)
    {
        $this->params->inputFromEndProcess($data);
    }

    /**
     * Receiving meta data
     * 
     * @param array   $metaData MetaData to be received
     * @param Process $source   The sending process
     * 
     * @return null
     */
    public function receiveMeta($metaData,$source)
    {
        $this->params->metaFromEndProcess($metaData);
    }
}
