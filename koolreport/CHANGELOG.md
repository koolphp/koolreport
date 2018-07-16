# Version 2.78.0
    1. Utility: Fix jsonEncode() to work with array contains javascript function
    2. DataSource: Improve the parameter escape string
    3. DataSource: Make `MySQLDataSource`, `PostgreSQLDateaSource`, `SQLSRVDataSource`, `OracleDataSource` share database connection to reduce response time and memory.
    4. GoogleChart: When you user select item on chart, the selectedRow now can contain associate value beside the array of values.
    5. Table: Return both array and associate rowData on the rowClick event.

# Version 2.75.0
    1. Table: Add `responsive` property to Table widget
    2. GoogleChart: Add `formatValue` to column so that user can do custom value format.
    3. GoogleChart: Make width of GoogleChart default 100%
    4. SubReport: Enhance the partial render
    5. PdoDataSource: Fix issue with Oracle
    6. KoolReport: Reduce reload settings() by saving to $reportSettings
    7. KoolReport: src() will take the first datasource if name is not specify 
    8. Utility: Add advanced jsonEncode() function to enable js function definition inside php array.
    9. Adding version factor to KoolReport as well as its widget so that everytime we upgrade core libray as well as package, the widget is able to create new assets folder with updated resource
    10. Fix several minor bug
    11. Adding `DifferenceColumn` process to calculate the difference between row and the previous one.
    12. Adding `AppendRow` process to add custom row to data flow.
     
# Version 2.43.0
    1. Google Chart:Fix issue with GoogleChart when working with numeric column but in string form.


# Version 2.42.0
    1. Change namespace `::class` to use classname in string so that `KoolReport` is compatible with PHP 5.4
    2. `PdoDataSource`: Fix `charset` issue with `PostgreSQL`

# Version 2.41.3
    1. Adding `AccumulativeColumn` to generate accumulative column.
    2. Fix Group process for not counting records correctly.
    3. Enhance the `autoload` of KoolReport
    4. A bundle of small fixes

# Version 2.32.8
    1. Revert back to previous param binding of PDODataSource


# Version 2.31.7
    1. Fix the bug of incorrect active report when subReport is used.
    2. Widget: Adding default `dataSource` and backward `dataStore` property.
    3. Widget: Able to set dataStore object, array data and even the adhoc process.
    4. Table: Remove the `data` property and start using the `dataSource`
    5. GoogleCharts: Remove `data` property and start using `dataSource` instead
    6. DataStore: Adding `requestDataSending()` to manually request data piping from source.
    7. Adding events `OnBeforeResourceAttached` and `OnResourceAttached`
    8. Table: Add ability to set multilevel group headers.
    9. PdoDataSource: Adding SQL Query error handling
    10. CopyColumn: Change input format from `"orginal"=>"copy"` to `"copy"=>"orginal"`
    11. DataStore: Function process() can accept a series of piping processes.
    12. GoogleCharts: Add property `pointerOnHover` to set whether pointer will be used when user holds mouse over the item of chart.
    13: GoogleCharts: Automatic set `pointerOnHover=>true` if there is `"itemSelect"` event is registered.
    14. Table: Change "goPage" event to "pageChanged" event
    15. Added `ColumnRename` process to rename column
    16. Process: Adding static function `process()`

Note for packages:
- Remove the $dataStore property


# Version 2.0.0
    1. DataStore: Add process() function to further process data
    2. PdoDataSource: Update the bindParams() function.
    3. Table: Handle the case when there is no data
    4. Table: Show custom messages
    5. Widget: Able to load language/localization
    6. PdoDataSource, MySQLDataSource, SQLSRVDataSource: Update parameter binding.
    7. Add ability to contain sub report, supporting partial report rendering.
    8. Widget: Enhance the template() function
    9. Google Charts: Rewrite library to support ajax loading and work well with SubReport
    10. Table: Support ajax loading.
    11. Table: Adding client event handler capability.

# Version 1.72.8
    1. DataStore: Fix the `get()` function
    2. TimeBucket: Change month bucket format from `Y-n` to `Y-m` to support sorting.
    3. DataStore: Add $offset parameter to the top() function.
    4. DataStore: Add function min(), max(), sum(), avg()
    5. Make KoolReport run debug() if could not find the view file.
    6. Filter: Add `"in"` and  `"notIn"` operator 
    7. DataStore::filter() Add "startWith","notStartWith", "endWith" and "notEndWith"
    8. CalculatedColumn: Add row number column with key `{#}`
    9. Table: New feature of pagination

# Version 1.61.5
    1. Fix parameters bug in PDODataSource
    2. Fix parameters bug in MySQLDataSource
    3. Fix parameters bug in SQLSRVDataSource

# Version 1.61.2
    1. Add html() method to Widget to allow return html of widget
    2. Add $return to create() method of Widget to return html instead of echo html.
    2. Add innerView() to KoolReport to allow rendering sub view
    3. Add function get() to DataStore to get any value in table
    4. Make Transpose process take the label as data if the label of column is available.
    4. Fix the isEnd() function of Node
    5. Fix Group to allow multiple sources to pipe to group process.
    6. Return content on event `"OnRenderEnd"`
    7. Allow cancel rendering using `"OnBeforeRender"` event
    8. Add previous() function to Node for navigation
    9. Fix the ProcessGroup to enable transferring data smoothly.
    10. ResourceManager now will fire the OnResourceInit event
    11. Table has `data` property to input data on fly.
    12. Google chart has `data` property to input data on fly.
    13. Add `filter()` function to DataStore to filter data base on condition.
    14. Add `top()` and `topByPercent()` function to DataStore to get the top rows
    15. Add `bottom()` and `bottomByPercent()` function to DataStore to get the bottom rows
    16. Add `sort()` function to DataStore to get data sorted.
    17. Add `"footer"=>"count"` to `Table` column settings.

# Version 1.47.3
    1. New `AggregatedColumn` process
    2. Table is now able to show footer
    3. Make footer show on top with `showFooter` property
    4. Add `footerText` property
    5. Add `showHeader` to `Table` widget
    6. Ability to set `cssStyle` for each columns. `cssStyle` can be string or array containing components `td`,`th` and `tf`.
    7. Improve `DataSource` class
    8. New process `Transpose` to tranpose column and row of data flow
    9. Fix double quote issue of `PDODataSource`
    10. The Node now has getReport() function which return the report.
    11.Fix the Timeline google charts
    12. Fix the Group process by removing the space in column name 
    13. Add params() function to MySQLDataSource and MSSQLDataSource 

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