if(typeof googleChartLoader === "undefined")
{
    function GoogleChart(chartType,chartId,data,options)
    {
        this.chartType = chartType;
        this.chartId = chartId;
        this.data = data;
        this.options = options;
        googleChartLoader.callback(function(){
            google.charts.setOnLoadCallback(this.init.bind(this));
            window.addEventListener('resize',this.draw.bind(this));    
        }.bind(this));
    }

    GoogleChart.prototype = {
        options:null,
        dataTable:null,
        chartId:null,
        chartType:null,
        events:{},
        chart:null,
        data:null,
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
                        }
                    }
                    if (item.row != null && item.column != null)
                    {                    
                        this.fireEvent("onItemSelect",{
                            "selectedRowIndex":item.row,
                            "selectedColumnIndex":item.column,
                            "selectedValue":this.dataTable.getValue(item.row,item.column),
                            "selectedRow":selectedRow,
                            "columnName":this.dataTable.getColumnLabel(item.column),
                            "table":this.dataTable,
                        });
                    }
                }
            }.bind(this));
            this.addPointerCss();
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
        draw:function()
        {
            this.dataTable = new google.visualization.arrayToDataTable(this.data);
            this.chart.draw(this.dataTable,this.options);
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
    var googleChartLoader = {
        funcs: new Array(),
        load:function(stability,package)
        {
            if(typeof google =="undefined")
            {
                $.getScript('https://www.gstatic.com/charts/loader.js',function(){
                    google.charts.load(stability, {'packages':[package]});
                    for(var i in this.funcs)
                    {
                        this.funcs[i]();
                    }
                    this.funcs = new Array();
                }.bind(this));
            }
            else
            {
                google.charts.load(stability, {'packages':[package]});
            }
        },
        callback:function(func)
        {
            if(typeof google == "undefined")
            {
                this.funcs.push(func);
            }
            else
            {
                func();
            }
        }
    };
}