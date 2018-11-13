var KoolReport = KoolReport || {};

KoolReport.subReport = KoolReport.subReport || {
    events:{},
    update:function(name,data)
    {
        if(typeof(jQuery) == 'undefined')
        {
            console.error("subReport requires jQuery to run");
            return;
        }
        if(!data)
        {
            data = {};
        }
        data["@subReport"] = name;
        if(this.fireEvent("updating",data))
        {
            $.ajax({
                method:"POST",
                data:data,
            }).done(function(content){
                var start_mark = "<subreport-partial>";
                var end_mark = "</subreport-partial>";
                content = content.substring(content.indexOf(start_mark)+start_mark.length,content.indexOf(end_mark));
                $("sub-report#"+name).html(content);
                this.fireEvent("updated",{"@supReport":name});
                this.fireEvent("done",{"@supReport":name});
            }.bind(this))
            .fail(function(e){
                this.fireEvent("error",{"event":e,"@supReport":name});
                this.fireEvent("done",{"@supReport":name});
            }.bind(this));    
        }
    },
    on:function(name,func)
    {
        if(typeof this.events[name] == "undefined")
        {
            this.events[name] = [];
        }
        this.events[name].push(func);
    },
    fireEvent:function(name,params)
    {
        if(typeof params =="undefined")
        {
            params = null;
        }
        if(typeof this.events[name] !="undefined")
        {
            for(var i in this.events[name])
            {
                if(this.events[name][i](params)==false)
                {
                    return false;
                }
            }
        }
        return true;
    }
}; 

var subReport = KoolReport.subReport;
