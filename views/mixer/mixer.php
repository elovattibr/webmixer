<div id="mixer" data-device="<?php echo $this->device ?>">

    <h3 class="mixer_name" style="text-align: center;"></h3>
    
    <div class="render_controls"></div>

    <script type="text/html" id="tpl_control">

        <div class="col-xs-{{:preferences.columns}}">

            <div class="panel panel-default">
                
                <div class="panel-heading">
                    {{if switch}} 
                        <div class="btn-group" data-toggle="buttons"  style="display: inline;">
                            <input type="checkbox" name="switch" data-id="{{:switch.id}}" {{if switch.values[0] == true}} checked {{/if}}/>  
                        </div>                        
                    {{/if}}
                    <h3 class="panel-title" style="display: inline;">{{:name}}</h3>
                    <a class="pull-right bt-customize-control" data-id="{{:id}}" data-name="{{:name}}" style="cursor: pointer;"><span class="glyphicon glyphicon-cog" style="display: inline;"></span></a>
                </div>

                {{if source}}
                    <div class="panel-body">
                        <div class="btn-group" data-toggle="buttons" style="margin: 10px;">
                            {{for source.items}}
                              <label class="btn  btn-sm btn-primary {{if checked == true}}active{{/if}}">
                                <input type="radio" 
                                       name="source[{{:source}}]" 
                                       data-id="{{:source}}"
                                       value="{{:index}}" {{if checked == true}} checked {{/if}}/> 
                                {{:description}}
                              </label>
                            {{/for}}
                        </div>                        
                    </div>
                {{/if}}

                {{if volume}}
                    <div class="panel-body">
                        {{for volume.channels}}
                            <div class="slider" title="{{:name}}" data-id="{{:id}}" data-channel="{{:channel}}" data-min="{{:min}}" data-max="{{:max}}" data-step="{{:step}}" data-value="{{:current}}" style="margin: 10px;"></div>
                        {{/for}}
                    </div>
                {{/if}}
                
            </div>    

        </div>    

    </script>
    
</div>

<script>
$(function(){
  
    var page = $("#mixer"),
        device = page.data('device'),
        controls = $('.render_controls',page),
        template = $.templates("#tpl_control");
        
    function bind(preferences, total){
        
        $(".slider", controls).each(function(){

            var slide = $(this),
                data = slide.data(),
                options = $.extend({
                    orientation: preferences.orientation,
                    slide: function (event, ui) {

                        var values = []; 
                        
                        $('.slider[data-id='+data.id+']', controls).each(function(){
                            values.push( $(this).slider("value") );
                        });

                        mixer.set({device:device, id:data.id, channel:data.channel, value: values.join(",")}, function(response){
                            console.log("Response", response);
                        });
                        
                    }
                }, data);

            slide.slider(options);

        });

        $(':input', controls).on({

            change:function(){

                var post = {
                    device:device,
                    id: $(this).data('id'),
                    channel: $(this).data('channel'),
                    value: null
                };

                if($(this).is(":checkbox")){
                    post.value = $(this).prop('checked')?"on":"off";
                } else {
                    post.value = $(this).val();
                }

                mixer.set(post, function(response){

                    console.log("Response", response);
                    
                });
            }

        });   

        $('.customize-modal',page).on({
            
            'init':function(){
                $(this).modal('hide');
            },
            'show':function(e, data){
                
                
                
                $(this).modal('show');
                
            },
            'hide':function(){
                $(this).modal('close');
            },
            
        }).trigger('init');
        
        $('.bt-customize-control',controls).on({
            click:function(){
                
                var bt = $(this),
                    id = bt.data('id');
            
                $('.customize-modal',page).trigger('show', {
                    'id':id
                });
                
            }
        });        
    }
        
    mixer.groups(device, function(response){
        
        controls.append( template.render(response.master) );

        bind(response.preferences);
        
    });
  
});  
</script>