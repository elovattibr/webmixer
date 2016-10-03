<div id="devices">
    <table class="table">
        <thead>
            <th>Label</th>
            <th>Name</th>
            <th></th>
        </thead>
        <tbody class="render"></tbody>
    </table>
</div>

<script type="text/html" id="tpl_device">
    {{props devices}}
        <tr>
            <td>{{:prop.label}}</td>
            <td>{{:key}}</td>
            <td></td>
        </tr>
    {{/props}}
</script>

<script>
$(function(){
  
    var devices = $('#devices .render'),
        template = $.templates("#tpl_device");
        
    mixer.preferences(function(response){
        
        devices.html( template.render(response) );
        
    });
  
});  
</script>