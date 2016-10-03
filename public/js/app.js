(function($, document, window){
    
    /* COMMON SHELL*/
    function Shell(options){
        
        var self = this,
            settings = {
                interface:{
                    'content':$('body>.container')
                }
            };

        /*PRIVATE*/
        function _construct(options){
            
            //Merge options
            settings = $.extend(settings,options||{});
            
            attach();
            
            //Return instance
            return self;
            
        }
        
        /*Prior binding*/
        function attach (){
          
            $(document).on("click", 'a[href][data-url]', function(){
                
                var link = $(this),
                    to = link.data();
                    to.callback = function(){
                    
                        /*Bootstrap navbar active highlight*/
                        if(link.parents('#navbar').size()>0){
                            var nav = link.parents('#navbar'),
                                sel = link.parents('li');
                            $('li',nav).removeClass('active');
                            sel.addClass('active');
                        }
                        
                        /*Bootstrap list-group active highlight*/
                        if(link.parents('.list-group').size()>0){
                            var list = link.parents('.list-group');
                            $('a[href][data-url]',list).removeClass('active');
                            link.addClass('active');
                        }
                        
                    };
                    
                self.navigate(to);
                
                
            });
            
        };
        
        /*PUBLIC*/
        self.navigate = function(where){
            
            var url = where.url||false,
                form = where.form||false,
                target = where.target||settings.interface.content,
                callback = where.callback||function(){},
                post = {};

            if(!url) {
                return false;
            }

            if(form){
                post = $(form).serializeArray();
            }

            $(target).load(url, post, callback);
            
        };

        return _construct(options);        
                
    }
    
    /*MIXER */
    function Mixer(options) {
        
        var self = this,
            settings = {};

        /*PRIVATE*/
        function _construct(options){
            
            //Merge options
            settings = $.extend(settings,options||{});
  
            //Return instance
            return self;
        }

        
        /*PUBLIC*/
        self.controls = function(device, callback){
            
            self.get(device, function(response){
                
                (callback||function(){})(response);
                
            });
            
        };        
        
        self.preferences = function (callback){
            
            $.post('/preferences/get', {}, function(preferences){
                
                callback(preferences);
                
            },'json');
            
        };       
        
        self.get = function (device, callback){
            
            $.post('/mixer/get', {'device': device}, function(controls){
                
                callback(controls);
                
            });
            
        };       
        
        self.set = function (data, callback){
            
            $.post('/mixer/set', data, function(response){
                
                callback(response);
                
            });            
            
        };      
        
        return _construct(options);
        
    };
    
    /* Construct class when DOM done loading */
    $(document).ready(function(){
        
      window.shell = new Shell(); 
      window.mixer = new Mixer(); 
      
    });
    
})($, document, window);