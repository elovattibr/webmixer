<div id="mixer" data-device="<?php echo $this->device ?>">
    
    <div class="render_controls"></div>
    
</div>

<script type="text/html" id="tpl_control">
    
    {{if preferences.mode == "mixer"}}
        <table class="table">
            <thead>
                <th colspan="4" style="text-align: center; font-size: 140%;">{{:preferences.label}}</th>
            </thead>
            <tbody>
            {{for controls}}
                <tr>
                    <td align="center">
                        {{if switch}} 
                            <div class="btn-group" data-toggle="buttons">
                                <input type="checkbox" name="switch" data-id="{{:switch.id}}" {{if switch.values[0] == true}} checked {{/if}}/>  
                            </div>                        
                        {{/if}}
                    </td>
                    <td>{{:name}}</td>
                    <td align="center" valign="middle" style="padding: 0;">
                        {{if source}}
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
                        {{/if}}

                        {{if volume}}
                            {{for volume.channels}}
                                <div class="control" style="display: block;">
                                    <div class="col-xs-1">
                                        <div class="control-level" style="margin-top: 8px; font-size: 80%; color: gray;"></div>                                            
                                    </div>
                                    <div class="col-xs-11">
                                        <div class="slider" title="{{:name}}" data-id="{{:id}}" data-channel="{{:channel}}" data-min="{{:min}}" data-max="{{:max}}" data-step="{{:step}}" data-value="{{:current}}" style="margin: 10px; "></div>
                                    </div>
                                </div>
                            
                            {{/for}}
                        {{/if}}
                    </td>
                    <td style="text-align: center;">
                        <a class=" bt-customize-control" data-id="{{:id}}" data-name="{{:name}}" style="cursor: pointer;"><span class="glyphicon glyphicon-cog" style="display: inline;"></span></a>
                    </td>
                </tr>
            {{/for}} 
            </tbody>
        </table>            
    {{/if}} 

    {{if preferences.mode == "equalizer"}}
    
        <h2>{{:preferences.label}}</h2>
    
        {{for controls}}
            <div class="col-xs-{{:preferences.columns}}" style="padding: 0; margin: 0; margin-left: 10px;">
                <div class="panel panel-default" >
                    <div class="panel-heading" style="text-align: center; padding-left:2px; padding-right: 2px; white-space: nowrap; overflow: hidden;">
                        <small>{{:name}}</small>
                    </div>
                    {{if volume}}
                        <div class="panel-body">
                            <div class="row control">
                                {{for volume.channels}}
                                    <div class="col-xs-6 " style="padding: 0; margin: 0; ">
                                        <div class="slider" title="{{:name}}" data-id="{{:id}}" data-channel="{{:channel}}" data-min="{{:min}}" data-max="{{:max}}" data-step="{{:step}}" data-value="{{:current}}" style="margin: 10px;"></div>
                                        <div class="control-level" style="font-size: 80%; text-align: center;"></div>                                            
                                    </div>
                                {{/for}}
                            </div>
                        </div>
                    {{/if}}
                    <!--<a class="bt-customize-control" data-id="{{:id}}" data-name="{{:name}}" style="cursor: pointer;"><span class="glyphicon glyphicon-cog"></span></a>-->
                </div>    
            </div>
        {{/for}} 
    {{/if}} 
</script>

<script>
$(function(){
  
    var page = $("#mixer"),
        device = page.data('device'),
        controls = $('.render_controls',page),
        template = $.templates("#tpl_control");
  
    function setControl(data, ui){
        
        var values = []; 

        $('.slider[data-id='+data.id+']', controls).each(function(){
            values.push( $(this).slider("value") );
        });

        mixer.set({device:device,id:data.id, channel:data.channel, value: values.join(",")}, function(response){
            console.log("Response", response);
        });
        
    }
    
    function bind(preferences){
        
        $(".slider", controls).each(function(){
            var slide = $(this),
                level = slide.parents(".control").find(".control-level"),
                data = slide.data(),
                options = $.extend({
                    orientation: preferences.orientation,
                    create: function() {
                        level.text( slide.slider( "value" )  + "dB" );
                    },
                    slide: function (event, ui) {
                        
                        level.text( ui.value + "dB" );
                        setControl(options, ui);
                        
                    },
                    stop: function (event, ui) {
                        setControl(options, ui);
                    },

                }, data);

            slide.slider(options);

        });

        $(':input', controls).on({

            change:function(){

                var post = {
                    id: $(this).data('id'),
                    device:device,
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
        
    mixer.controls(device, function(response){
        
        controls.append( template.render(response) );

        bind(response.preferences);
        
    });
  
});  
</script>