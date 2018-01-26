if(typeof KoolPHPTable =="undefined")
{
    function KoolPHPTable(name,options)
    {
        this.options = options;
        this.name = name;
        if(this.options.paging!==null)
        {
            this.initPaging();
        }
        this.initEvents();    
    }
    KoolPHPTable.prototype = {
        options:null,
        name:null,
        events:{},
        initEvents:function()
        {
            $('#'+this.name+" tr").on('click',function(event){
                var rowIndex = $(event.currentTarget).attr("row-index");
                this.fireEvent('rowClick',{table:this,data:this.getRowData(rowIndex),rowIndex:rowIndex});
            }.bind(this));
        },
        getRowData:function(index)
        {
            var data = [];
            $("#"+this.name+" tr[row-index="+index+"] td").each(function(i,td){
                data.push($(td).attr("row-value"));
            });
            return data;
        },
        initPaging:function()
        {
            var _nav = $('#'+this.name+' nav');
            _nav.empty();
            var _ul = $("<ul class='pagination'></ul>");
            _nav.append(_ul);
    
            //Previous button
            var _prev = $("<li><a href='#'>&laquo;</a></li>");
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
                var _first = $("<li><a>1</a><li>");
                _first.find("a").prop("href","javascript:"+this.name+".goPage(0)");
                var _start = $("<li><a>...</a><li>");
                _start.find("a").prop("href","javascript:"+this.name+".goPage("+(start-5)+")");
                _ul.append(_first);
                _ul.append(_start);
            }
    
            for(var i=start;i<end;i++)
            {
                var _li = $("<li><a></a><li>");
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
                var _end = $("<li><a>...</a><li>");
                _end.find("a").prop("href","javascript:"+this.name+".goPage("+end+")");
                var _last = $("<li><a></a><li>");
                _last.find("a").text(this.options.paging.pageCount).prop("href","javascript:"+this.name+".goPage("+(this.options.paging.pageCount-1)+")");
                _ul.append(_end);
                _ul.append(_last);
            }
    
    
            //Next button
            var _next = $("<li><a href='#'>&raquo;</a></li>");
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
                _root.find("tr[row-index="+(oldStart+i)+"]").hide();
                _root.find("tr[row-index="+(newStart+i)+"]").show();
            }
            this.fireEvent("pageChanged",{pageIndex:pageIndex,oldPageIndex:oldIndex});
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
}
