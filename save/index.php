<?php
	require_once('./php/db.php');
	
	$defaults = new stdClass;
	$defaults->blockColour = "ff9900";
	$defaults->canvasColour = "000000";
	$defaults->blockSize = 15;
	$defaults->speed = 5;
	$defaults->textString = "Got something to say?";
	$defaults->clearance = 10;
	$defaults->breakWord = false;
	$defaults->ordering = 'default';
	$defaults->do_loop = true;
	$defaults->animate = true;
	
	
	if (isset($_GET['slug']) && !empty($_GET['slug'])) {
		$db = new DB();
		$values = $db->get($_GET['slug']);
		$db->increaseViewCount($_GET['slug']);
	}
	
	
	if (!isset($values) || count($values) < 1) {
		$values = $defaults;
	}
?>
<!DOCTYPE html>
<html lang='en-gb' xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<meta charset='utf-8'>
	<title>Canvas Letters</title>
	<style>
		* { margin:0;padding:0; font-family:monospace; }
		h2 { margin-bottom:20px; color:#fff; font-size:14px; cursor:pointer; }
		p#views { display:none; float:left; }
		#configuration { position:fixed; z-index:2; right:5px; top:5px; width:320px; padding:10px; text-align:right; }
		form { display:none; }
		#configuration.active { background:#fff; }
		#configuration.active h2 { color:#000; }
		#configuration.active p#views { display:block; }
		#configuration.active form { display:block; }
		input#url { clear:both; width:95%; padding:3px; border:1px solid #666; margin-top:5px; }
		fieldset { border:0; }
		legend { display:none; }
		label { float:right; width:100px; margin:0 0 3px 30px; color:#036; }
		p.label { margin:0 0 3px 0; color:#036; }
		.input-container { clear:both; margin-bottom:10px; overflow:auto; padding:5px; background:#eee; }
	</style>
	<script src="./canvas-letters.js"></script>
	<script src="./ajax.js"></script>
	<script src="./json.js"></script>
	<script>
		window.onload = function(){
			// create canvas
			var canvasPage = new canvasLetters();
			function initCanvas() {
				
				var breakWords = document.getElementsByName('breakWord');
				for (var i = breakWords.length - 1; i >= 0; i--){ if (breakWords[i].checked) { var breakWord = parseInt(breakWords[i].value, 10); } };

				var orderings = document.getElementsByName('ordering');
				for (i = orderings.length - 1; i >= 0; i--){ if (orderings[i].checked) { var ordering = orderings[i].value; } };
				
				var loops = document.getElementsByName('do_loop');
				for (i = loops.length - 1; i >= 0; i--){ if (loops[i].checked) { var loop = parseInt(loops[i].value, 10); } };
				
				var animates = document.getElementsByName('animate'); 
				for (i = animates.length - 1; i >= 0; i--){ if (animates[i].checked) { var animate = parseInt(animates[i].value, 10); } };
				
				var blockColour = document.getElementById('blockColour').value;
				if (blockColour.length != 6) { blockColour = null; }
				
				var canvasColour = document.getElementById('canvasColour').value;
				if (canvasColour.length != 6) { canvasColour = null; }
				
				var blockSize = parseInt(document.getElementById('blockSize').value, 10);
				if (blockSize < 5) { blockSize = 5; }
				if (blockSize > 50) { blockSize = 50; }
				
				var clearance = parseInt(document.getElementById('clearance').value, 10);
				if (clearance < 5) { clearance = 5; }
				if (clearance > 50) { clearance = 50; }
				
				var speed = parseInt(document.getElementById('speed').value, 10);
				if (speed < 1) { speed = 1; }
				if (speed > 10) { speed = 10; }
				speed = 100 / speed;
				
				canvasPage.init({
					inline : false,
					blockColour : blockColour,
					canvasColour : canvasColour,
					blockSize : blockSize,
					textString : document.getElementById('textString').value,
					clearance : clearance,
					breakWord : breakWord,
					ordering : ordering,
					loop : loop,
					speed: speed,
					animate : animate
				});
			}
			initCanvas();
			
			// create config
			var configEl = document.getElementById('config-el');
			var configForm = document.getElementById('config-form');
			var config = document.getElementById('configuration');
			var preview = document.getElementById('preview');
			configEl.onclick = function(){
				if (config.className.indexOf('active') == -1) {
					config.className = 'active';
				} else {
					config.className = '';
				}
			};
			
			preview.onclick = function() {
				initCanvas();
			};
			
			var showSlug = function(data) {
				data = json_parse(data);
				var url = document.getElementById('url');
				if (!url) {
					var saveContainer = document.getElementById('save-container');
					url = document.createElement('input');
					url.readOnly = true;
					url.type = "text";
					url.id = 'url';
					saveContainer.appendChild(url);
				}
				url.value = data.url;
			};
			
			configForm.onsubmit = function() {
				initCanvas();				
				var values = ajax.serialise(configForm);
				values += "&method=save";
				ajax.init({
					ajaxUrl: "./php/ajax.php",
					callback: showSlug,
					values: values
				});
				return false;
			};		
		};
	</script>
</head>
<body>
	<h1>Canvas Letters</h1>
	<div id="configuration">
		<?php if(isset($values->views)) { echo '<p id="views">('.$values->views.' views)</p>'; } ?>
		
		<h2 id="config-el">Create your own</h2>
		<form method="post" action="" id="config-form">
			<fieldset>
				<legend>Canvas Letters config</legend>
				<div class="input-container">
					<label for="blockColour">Block Colour</label>
					<input type="text" class="text" name="blockColour" id="blockColour" value="<?php echo $values->blockColour; ?>" />
				</div>
				
				<div class="input-container">
					<label for="canvasColour">Canvas Colour</label>
					<input type="text" class="text" name="canvasColour" id="canvasColour" value="<?php echo $values->canvasColour; ?>" />
				</div>
				
				<div class="input-container">
					<label for="blockSize">Block size</label>
					<input type="range" class="range" name="blockSize" id="blockSize" min="1" max="50" step="1" value="<?php echo $values->blockSize; ?>" />
				</div>
				
				<div class="input-container">
					<label for="speed">Speed</label>
					<input type="range" class="range" name="speed" id="speed" min="1" max="10" step="1" value="<?php echo $values->speed; ?>" />
				</div>
				
				<div class="input-container">
					<label for="textString">Text</label>
					<textarea name="textString" id="textString" cols="20" rows="5"><?php echo $values->textString; ?></textarea>
				</div>
				
				<div class="input-container">
					<p class="label">Break words?</p>
					<label for="breakWord-no">No <input type="radio" class="radio" name="breakWord" id="breakWord-no" value="0" <?php if (!$values->breakWord) { echo 'checked="checked"'; } ?> /></label>
					
					<label for="breakWord-yes">Yes <input type="radio" class="radio" name="breakWord" id="breakWord-yes" value="1" <?php if ($values->breakWord) { echo 'checked="checked"'; } ?> /></label>
					
				</div>
				
				<div class="input-container">
					<label for="clearance">Clearance</label>
					<input type="range" class="range" name="clearance" id="clearance" min="5" max="50" step="1" value="<?php echo $values->clearance; ?>" />
				</div>
				
				<div class="input-container">
					<p class="label">Block order</p>
					<label for="ordering-letter">Letter <input type="radio" class="radio" name="ordering" id="ordering-letter" value="default" <?php if ($values->ordering == 'default') { echo 'checked="checked"'; } ?> /></label>
					<label for="ordering-vertical">Vertical <input type="radio" class="radio" name="ordering" id="ordering-vertical" value="vertical" <?php if ($values->ordering == 'vertical') { echo 'checked="checked"'; } ?> /></label>
					<label for="ordering-horizontal">Horizontal <input type="radio" class="radio" name="ordering" id="ordering-horizontal" value="horizontal" <?php if ($values->ordering == 'horizontal') { echo 'checked="checked"'; } ?> /></label>
					<label for="ordering-reverse">Reverse <input type="radio" class="radio" name="ordering" id="ordering-reverse" value="reverse" <?php if ($values->ordering == 'reverse') { echo 'checked="checked"'; } ?> /></label>
					<label for="ordering-random">Random <input type="radio" class="radio" name="ordering" id="ordering-random" value="random" <?php if ($values->ordering == 'random') { echo 'checked="checked"'; } ?> /></label>
				</div>
				
				<div class="input-container">
					<p class="label">Loop?</p>
					<label for="loop-no">No <input type="radio" class="radio" name="do_loop" id="loop-no" value="0" <?php if (!$values->do_loop) { echo 'checked="checked"'; } ?> /></label>
					<label for="loop-yes">Yes <input type="radio" class="radio" name="do_loop" id="loop-yes" value="1" <?php if ($values->do_loop) { echo 'checked="checked"'; } ?> /></label>
				</div>
				
				<div class="input-container">
					<p class="label">Animate?</p>
					<label for="animate-no">No <input type="radio" class="radio" name="animate" id="animate-no" value="0" <?php if (!$values->animate) { echo 'checked="checked"'; } ?> /></label>
					<label for="animate-yes">Yes <input type="radio" class="radio" name="animate" id="animate-yes" value="1" <?php if ($values->animate) { echo 'checked="checked"'; } ?> /></label>
				</div>
				
				<div class="input-container" id="save-container">
					<input type="button" id="preview" value="preview" />
					<input type="submit" class="button" name="save" id="save" value="save" />
				</div>
			</fieldset>
		</form>
	</div>
</body>
</html>