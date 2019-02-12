var KoolReport = KoolReport || {};
KoolReport.koolphp = KoolReport.koolphp || {};
KoolReport.koolphp.table = KoolReport.koolphp.table || function(name,options){
    this.options = options;
    this.name = name;
    this.events = {};
    this.initPaging();
    this.handleRemoveDuplicate();
    this.handleRowGroup();    
    this.initEvents();
};

KoolReport.koolphp.table.prototype = {
    options:null,
    name:null,
    events:null,
    initEvents:function()
    {
        $('#'+this.name+" tr").on('click',function(event){
            var rowIndex = $(event.currentTarget).attr("ri");
            var rowData = this.getRowData(rowIndex);
            this.fireEvent('rowClick',{table:this,rowData:rowData,rowIndex:rowIndex});
            this.fireEvent('rowSelect',{table:this,rowData:rowData,rowIndex:rowIndex});
        }.bind(this));
    },
    getRowData:function(index)
    {
        var data = [];
        $("#"+this.name+" tr[ri="+index+"] td").each(function(i,td){
            data.push($(td).attr("rv"));
            if(typeof this.options.cKeys[i] !="undefined")
            {
                data[this.options.cKeys[i]] = data[i];
            }
        }.bind(this));
        return data;
    },
    initPaging:function()
    {
        if(this.options.paging==null) return;
        var _nav = $('#'+this.name+' nav');
        _nav.empty();
        var _ul = $("<ul class='pagination'></ul>");
        _nav.append(_ul);

        //Previous button
        var _prev = $("<li class='page-item'><a class='page-link' href='#'>&laquo;</a></li>");
        if(this.options.paging.pageIndex<=0)
        {
            _prev.addClass("disabled");
            _prev.find("a").prop("href","javascript:void(0)");
        }
        else
        {
            _prev.find("a").prop("href","javascript:"+this.name+".goPage("+(this.options.paging.pageIndex-1)+")");
        }
        _ul.append(_prev);
        //Middle

        var start = Math.floor(this.options.paging.pageIndex/5)*5;
        var end = (start+5>this.options.paging.pageCount)?this.options.paging.pageCount:(start+5);

        if(start>0)
        {
            var _first = $("<li class='page-item'><a class='page-link'>1</a><li>");
            _first.find("a").prop("href","javascript:"+this.name+".goPage(0)");
            var _start = $("<li class='page-item'><a class='page-link'>...</a><li>");
            _start.find("a").prop("href","javascript:"+this.name+".goPage("+(start-5)+")");
            _ul.append(_first);
            _ul.append(_start);
        }

        for(var i=start;i<end;i++)
        {
            var _li = $("<li class='page-item'><a class='page-link'></a><li>");
            _li.find("a").text(i+1);
            if(i==this.options.paging.pageIndex)
            {
                _li.addClass("active");
                _li.find("a").prop("href","javascript:void(0)");
            }
            else
            {
                _li.find("a").prop("href","javascript:"+this.name+".goPage("+i+")");
            }
            _ul.append(_li);
        }
        if(end<this.options.paging.pageCount)
        {
            var _end = $("<li class='page-item'><a class='page-link'>...</a><li>");
            _end.find("a").prop("href","javascript:"+this.name+".goPage("+end+")");
            var _last = $("<li class='page-item'><a class='page-link'></a><li>");
            _last.find("a").text(this.options.paging.pageCount).prop("href","javascript:"+this.name+".goPage("+(this.options.paging.pageCount-1)+")");
            _ul.append(_end);
            _ul.append(_last);
        }


        //Next button
        var _next = $("<li class='page-item'><a class='page-link' href='#'>&raquo;</a></li>");
        if(this.options.paging.pageIndex >= this.options.paging.pageCount-1)
        {
            _next.addClass("disabled");
            _next.find("a").prop("href","javascript:void(0)");
        }
        else
        {
            _next.find("a").prop("href","javascript:"+this.name+".goPage("+(this.options.paging.pageIndex+1)+")");
        }
        _ul.append(_next);
    },
    goPage:function(pageIndex)
    {
        var oldIndex = this.options.paging.pageIndex;
        this.options.paging.pageIndex = pageIndex;
        this.initPaging();
        var oldStart = oldIndex*this.options.paging.pageSize;
        var newStart = pageIndex*this.options.paging.pageSize;
        var _root = $('#'+this.name);
        for(var i=0;i<this.options.paging.pageSize;i++)
        {
            _root.find("tr[ri="+(oldStart+i)+"]").hide();
            _root.find("tr[ri="+(newStart+i)+"]").show();
        }
        this.handleRowGroup();
        this.handleRemoveDuplicate();
        this.fireEvent("pageChanged",{pageIndex:pageIndex,oldPageIndex:oldIndex});
    },
    handleRowGroup:function()
    {
        if(this.options.paging==null)
        {
            return;
        }
        var _root = $('#'+this.name);
        var start = this.options.paging.pageIndex*this.options.paging.pageSize;
        var end = start+this.options.paging.pageSize-1;
        _root.find('tr.row-group:visible').hide();
        _root.find('tr.row-group').each(function(i,tr){
            var from = $(tr).attr('from');
            var to = $(tr).attr('to'); 
            if(from<=start && to>start)
            {
                $(tr).show();
            }
            else if(from<=end && to>end)
            {
                $(tr).show();
            }
            else if(from>=start && to<=end)
            {
                $(tr).show();
            }
        });
    },
    handleRemoveDuplicate:function()
    {
        var cNames = this.options.removeDuplicate;
        if(cNames==[]) return;
        var startRowIndex = 0;
        var endRowIndex = 0;
        var _root = $('#'+this.name);
        _root.find('td.dup').removeClass('dup');
        _root.find('td.s-dup').attr('rowspan',false).removeClass('s-dup');
        if(this.options.paging!==null)
        {
            startRowIndex = this.options.paging.pageIndex*this.options.paging.pageSize;
            endRowIndex = startRowIndex+this.options.paging.pageSize+1;
        }
        else
        {
            endRowIndex = _root.find("tbody")[0].childElementCount;
        }

        var cIndex = [];
        var currentValues = [];
        var currentRootTds = [];
        var currentRowSpan = [];
        this.options.cKeys.forEach(function(key,index){
            if(cNames.indexOf(key)>-1)
            {
                cIndex.push(index);
                currentValues.push(null);
                currentRootTds.push(null);
                currentRowSpan.push(1);
            }
        });

        for(var i=startRowIndex;i<endRowIndex;i++)
        {
            var tds = $("#"+this.name+" tr[ri="+i+"] td");
            cIndex.forEach(function(index){
                var cellValue = $(tds[index]).attr("rv");
                if(cellValue==currentValues[index])
                {
                    $(tds[index]).addClass("dup");
                    currentRowSpan[index]++;
                }
                else
                {
                    if(currentRowSpan[index]>1)
                    {
                        $(currentRootTds[index]).attr("rowspan",currentRowSpan[index]);
                    }
                    $(tds[index]).addClass("s-dup");
                    currentValues[index] = cellValue;
                    currentRootTds[index] = tds[index];
                    currentRowSpan[index] = 1;
                }
            });
        }
        cIndex.forEach(function(index){
            if(currentRowSpan[index]>1)
            {
                $(currentRootTds[index]).attr("rowspan",currentRowSpan[index]);
            }            
        });
    },
    on:function(name,func){
        if(typeof this.events[name] == "undefined")
        {
            this.events[name] = [];
        }
        this.events[name].push(func);
    },
    fireEvent:function(name,params)
    {
        if(typeof this.events[name] != "undefined")
        {
            for(var i in this.events[name])
            {
                this.events[name][i](params);
            }
        }        
    }
};
