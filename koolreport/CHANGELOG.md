# Version 1.34.9
    1. Fixed Fix Google Chart due to change in core library.
    2. Add load() function for ArrayDataSource.
    3. Add `formatValue` column settings in `\koolreport\widgets\koolphp\Table`.

# Version 1.32.8 - July 5th, 2017
    1. Enhancment Table has align property for columns
    2. Enhancment Adding event OnInit and OnInitDone to KoolReport
    3. Enhancment Adding event OnBeforeSetup and OnSetup to KoolReport
    4. Enhancment Adding function params() in PDODataSource to set params for SQL statement.
    5. Enhancment Adding process Map which is versatile way to transform data.
    6. Fixed Solve issue of empty data source given to table.
    7. Fixed Solve the bug of missing row in ColumnsSort process.

# Version 1.27.6 - May 22th, 2017

    1. Enhancment Move the ExcelDataSource from the core to separate package to reduce the size of core.
    2. Enhancment Move MongoDataSource to separate package as well.
    3. Enhancment Add namespace 'clients' to contain most common used clients library such as jQuery, Bootstrap
    4. Enhancment Adding the colorScheme to Koolreport to manane color of charts and others.
    5. Enhancment We now can create theme for KoolReport
    6. Enhancment Enhance the Widget Asset Manager
    7. Enhancment Add functionpublishAssetFolder() to KoolReport.
    8. Enhancment Add the MySQLDataSource
    9. Enhancment Add the SQLSRVDataSource
    10. Enhancment Add ColumnsSort process to sort columns by name of label.
    11. Enhancment The Sort process now can sort by custom comparison function.
    12. Enhancment Add function debug() in KoolReport, this function will display all available data stores.
    13. Fixed Fix Google Chart initiation bug,this bug is actually due to the change from Google library.

# Version 1.15.4 - May 22th, 2017

    1. Enhancment Add ResourceManager to manage report's resources such as js, css and any others.
    2. Enhancment Improve the loading of Google Charts library with new ResourceManager to avoid loading redundancy.
    3. Enhancment Add event register and event handling mechanism.
    4. Enhancment Add OnBeforeRun,OnRunEnd,OnBeforeRender,OnRenderEnd event.
    5. Enhancment Allow to set full path for report's assets folder settings

# Version 1.11.4 - May 4th, 2017

    1. Enhancment Add ReportDataSource to pull data from another report
    2. Enhancment Allow to set "role" to columns for google chart
    3. Enhancment Filter process is now allowed or operator
    4. Enhancment Allow ValueMap to set custom function
    5. Enhancment Make Google Charts responsive to the change of screen size
    6. Enhancment Add saveTo() function to Node class
    7. Enhancment Enhance the mechanism of google chart library loader
    8. Enhancment Koolphp Table can remove duplicated value
    9. Enhancment Add popStart() and pop() function to DataStore class which helps to reduce memory usage
    10. Enhancment Enhance CSVDataSource and ExcelDataSource to reduce memory usage
    11. Enhancment Allow CalculatedColumn to add custom function and set meta data on the fly.
    12. Enhancment Make removeDuplicate of koolphp\Table be list of columns you want to remove duplicated data.
    13. Fixed Fix autoload.php bug in loading packages
    14. Fixed Fix OnlyColumn bug
    15. Fixed Fix koolphp's Table bug when column header is number
    16. Fixed Fix google charts duplicated chart id problem

# Version 1.0.0 - April 4th, 2017

    1. Establish middle-ware structure of KoolReport
    2. Build the most common datasource connectors
    3. Build the most common data processes
    4. Create PHP wrapper for Googe Charts library

# First brick - Jan 1st, 2017

    1. It was a nice day!
    2. Paper and pencil
    3. Two guys
    4. In a garden