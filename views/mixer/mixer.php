<div id="mixer" data-device="<?php echo $this->device ?>">

    <h3 class="mixer_name" style="text-align: center;">asdasd</h3>
    
    <div class="render_controls"></div>

    <script type="text/html" id="tpl_control">

        <div class="col-xs-{{:preferences.columns}}">


        </div>    

    </script>

</div>

<script>
$(function(){
  
    var page = $("#mixer"),
        device = page.data('device'),
        controls = $('.render_controls',page),
        template = $.templates("#tpl_control");
        
  
});  
</script>