var KoolReport = KoolReport || {};
KoolReport.load = KoolReport.load || {
    scripts:[],
    scriptCallbacks:[],
    links:[],
    linkCallbacks:[],
    doneCallbacks:[],
    resources:function(resources,cb)
    {
        if(resources.js)
        {
            this.js(resources.js,cb);
        }
        if(resources.css)
        {
            this.css(resources.css);
        }
    },
    js:function(sources,cb)
    {
        var now = sources.filter(function(value){
            return typeof(value)=="string";
        });
        var next = sources.filter(function(value){
            return typeof(value)=="object";
        });
        if(next.length==0)
        {
            this.scriptCallbacks.push(cb);
            now.forEach(function(src){
                this.registerScript(src);
            }.bind(this));
        }
        else
        {
            this.scriptCallbacks.push(function(){
                this.js(next[0],cb);
            }.bind(this));
            now.forEach(function(src){
                this.registerScript(src);
            }.bind(this));
        }
        this.checkScriptsAndCallback();
    },
    registerScript:function(src)
    {
        if(typeof this.scripts[src] == "undefined")
        {
            var existedTag = false;

            if(typeof(window.jQuery)!='undefined' && src.indexOf("/jquery.min.js")>=0)
            {
                existedTag = true;
            }

            if(existedTag==false)
            {
                var onPageScripts = document.getElementsByTagName("script");
                for(var i=0;i<onPageScripts.length;i++)
                {
                    if(onPageScripts[i].attributes["src"] && onPageScripts[i].attributes["src"].value==src)
                    {
                        existedTag = true;
                        break;
                    }
                }
            }

            if(existedTag)
            {
                this.scripts[src] = 1;
            }
            else
            {
                var script = document.createElement("script");
                script.type="text/javascript";
                script.src = src;
                script.onload = this.onScriptLoaded.bind(this);
                script.onerror = this.onScriptLoaded.bind(this);
                document.head.appendChild(script);
                this.scripts[src] = 0;    
            }
        }
    },
    onScriptLoaded:function(e)
    {
        this.scripts[e.target.attributes["src"].value] = 1;
        this.checkScriptsAndCallback();
    },
    checkScriptsAndCallback:function()
    {
        var allLoaded = true;
        for(var src in this.scripts)
        {
            allLoaded &= this.scripts[src];
        }
        
        if(allLoaded)
        {
            var runCbs = this.scriptCallbacks;
            this.scriptCallbacks = [];
            runCbs.forEach(function(cb){
                if(cb)
                {
                    try{cb();}
                    catch(err){console.log(err);}    
                }
            });
            setTimeout(this.checkOnDone.bind(this),2);
        }
    },
    onDone:function(cb)
    {
        this.doneCallbacks.push(cb);
        this.checkOnDone();
    },
    checkOnDone:function()
    {
        if(this.scriptCallbacks.length==0)
        {
            var doneCbs = this.doneCallbacks;
            this.doneCallbacks = [];
            doneCbs.forEach(function(cb){
                if(cb)
                {
                    try{cb();}
                    catch(err){console.log(err);}    
                }
            });
        }
    },

    css:function(hrefs)
    {
        var now = hrefs.filter(function(href){
            return typeof(href)=="string";
        });
        var next = hrefs.filter(function(href){
            return typeof(href)=="object";
        });

        if(next.length==0)
        {
            now.forEach(function(href){
                this.registerLink(href);
            }.bind(this));
        }
        else
        {
            this.linkCallbacks.push(function(){
                this.css(next[0]);
            }.bind(this));
            now.forEach(function(href){
                this.registerLink(href);
            }.bind(this));
        }
    },

    registerLink:function(href)
    {
        if(typeof this.links[href] == "undefined")
        {
            var links = document.getElementsByTagName("link");
            var found = false;
            for(var i=0;i<links.length;i++)
            {
                if(!found && links[i].attributes["href"] && links[i].attributes["href"].value==href)
                {
                    found = true;
                    break;
                }
            }
            if(found)
            {
                this.links[href] = 1;
            }
            else
            {
                var link = document.createElement("link");
                link.rel = 'stylesheet';
                link.type="text/css";
                link.href = href;
                link.onload = this.onLinkLoaded.bind(this);
                link.onerror = this.onLinkLoaded.bind(this);
                document.head.appendChild(link);
                this.links[href] = 0;
            }    
        }
    },
    onLinkLoaded:function(e)
    {
        this.links[e.target.attributes["href"].value] = 1;
        this.checkLinksAndCallback();
    },
    checkLinksAndCallback:function()
    {
        var allLoaded = true;
        for(var href in this.links)
        {
            allLoaded &= this.links[href];
        }
        if(allLoaded)
        {
            var runCbs = this.linkCallbacks;
            this.linkCallbacks = [];
            runCbs.forEach(function(cb){
                try{cb();}
                catch(err){console.log(err);}
            });
        }
    }
};

KoolReport.widget = (KoolReport.widget)?KoolReport.widget:{
    init:function(resources,cb)
    {
        KoolReport.load.resources(resources,cb);
    }
};