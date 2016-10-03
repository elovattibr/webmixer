<div id="mixer" data-device="<?php echo $this->device ?>">
    <table class="table">
        <thead>
            <th align="center">On/Off</th>
            <th>Mixer - <?php echo $this->device ?></th>
            <th></th>
        </thead>
        <tbody class="render_controls"></tbody>
    </table>
</div>

<script type="text/html" id="tpl_control">
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
                    <div class="slider" title="{{:name}}" data-id="{{:id}}" data-channel="{{:channel}}" data-min="{{:min}}" data-max="{{:max}}" data-step="{{:step}}" data-value="{{:current}}" style="margin: 10px;"></div>
                {{/for}}
            {{/if}}
        </td>
    </tr>
</script>

<script>
$(function(){
  
    var device = $('#mixer').data('device'),
        controls = $('#mixer .render_controls');
        
    mixer.controls(device, function(count){
        
        $(".slider", controls).each(function(){

            var slide = $(this),
                data = slide.data(),
                options = $.extend({
                    orientation: "horizontal",
                    slide: function (event, ui) {

                        var values = []; 
                        
                        $('.slider[data-id='+data.id+']', controls).each(function(){
                            values.push( $(this).slider("value") );
                        });

                        mixer.set({id:data.id, channel:data.channel, value: values.join(",")}, function(response){
                            console.log("Response", response);
                        });
                        
                    }
                }, data);

            slide.slider(options);

        });

        $(':input', controls).on({

            change:function(){

                var post = {
                    id: $(this).data('id'),
                    channel: $(this).data('channel'),
                    value: null
                };

                if($(this).is(":checkbox")){
                    post.value = $(this).prop('checked')?"on":"off";
                } else {
                    post.value = $(this).val();
                }

                setControl(post, function(response){

                    console.log("Response", response);
                    
                });
            }

        });
        
    })
  
});  
</script>