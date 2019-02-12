<?php
/**
 * This file contains class to break the time series and put them in defined buckets.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
 * ->pipe(new TimeBucket(array(
 *         "registerDate"=>array(
 *             "bucket"=>"date", //"week","month","year","quarter"
 *             "formatString"=>"{year}-{month}-{day}"
 *         )
 * )))
 *
 * ->pipe(new TimeBucket(array(
 *         "registerDate"=>"quarter"
 * )))
 *
 * hourofday: 0-23 {hour}
 * dayofweek: 0-7 {weekday}
 * dayofmonth: 1-31 {day}
 * date: 2011-12-12 {year}-{month}-{day}
 * week: 2011-34 {year}-{week}
 * quater: 2011-4 {year}-{quater}
 * month: 2011-12 {year}-{month}
 * monthofyear: {}
 * year:2011 {year}
 *
 */
namespace koolreport\processes;

use \koolreport\core\Process;
use \koolreport\core\Utility;

/**
 * This file contains class to break the time series and put them in defined buckets.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class TimeBucket extends Process
{

    /**
     * Handle on initiation
     *
     * @return null
     */
    protected function onInit()
    {
        foreach ($this->params as $cName => $cParams) {
            if (gettype($cParams) == "array") {
                $bucket = Utility::get($cParams, "bucket");
                $formatString = Utility::get($cParams, "formatString");
                $this->params[$cName] = array(
                    "bucket" => $bucket,
                    "formatString" => $formatString ? $formatString : $this->defaultFormatString($bucket),
                );
            } else {
                //cParams now is bucket
                $this->params[$cName] = array(
                    "bucket" => $cParams,
                    "formatString" => $this->defaultFormatString($cParams),
                );

            }
        }
    }

    /**
     * Get the default format string
     * 
     * @param string $bucket The bucket type
     * 
     * @return string The default format string
     */
    protected function defaultFormatString($bucket)
    {
        $map = array(
            "year" => "Y",
            "month" => "Y-m",
            "quarter" => "Y-Q{q}",
            "week" => "Y-W",
            "date" => "Y-m-d",
            "dayofmonth" => "d",
            "dayofweek" => "w",
            "hourofday" => "H",
            "monthofyear" => "m",
        );
        return ($map[$bucket]) ? $map[$bucket] : "[invalid bucket]";
    }

    /**
     * Handle on meta received
     * 
     * @param array $metaData The meta data
     * 
     * @return array New meta data
     */
    protected function onMetaReceived($metaData)
    {
        foreach ($this->params as $cName => $cParams) {
            $metaData["columns"][$cName] = array(
                "type" => "string",
            );
        }
        return $metaData;
    }

    /**
     * Handle on data input
     *
     * @param array $data The input data row
     *
     * @return null
     */
    protected function onInput($data)
    {
        //Process data here
        foreach ($this->params as $cName => $cParams) {
            if ($data[$cName]) {
                //Only if the value of column is not empty
                $format = Utility::get($this->metaData["columns"][$cName], "format");

                $datetime = ($format) ? \DateTime::createFromFormat($format, $data[$cName]) : new \DateTime($data[$cName]);

                $data[$cName] = $datetime->format($cParams["formatString"]);
                if ($cParams["bucket"] == "quarter") {
                    $data[$cName] = Utility::strReplace(
                        $data[$cName],
                        array(
                            "{q}" => ceil(intval($datetime->format("n")) / 3),
                        )
                    );
                }
            }
        }
        $this->next($data);
    }
}
