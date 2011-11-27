<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8'>
	<title>Canvas Letter Creator</title>
	<style>
	  * { margin:0;padding:0; }
	  body { background:#000; color:#f90; }
	  .blocks { background:#000; width:310px; padding:20px; float:left; }
	  a { display:block; float:left; width:50px; height:50px; border:1px solid #252525; text-align:center; line-height:50px; text-decoration:none; color:#fff; }
	  a:hover { background:#252525; }
	  a.on { background:#f90; }
	  p {
      float:left;
      padding:10px;
      border:1px solid #f90;
	  }
	</style>
	<script>
		window.onload = function(){
		  var anchors = document.getElementsByTagName("a"),
		      result = document.getElementsByTagName("p")[0],
		      end = [];
		  
		  for (var i=0; i < anchors.length; i++) {
		    end.push(0);
		    anchors[i].onclick = (function(value) {
          return function() {
            if (this.className == "on") {
  		        this.className = "";
  		        end[value] = 0;
  		      } else {
  		        this.className = "on";
  		        end[value] = 1;
  		      }
  		      updateResult();
          };
        })(i);
		  };
		  
		  updateResult = function() {
		    var str = end.join(",");
		    result.innerHTML = "["+str.replace(/,$/, "")+"]";
		  };
		  updateResult();
		};
	</script>
</head>
<body>
	<h1>Canvas Letter Creator</h1>
	<div class="blocks">
  	<?php
  	  for ($i=1; $i < 36; $i++) { 
	      echo '
	      <a href="#" class="off">'.$i.'</a>
	      ';
  	  }
  	?>
  </div>
  <p></p>
</body>
</html>