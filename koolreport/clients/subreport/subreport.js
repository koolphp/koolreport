if(typeof subReport =="undefined")
{
    var subReport = {
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
            this.fireEvent("updating",data);
            $.ajax({
                method:"POST",
                data:data,
            }).done(function(content){
                $("sub-report#"+name).html(content);
                this.fireEvent("updated",{"@supReport":name});
                this.fireEvent("done",{"@supReport":name});
            }.bind(this))
            .fail(function(e){
                this.fireEvent("error",{"event":e,"@supReport":name});
                this.fireEvent("done",{"@supReport":name});
            }.bind(this));
        },
        on(name,func)
        {
            if(typeof this.events[name] == "undefined")
            {
                this.events[name] = [];
            }
            this.events[name].push(func);
        },
        fireEvent(name,params=null)
        {
            if(typeof this.events[name] !="undefined")
            {
                for(var i in this.events[name])
                {
                    this.events[name][i](params);
                }
            }
        }
    };
}
