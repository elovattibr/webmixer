<!DOCTYPE html> 
<html>
    <head>
        
        <title>WebMixer</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="public/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="public/css/jquery-ui.css"/>
        <link rel="stylesheet" href="public/css/app.css"/>

    </head>
    <body> 
        
        <nav class="navbar navbar-inverse navbar-fixed-top">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#">WebMixer</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
              <ul class="nav navbar-nav">
                <li class="active"><a href="#mixer" data-url="/mixer/default">Mixer</a></li>
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Devices <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php
                            foreach($this->devices AS $device => $preferences) {  ?>
                                <li><a href="#preferences" data-url="/devices/<?php echo $device ?>"><?php echo $preferences->label ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>                  
                </ul>                
              </ul>
              <ul class="nav navbar-nav pull-right">
                  <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span></a>
                      <ul class="dropdown-menu  dropdown-menu-right">
                          <li><a href="#preferences" data-url="/preferences/main">Preferences</a></li>
                          <li role="separator" class="divider"></li>
                          <li><a href="#">About</a></li>
                      </ul>
                  </li>                  
              </ul>
            </div>
          </div>
        </nav>

        <div class="container">


        </div>     
        
        <script src="public/js/jquery.min.js"></script>
        <script src="public/js/jquery-ui.min.js"></script>
        <script src="public/js/jsviews.min.js"></script>
        <script src="public/js/bootstrap.min.js"></script>
        <script src="public/js/app.js" type="text/javascript" charset="utf-8"></script>        

        <script>
        $(document).ready(function(){
            
            $("#navbar ul li:visible:first a").click();
            
        });  
        </script>        
        
    </body>
</html>