<?php

require_once "../../../../koolreport/autoload.php";

use \koolreport\KoolReport;

class OrderList extends KoolReport
{
    use \koolreport\clients\Bootstrap;
    use \koolreport\inputs\Bindable;
    use \koolreport\inputs\POSTBinding;

    protected function defaultParamValues()
    {
        return array(
            "dateRange"=>array(
                "2003-01-01",
                "2003-01-31"
            ),
            "customers"=>array(),
        );
    }

    protected function bindParamsToInputs()
    {
        return array(
            "dateRange"=>"dateRange",
            "customers"=>"customers",
        );
    }

    public function settings()
    {
        $config = include "../../../config.php";
        return array(
            "dataSources"=>array(
                "automaker"=>$config["automaker"]
            )
        );
    }   
    protected function setup()
    {
        $this->src('automaker')
        ->query("
            SELECT
                customers.customerName,
                orders.orderNumber,
                products.productName,
                orderdetails.quantityOrdered*orderdetails.priceEach as amount,
                orders.orderDate,
                orders.status
            FROM 
                orders
            JOIN 
                customers
            ON 
                customers.customerNumber = orders.customerNumber
            ".
            (($this->params["customers"]!=array())?"AND customers.customerNumber IN (:customers)":"")
            ."
            JOIN 
                orderdetails
            ON 
                orders.orderNumber = orderdetails.orderNumber
            JOIN 
                products
            ON
                products.productCode = orderdetails.productCode
            WHERE
                orderDate > :start
                AND
                orderDate < :end
        ")
        ->params(array(
            ":start"=>$this->params["dateRange"][0],
            ":end"=>$this->params["dateRange"][1],
            ":customers"=>$this->params["customers"]
        ))
        ->pipe($this->dataStore("result"));

        $this->src("automaker")->query("
            SELECT
                customerNumber,
                customerName
            FROM
                customers
            ORDER BY customerName
        ")
        ->pipe($this->dataStore("customers"));
    } 
}
