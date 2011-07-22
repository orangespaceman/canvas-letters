/*
 * Canvas Letters Banner
 *
 * petegoodman.com
 */
var canvasLettersBanner = function() {

	/*
	 * The HTML body element
	 */
	var body = null,
	
	/*
	 * The canvas HTMl element
	 */
	canvas = null,

	/*
	 * The canvas draw context
	 */
	drawContext = null,
	
	/*
	 * The draw interval
	 */
	drawInterval = null,
	
	/*
	 * Bool - are we currently recalculating?
	 */
	redrawing = false,
	
	/*
	 * Array of blocks to draw
	 */
	blocks = [],
	blockCount = 0,
	blockSize = 0,
	
	/*
	 * current block drawing details
	 */
	currentX = 0,
	currentY = 0,
	
	/*
	 * current animation settings
	 */
	currentXOffset = 0,
	textStringWidth = 0,
	

	/*
	 * Character block dimensions
	 */
	characterBlockWidth = 5,
	characterBlockHeight = 7,
	characterWidth = 0,
	
	/*
	 * the (potentially modified) text string we're drawing
	 */
	textString = "",
	
	/*
	 * Debug timeout
	 */
	debugTimeout = null,
	
	/*
	 * Characters
	 */
	characters = {
		"a": [0,0,1,0,0,0,1,0,1,0,1,0,0,0,1,1,0,0,0,1,1,1,1,1,1,1,0,0,0,1,1,0,0,0,1],
		"b": [1,1,1,1,0,1,0,0,0,1,1,0,0,0,1,1,1,1,1,0,1,0,0,0,1,1,0,0,0,1,1,1,1,1,0],
		"c": [0,1,1,1,0,1,0,0,0,1,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,1,0,1,1,1,0],
		"d": [1,1,1,0,0,1,0,0,1,0,1,0,0,0,1,1,0,0,0,1,1,0,0,0,1,1,0,0,1,0,1,1,1,0,0],
		"e": [1,1,1,1,1,1,0,0,0,0,1,0,0,0,0,1,1,1,0,0,1,0,0,0,0,1,0,0,0,0,1,1,1,1,1],
		"f": [1,1,1,1,1,1,0,0,0,0,1,0,0,0,0,1,1,1,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0],
		"g": [0,1,1,1,0,1,0,0,0,1,1,0,0,0,0,1,0,1,1,1,1,0,0,0,1,1,0,0,0,1,0,1,1,1,1],
		"h": [1,0,0,0,1,1,0,0,0,1,1,0,0,0,1,1,1,1,1,1,1,0,0,0,1,1,0,0,0,1,1,0,0,0,1],
		"i": [1,1,1,1,1,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,1,1,1,1,1],
		"j": [1,1,1,1,1,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,1,0,1,0,0,1,0,1,0,0,1,1,1,0,0],
		"k": [1,0,0,0,1,1,0,0,1,0,1,0,1,0,0,1,1,0,0,0,1,0,1,0,0,1,0,0,1,0,1,0,0,0,1],
		"l": [1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1,1,1,1,1],
		"m": [1,0,0,0,1,1,1,0,1,1,1,0,1,0,1,1,0,1,0,1,1,0,0,0,1,1,0,0,0,1,1,0,0,0,1],
		"n": [1,0,0,0,1,1,0,0,0,1,1,1,0,0,1,1,0,1,0,1,1,0,0,1,1,1,0,0,0,1,1,0,0,0,1],
		"o": [0,1,1,1,0,1,0,0,0,1,1,0,0,0,1,1,0,0,0,1,1,0,0,0,1,1,0,0,0,1,0,1,1,1,0],
		"p": [1,1,1,1,0,1,0,0,0,1,1,0,0,0,1,1,1,1,1,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0],
		"q": [0,1,1,1,0,1,0,0,0,1,1,0,0,0,1,1,0,0,0,1,1,0,1,0,1,1,0,0,1,0,0,1,1,0,1],
		"r": [1,1,1,1,0,1,0,0,0,1,1,0,0,0,1,1,1,1,1,0,1,0,1,0,0,1,0,0,1,0,1,0,0,0,1],
		"s": [0,1,1,1,0,1,0,0,0,1,1,0,0,0,0,0,1,1,1,0,0,0,0,0,1,1,0,0,0,1,0,1,1,1,0],
		"t": [1,1,1,1,1,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0],
		"u": [1,0,0,0,1,1,0,0,0,1,1,0,0,0,1,1,0,0,0,1,1,0,0,0,1,1,0,0,0,1,0,1,1,1,0],
		"v": [1,0,0,0,1,1,0,0,0,1,1,0,0,0,1,0,1,0,1,0,0,1,0,1,0,0,0,1,0,0,0,0,1,0,0],
		"w": [1,0,0,0,1,1,0,0,0,1,1,0,0,0,1,1,0,1,0,1,1,0,1,0,1,1,0,1,0,1,0,1,0,1,0],
		"x": [1,0,0,0,1,1,0,0,0,1,0,1,0,1,0,0,0,1,0,0,0,1,0,1,0,1,0,0,0,1,1,0,0,0,1],
		"y": [1,0,0,0,1,1,0,0,0,1,1,0,0,0,1,0,1,0,1,0,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0],
		"z": [1,1,1,1,1,0,0,0,0,1,0,0,0,1,0,0,0,1,0,0,0,1,0,0,0,1,0,0,0,0,1,1,1,1,1],
		"0": [0,1,1,1,0,1,0,0,0,1,1,0,0,1,1,1,0,1,0,1,1,1,0,0,1,1,0,0,0,1,0,1,1,1,0],
		"1": [0,0,1,0,0,0,1,1,0,0,1,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,1,1,1,1,1],
		"2": [0,1,1,1,0,1,0,0,0,1,0,0,0,0,1,0,0,0,1,0,0,0,1,0,0,0,1,0,0,0,1,1,1,1,1],
		"3": [0,1,1,1,0,1,0,0,0,1,0,0,0,0,1,0,0,1,1,0,0,0,0,0,1,1,0,0,0,1,0,1,1,1,0],
		"4": [0,0,0,1,0,0,0,1,1,0,0,1,0,1,0,1,0,0,1,0,1,1,1,1,1,0,0,0,1,0,0,0,0,1,0],
		"5": [1,1,1,1,1,1,0,0,0,0,1,0,0,0,0,1,1,1,1,0,0,0,0,0,1,1,0,0,0,1,0,1,1,1,0],
		"6": [0,0,1,1,0,0,1,0,0,0,1,0,0,0,0,1,1,1,1,0,1,0,0,0,1,1,0,0,0,1,0,1,1,1,0],
		"7": [1,1,1,1,1,0,0,0,0,1,0,0,0,1,0,0,0,1,0,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0],
		"8": [0,1,1,1,0,1,0,0,0,1,1,0,0,0,1,0,1,1,1,0,1,0,0,0,1,1,0,0,0,1,0,1,1,1,0],
		"9": [0,1,1,1,0,1,0,0,0,1,1,0,0,0,1,0,1,1,1,1,0,0,0,0,1,0,0,0,1,0,0,1,1,0,0],
		"-": [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
		"?": [0,1,1,1,0,1,0,0,0,1,0,0,0,1,0,0,0,1,0,0,0,0,1,0,0,0,0,0,0,0,0,0,1,0,0],
		"!": [0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,0,0,0,0,0,1,0,0],
		"@": [0,1,1,1,0,1,0,0,0,1,1,0,1,1,1,1,0,1,0,1,1,0,1,1,0,1,0,0,0,1,0,1,1,1,0],
		"&": [0,1,1,0,0,1,0,0,1,0,1,0,1,0,0,0,1,0,0,0,1,0,1,0,1,1,0,0,1,0,0,1,1,0,1],
		".": [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,0,1,1,0], 
		" ": [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0] 
	},
	
	
	
	/*
	 * default options
	 * (the ones to copy from if an option isn't specified specifically)
	 */
	defaults = {
		blockColour : "ff9900",
		canvasColour : "000000",
		textString : "Lorem ipsum dolor sit amet, consectetur adipisicing elit",
		clearance : 30,
		speed : 50,
		animate : false,
		debugMode : false		
	},
	
	/*
	 * config options
	 * (the combined options, the ones to use)
	 */
	options = {},
	
	
	 /*
	 * initialisation method
	 */
	init = function(initOptions){
		
		debug("init()");
		
		// save the init options
		saveOptions(initOptions);

		// create canvas element
		if (!canvas) {
			createCanvas();		
		}
		
		// init canvas set-up
		startLetters();
		
		// reset on resize
		if (!options.inline) {
			window.onresize = function() {
				startLetters();
			};
		}
	},
	
	
	/*
	 * save any options sent through to the intialisation script, if set
	 */
	saveOptions = function(initOptions) {
		
		debug('saveOptions()');
		
		for (var option in defaults) {
			if (!!initOptions[option] || initOptions[option] === false) {
				options[option] = initOptions[option];
			} else {
				options[option] = defaults[option];
			}
		}
	},
	
	
	
	/*
	 * Create canvas element
	 */
	createCanvas = function() {
		
		debug("createCanvas()");
		
		// condition : if we are creating a full-screen canvas
		if (!options.inline) {
		
			// create canvas
			canvas = document.createElement('canvas');
			canvas.id = "canvas";
			canvas.style.position = "absolute";
			canvas.style.zIndex = 1;
			canvas.style.left = 0;
			canvas.style.top = 0;
		
			// add the canvas into the page
			body = document.getElementsByTagName('body')[0];
			body.appendChild(canvas);
		
		// if we are using an existing canvas element inline in the page
		} else {
			canvas = document.getElementById(options.canvasId);
		}
		
		// get the draw context
		drawContext = canvas.getContext("2d");
	},
	
	
	
	/*
	 * Start letters
	 */
	startLetters = function() {
		
		debug('startLetters()');

		// catch multiple calls
		if (!redrawing) {
			
			redrawing = true;
		
			clearInterval(drawInterval);

			// init values
			blocks = [];
			blockCount = 0;
			currentX = options.clearance;
			currentY = options.clearance;
			textString = options.textString.toLowerCase();

			// set up functions
			setCanvasWidth();
			setCanvasHeight();	
			setBlockSize();		
			calculateBlockPositions();
			setLoopWidth();
			
			currentXOffset = -canvas.width;
			
			debug('textStringWidth: ' + textStringWidth);
			
			// start loop
			drawInterval = setInterval(draw, 20);
			
			// redrawing complete!
			redrawing = false;
		}
	},
	
	
	/*
	 *
	 */
	setCanvasWidth = function() {
		canvas.width = document.body.offsetWidth;
	},
	
	
	/*
	 *
	 */
	setCanvasHeight = function() {
		canvas.height = document.documentElement.clientHeight;
	},
	
	
	/*
	 *
	 */
	setBlockSize = function() {
		blockSize = Math.floor((canvas.height - (options.clearance*2)) / characterBlockHeight);
	},
	
	
	/*
	 * 
	 */
	calculateBlockPositions = function() {
		
		debug('calculateBlockPositions()');
		
		characterWidth = (blockSize * characterBlockWidth) + options.clearance;
	
		// draw the text string
		for (var character = 0, textStringLength = textString.length; character < textString.length; character++) {

			// if we can draw this letter, begin
			if (!!characters[textString[character]]) {
				
				// if this isn't the first character, work out how far along the line to put it
				if (character > 0) {
					currentX += characterWidth;
				}
				
				// get the blocks for this character
				var blockArray = characters[textString[character]];
				
				// for each block within a character
				for (var block = 0, blockArrayLength = blockArray.length; block < blockArrayLength; block++) {
										
					// calculate X & Y positions for each block
					var x = currentX;
					var y = currentY;
					x += (blockSize * (block % characterBlockWidth));
					if (block >= characterBlockWidth) {
						y += (blockSize*(Math.floor(block/characterBlockWidth)));
					}
						
					// if we're drawing a block, add it to the array
					if (blockArray[block] == 1) {
						//debug('draw a block at ' + x + ', ' + y);
						blocks.push({x:x,y:y,opacity:0});
					}
				}
			} else {
				debug("calculateBlockPositions() - letter not recognised: " + textString[character]);
			}
		}

		blockCount = blocks.length;
		debug('calculateBlockPositions() - block count: ' + blockCount);
	},
	
	
	
	/*
	 *
	 */
	setLoopWidth = function() {
		textStringWidth = blocks[blocks.length-1].x;
	},
	
	
	/*
	 *
	 */
	drawRectangle = function(x,y,w,h) {
	  drawContext.beginPath();
	  drawContext.rect(x,y,w,h);
	  drawContext.closePath();
	  drawContext.fill();
	},
	
	

	/*
	 *
	 */
	draw = function() {
		
		// clear canvas
		drawContext.clearRect(0,0,canvas.width,canvas.height);

		// draw background
		drawContext.fillStyle = "#"+options.canvasColour;
		drawContext.fillRect(0, 0, canvas.width, canvas.height);
		
		// normal direction, add blocks
		var drawColour = options.blockColour;
		
		// calculate which blocks to work on
		var animateLimit = blocks.length;
						
		// loop through blocks and draw!
		for (var counter = animateLimit; counter >= 0; counter--) {
			if (!!blocks[counter]) {
				if (blocks[counter].x > currentXOffset-blockSize && blocks[counter].x < currentXOffset+canvas.width) {
					drawContext.fillStyle = "#"+options.blockColour;
					drawRectangle(blocks[counter].x-currentXOffset, blocks[counter].y, blockSize, blockSize);
				}
			}
		};
		
		// add one to loop
		currentXOffset+=options.speed;
	
		// calculate whether to end the drawing
		if (currentXOffset >= textStringWidth + canvas.width) {
			currentXOffset = -canvas.width;
		}
	},
	
	
	/*
	 * Debug
	 * output debug messages
	 * 
	 * @return void
	 * @private
	 */
	debug = function(content) {
		if (!!options.debugMode) {
			console.log(content);
			clearTimeout(debugTimeout);
			debugTimeout = setTimeout(debugSpacer, 2000);
		}
	},
	debugSpacer = function() {
		if (!!options.debugMode) {
			console.log("----------------------------------------------------------------------------------");
		}
	};


	
	/*
	 * expose public methods
	 */
	return {
		init: init
	};	
};