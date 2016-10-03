<div class="row" style="margin-top: 20px">
    
    <div class="col-xs-4 col-sm-3 preferences-sidebar">
        <div class="list-group">
            <a href="#" data-url="/preferences/devices" data-target=".preferences-container" class="list-group-item active">Devices</a>
            <a href="#" data-url="/preferences/lookandfeel" data-target=".preferences-container" class="list-group-item">Look & Feel</a>
            <a href="#" data-url="/preferences/about" data-target=".preferences-container" class="list-group-item">About</a>
        </div>
    </div>
    
    <div class="col-xs-8 col-sm-9 preferences-container">
        
        
    </div>
    
</div>

<script>
$(function(){
  
  var sidebar = $('.preferences-sidebar');
      
  $('a:first',sidebar).click();
  
});  
</script>