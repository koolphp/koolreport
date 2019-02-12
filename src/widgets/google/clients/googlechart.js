var KoolReport = KoolReport || {};
KoolReport.google = KoolReport.google || {};
KoolReport.google.chart = KoolReport.google.chart||
function(chartType,chartId,cKeys,data,options,loader)
{
    this.chartType = chartType;
    this.chartId = chartId;
    this.data = data;
    this.options = options;
    this.events = {};
    this.cKeys = cKeys;
    this.loader = loader;

    if(typeof google !="undefined" && typeof google.charts !="undefined" )
    {   
        this.loadPackage();
    }
    else
    {        
        // Load the loader.js
        this.loadLoader();
    }
    $(window).on('resize',this.redraw.bind(this));
};
KoolReport.google.chart.prototype = {
    options:null,
    dataTable:null,
    chartId:null,
    chartType:null,
    events:null,
    chart:null,
    data:null,
    loader:null,
    pointerOnHover:false,
    loadLoader:function()
    {
        if($('#googlechart-loader').length == 0)
        {
            //First start, adding script and load event
            var script = document.createElement("script");
            script.id="googlechart-loader";
            script.type="text/javascript";
            script.src = "https://www.gstatic.com/charts/loader.js";
            $(script).on("load",this.loadPackage.bind(this));
            document.head.appendChild(script);
            
        }
        else
        {
            //Already on page but have not run,
            //Attach the load event
            $('#googlechart-loader').on("load",this.loadPackage.bind(this));
        }
    },
    loadPackage:function()
    {
        
        if(typeof google.visualization !="undefined" && typeof google.visualization[this.chartType] !="undefined")
        {
            this.init();
        }
        else
        {
            //Load the package
            google.charts.load(this.loader.stability,{
                packages:[this.loader.package],
                mapsApiKey:this.loader.mapsApiKey}
            );
            google.charts.setOnLoadCallback(this.init.bind(this));
        }
    },
    init:function()
    {
        this.chart = new google.visualization[this.chartType](document.getElementById(this.chartId));
        
        google.visualization.events.addListener(this.chart, 'select', function(){
            var selection = this.chart.getSelection();
            for (var i = 0; i < selection.length; i++)
            {
                var item = selection[i];
                if(item.row!=null)
                {
                    var selectedRow = [];
                    var numColumns = this.dataTable.getNumberOfColumns();
                    for(var i=0;i<numColumns;i++)
                    {
                        selectedRow.push(this.dataTable.getValue(item.row,i));
                        if(typeof(this.cKeys[i])!="undefined")
                        {
                            selectedRow[this.cKeys[i]] = selectedRow[i];
                        }
                    }
                }
                if (item.row != null && item.column != null)
                {
                    this.fireEvent("itemSelect",{
                        "selectedRowIndex":item.row,
                        "selectedColumnIndex":item.column,
                        "selectedValue":this.dataTable.getValue(item.row,item.column),
                        "selectedRow":selectedRow,
                        "columnName":this.dataTable.getColumnLabel(item.column),
                        "table":this.dataTable,
                    });
                }
                else if(item.row !=null)
                {
                    this.fireEvent("rowSelect",{
                        selectedRow:selectedRow,
                        table:this.dataTable,
                        selectedRowIndex:item.row,
                    });
                }
                else if(item.column!=null)
                {
                    this.fireEvent("columnSelect",{
                        selectedColumnIndex:item.column,
                        table:this.dataTable,
                        columnName:this.dataTable.getColumnLabel(item.column),
                    });
                }
                this.fireEvent("select",{
                    selectedRowIndex:item.row,
                    selectedColumnIndex:item.column,
                    table:this.dataTable,
                });
            }
        }.bind(this));
        if(this.pointerOnHover)
        {
            this.addPointerCss();
        }
        this.draw();
    },
    addPointerCss:function()
    {
        google.visualization.events.addListener(this.chart, 'onmouseover', function(){
            $("#"+this.chartId).css('cursor','pointer');
        }.bind(this));
        google.visualization.events.addListener(this.chart, 'onmouseout', function(){
            $("#"+this.chartId).css('cursor','default');
        }.bind(this));
    },
    draw:function(data)
    {
        if(data)
        {
            this.data = data;
        }
        this.dataTable = new google.visualization.arrayToDataTable(this.data);
        if (this.options.showColumns) {
            var view = new google.visualization.DataView(this.dataTable);
            view.setColumns([0].concat(this.options.showColumns));
            this.chart.draw(view, this.options);
        }
        else
        {
            this.chart.draw(this.dataTable,this.options);
        }
    },
    redraw:function()
    {
        if (this.options.showColumns) {
            var view = new google.visualization.DataView(this.dataTable);
            view.setColumns([0].concat(this.options.showColumns));
            this.chart.draw(view, this.options);
        }
        else
        {
            this.chart.draw(this.dataTable,this.options);
        }
    },
    registerEvent:function(name,handler)
    {
        if(typeof this.events[name]=='undefined')
        {
            this.events[name] = [];
        }
        this.events[name].push(handler);
    },
    fireEvent:function(name,params)
    {
        if(typeof this.events[name]!='undefined')
        {
            for(var i in this.events[name])
            {
                this.events[name][i](params);
            }
        }
    }
};