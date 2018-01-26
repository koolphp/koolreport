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
            if(this.fireEvent("updating",data))
            {
                $.ajax({
                    method:"POST",
                    data:data,
                }).done(function(content){
                    var start_mark = "<!--subreport-start-->";
                    var end_mark = "<!--subreport-end-->";
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
                    if(this.events[name][i](params)==false)
                    {
                        return false;
                    }
                }
            }
            return true;
        }
    };
}
