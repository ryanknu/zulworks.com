(function() {
	$ = jQuery;
	lastUpdate = 0;
	masterRow = false;
	$renameTarget = '';
	
	bg = '#000000';
	$(document).ready(function() {
		
		
		
		$(document.body).bind('gesturechanges', function() {
		
			log(event.rotation);
			hsv = Colors.hex2hsv(bg);
			hsv.V += 2;
			rgb = Colors.hsv2rgb(hsv.H, hsv.S, hsv.V);
			hex = Colors.rgb2hex(rgb.R, rgb.G, rgb.B);
			bg = hex;
			$(document.body).css('background-color', bg);
		});
	});
	
	// WELLS
	(function() {
		var init = function() {
			$('well').html('');
			$('well').each(function() {
				$this = $(this);
				color = $this.attr('color');
				$this.append($('<div/>'));
				$this.children().css('background-color', color);
				$this.bind('click', click);
			});
		};
		
		var click = function() {
			$('.details').hide();
			light_id = $(this).parents('.light-row').attr('lamp-id');
			if ( light_id != undefined ) {
				$('.details[light-id="'+ light_id +'"]').show();
			}
			$('well.selected').removeClass('selected');
			touches.setRegion($(this));
			$('#picker').show();
			$(this).addClass('selected');
		};
		
		$(document).ready(init);
		$(document).click(function() {
			if ( $(event.target).parents('section,div').length < 1 ) {
				$('.selected').removeClass('selected');
				$('#picker').hide();
				$('.details').hide();
			}
		});
		
		document.wells = {
			init: init
		};
	})();
	
	// TOUCHES
	(function() {
		var lastTime = 0,
			started = 0,
			target = document,
			timeout = undefined,
			image = undefined,
			$region = undefined,
			light_id = undefined,
			locks = {
				brightness: false,
				saturaiton: false,
				hue: false
			},
			rotation = 0,
			last = { y:0, x:0 },
			touches = [];
		
		var col = {
			h: 0,
			s: 100,
			v: 100
		};
			
		var wrapup = function() {
			if ( touches.length > 20 ) {
				touches = [];
				$(target).trigger('touchingend');
			}
		};
		
		var lock = function() {
			if ( $(this).prop('tagName') == 'BRIGHTNESS') {
				locks.brightness = !locks.brightness;
				if ( locks.brightness ) {
					$('brightness').addClass('locked');
				}
				else {
					$('brightness').removeClass('locked');
				}
			}
			else if ( $(this).prop('tagName') == 'SATURATION') {
				locks.saturation = !locks.saturation;
				if ( locks.saturation ) {
					$('saturation').addClass('locked');
				}
				else {
					$('saturation').removeClass('locked');
				}
			}
			else if ( $(this).prop('tagName') == 'IMG' ) {
				locks.hue = !locks.hue;
				if ( locks.hue ) {
					$('#pimg').addClass('locked');
				}
				else {
					$('#pimg').removeClass('locked');
				}
			}
		};
		
		var update = function(e) {
			if ( e.touches.length != 2 ) {
				e.preventDefault();
			}
			else {
				if ( $region == undefined ) {
					return;
				}

				dy = last.y - e.touches[0].pageY;
				if ( Math.abs(dy) > 7 && !locks.brightness ) {
					v = dy < 0 ? -3 : 3;
					col.v += v;
					if ( col.v > 100)
						col.v = 100;
					if ( col.v < 0 )
						col.v = 0;
				}
				dx = last.x - e.touches[0].pageX;
				if ( Math.abs(dx) > 7 && !locks.saturation ) {
					s = dx < 0 ? 3 : -3;
					col.s += s;
					if ( col.s > 100)
						col.s = 100;
					if ( col.s < 0 ) 
						col.s = 0;
				}
				
				log('dx: ' + dx + ' dy: ' + dy);
				
				last.y = e.touches[0].pageY;
				last.x = e.touches[0].pageX;
				
				$('indicator.saturation').css('left', 4 * (col.s) + 'px');
				$('indicator.brightness').css('top',   400 - 4 * (col.v) + 'px');
				
				rgb = Colors.hsv2rgb(col.h, col.s, col.v);
				
				log('rgb(' + rgb.R + ',' + rgb.G + ',' + rgb.B + ')' + JSON.stringify(col));
				$region.children().css('background-color', 'rgb(' + rgb.R + ',' + rgb.G + ',' + rgb.B + ')');
				if ( light_id != undefined ) {
					ncol = {
						h: col.h * (255 / 360),
						s: col.s * (255 / 100),
						b: col.v * (255 / 100)
					};
					hue.setLight(light_id, JSON.stringify(ncol));
				}
			}
			
			if ( touches.length > 100 ) {
				thouches.shift();
			}
			
			if ( timeout != undefined ) {
				clearTimeout(timeout);
			}
			
			time = new Date();
			if ( lastTime - time > 200 ) {
				lastTime = time;
				started = time;
				$(target).trigger('touchingstart');
			}
			else {
				lastTime = time;
			}
			
			timeout = setTimeout(wrapup, 100);
			
			touches.unshift({
				c: e.touches.length,
				x: e.touches[0].pageX,
				y: e.touches[0].pageY
			});
		};
		
		var catchrotate = function(e) {
			if ( image == undefined ) {
				image = $('#pimg')[0];
			}
			
			if ( !locks.hue ) {
				col.h += parseFloat(e.rotation) / 20;
				col.h %= 360;
				if ( col.h < 0 ) {
					col.h += 360;
				}
			}
			
			col.h = Math.floor(col.h);
			
			rgb = Colors.hsv2rgb(col.h, col.s, col.v);
			image.style.webkitTransform = ('rotate(' + col.h + 'deg)');
			$region.children().css('background-color', 'rgb(' + rgb.R + ',' + rgb.G + ',' + rgb.B + ')');
			
			rgb = Colors.hsv2rgb(col.h, 100, 100);
			$('brightness,saturation').css('background-color', 'rgb(' + rgb.R + ',' + rgb.G + ',' + rgb.B + ')');
			
			if ( light_id != undefined ) {
				ncol = {
					h: col.h * (255 / 360),
					s: col.s * (255 / 100),
					b: col.v * (255 / 100)
				};
				hue.setLight(light_id, JSON.stringify(ncol));
			}
			
			e.preventDefault();
		};
		
		var setRegion = function($nRegion) {
			$region = $nRegion;
			light_id = $region.parents('.light-row').attr('lamp-id');
		}
		
		window.touches = {
			setRegion: setRegion
		};
		
		$(document).ready(function() {
			document.ontouchmove = update;
			document.addEventListener('gesturechange', catchrotate, false);
			$(document).bind('touchingend', function(e) {
				log('ended');
			});
			$('brightness,saturation').css('background-color', '#ff0000');
			$('brightness,saturation,#picker img').click(lock);
		});
	})();
	
	var throttle = function() {
		now = new Date().getTime();
		delta = now - lastUpdate;
		if ( delta > 100 ) {
			lastUpdate = now;
			return true;
		}
		return false;
	}
	
	var log = function(string)
	{
		$('#log').prepend($('<div>'+ string +'</div>'));
	};
	
	var enableLog = function() {
		if ( $('#log').length < 1 ) {
			$(document.body).append($('<div id="log" />'));
		}
	};
	
	var setLight = function(light_id, color_hex) {
		if ( throttle() ) {
			$.post('/hue/api/status', {light_id: light_id, color: color_hex});
			hue.log('SET: Light ID: ' + light_id + ' Color: ' + color_hex);
		}
	};
	
	var setMood = function() {
		$('.light-row').each(function() {
			light_id = $(this).attr('lamp-id');
			if ( $('well div', $(this)).length > 0 ) {
				rgb = $('well div', $(this)).css('background-color').replace(/[^\d,]/g, '').split(',');
				hsv = Colors.hex2hsv(Colors.rgb2hex(rgb));
				
				ncol = {
					h: hsv.H * (255 / 360),
					s: hsv.S * (255 / 100),
					b: 128
				};
				
				hue.setLight(light_id, JSON.stringify(ncol));
			}
		});
	};
	
	var setColor = function() {
		$('.light-row').each(function() {
			light_id = $(this).attr('lamp-id');
			rgb = $('well div', $(this)).css('background-color').replace(/[^\d,]/g, '').split(',');
			hsv = Colors.rgb2hsv(rgb);
			
			ncol = {
				h: hsv.H * (255 / 360),
				s: hsv.S * (255 / 100),
				b: 255
			};
			
			hue.setLight(light_id, JSON.stringify(ncol));
		});
	};
	
	var toggle = function($sender) {
		$light = $sender.parents('.light-row');
		target = $light.attr('state') == 'on' ? 'off' : 'on';
		$.post('/hue/api/status', {
			light_id: $light.attr('lamp-id'), 
			   state: target
		});
		hue.log('TOGGLE: Light ID: ' + light_id + ' State: ' + target);
		$light.attr('state', target);
	};
	
	var all = function(onoff) {
		$light = $('.light-row').each(function() {
			target = onoff;
			$.post('/hue/api/status', {
				light_id: $(this).attr('lamp-id'), 
				   state: target
			});
			hue.log('TOGGLE: Light ID: ' + $(this).attr('lamp-id') + ' State: ' + target);
			$(this).attr('state', target);
		})		
	};
	
	var submitName = function($target) {
		$row = $target.parents('.rename-light');
		lamp_id = $row.attr('lamp-id');
		name = $('input', $row).val();
		hue.log('Renaming lamp ('+lamp_id+'): "' + name + '"');
		
		$.post('/hue/api/status', {light_id: lamp_id, name: name});
		
		$row.next().show();
		$row.hide();
		
		$renameTarget = $('section#rename section div:visible');
		if ( $renameTarget.length < 1 ) {
			// done, turn all lights on
			$('#utility').html('');
			$('#utility').hide();
			
			$('section#lights .light-row').each(function() {
				lamp_id = $(this).attr('lamp-id');
				$.post('/hue/api/status', {
					light_id: lamp_id,
					   state: 'on'
				});
			});
		}
		else {
			light_id = $renameTarget.attr('lamp-id');
			hue.setLight(light_id, '#ff0000');
			$.post('/hue/api/status', {
				light_id: light_id,
				   state: 'on'
			});
	
			$('section#lights .light-row').each(function() {
				lamp_id = $(this).attr('lamp-id');
				if ( lamp_id != light_id ) {
					$.post('/hue/api/status', {
						light_id: lamp_id,
						   state: 'off'
					});
				}
			});
		}
	};
	
	var renameLights = function() {
		$.get('/hue/index/rename', function(data) {
			$('#utility').html(data);
			$('#utility').show('fast');
			$('section#rename section div:first-child').show();
			
			$renameTarget = $('section#rename section div:visible');
			light_id = $renameTarget.attr('lamp-id');
			
			$.post('/hue/api/status', {
				light_id: light_id,
				   state: 'on'
			});
			
			hue.setLight(light_id, '#ff0000');
			$('section#lights .light-row').each(function() {
				lamp_id = $(this).attr('lamp-id');
				if ( lamp_id != light_id ) {
					$.post('/hue/api/status', {
						light_id: lamp_id,
						   state: 'off'
					});
				}
			});
		});
	};
	
	var initMasterCycle = function() {
		if ( masterRow ) {
			return;
		}
		$master = $('#cycle-master');
		$cycleRow = $('.cycle', $master);
		$cycleRow.html('');
		boxes = $(window).width() < 888 ? 10 : 20;
		boxWidth = ( $(window).width() * .9 - (3 * boxes) ) / boxes;
		for ( i = 0; i < boxes; i++ ) {
			$span = $('<span />');
			$span.addClass('cycle-box');
			$span.attr('time', (10 / boxes) * 1000 * i);
			$span.css('width', boxWidth + 'px');
			$span.css('height', boxWidth + 'px');
			$cycleRow.append($span);
		}
		$cycleRow.show();
		$master.show();
		masterRow = true;
	};
	
	var loadCycle = function($sender) {
		initMasterCycle();
		$light = $sender.parents('.light-row');
		$.post('/hue/api/cycle', {light_id: $light.attr('lamp-id')}, function(data) {
			$light = $('.light-row[lamp-id="' + data.light + '"]');
			for(var key in data.cycle) {
				secs = key * 500;
				$('.cycle-box[time="' + data.cycle[key].time + '"]', $light).css('background-color', data.cycle[key].color);
				$('.cycle-box[time="' + data.cycle[key].time + '"]', $light).attr('color', data.cycle[key].color);
			}
		});
		$cycleRow = $('.cycle', $light);
		$cycleRow.html('');
		boxes = $(window).width() < 888 ? 10 : 20;
		boxWidth = ( $(window).width() * .9 - (3 * boxes) ) / boxes;
		for ( i = 0; i < boxes; i++ ) {
			$span = $('<span />');
			$span.addClass('cycle-box');
			$span.attr('time', (10 / boxes) * 1000 * i);
			$span.css('width', boxWidth + 'px');
			$span.css('height', boxWidth + 'px');
			$cycleRow.append($span);
			
			$span.click(function() {
				if ( $(this).attr('color') == 'yes' ) {
					return;
				}
				$(this).attr('color', 'yes');
			});
		}
		$cycleRow.show();
	};
	
	var cycle = (function($) {
		cycleControl = false;
		cycling = false;
		
		var tick = function()
		{
			time = new Date().getTime();
			interval = time % 10000;
			chunk = interval - (time % 500);
			$colors = $('.cycle-box[time="' + chunk + '"]');
			$colors.each(function() {
				$colorBox = $(this);
				if ( $(this).parents('#cycle-master').length ) {
					// update master row
					$mrow = $('#cycle-master');
					$('.cycle-box', $mrow).css('background-color', 'white');
					$colorBox.css('background-color', '#ccc');
				}
				else {
					// log color change
					col = $(this).attr('color');
					if ( col != undefined && col.length ) {
						light_id = $(this).parents('.light-row').attr('lamp-id');
						hue.setLight(light_id, col);
					}
				}
			});
		};
		
		var start = function()
		{
			cycling = true;
			cycleControl = setInterval(tick, 250);
		};
		
		var stop = function()
		{
			clearInterval(cycleControl);
			cycling = false;
		};
		
		var update = function(light_id, time, color)
		{
			if ( throttle() ) {
				$.post('/hue/api/cycle', {light_id: light_id, time: time, color: color})
			}
		};
		
		return {
			update: update,
			start: start,
			stop: stop
		};
	})($);
		
	window.hue = {
		setLight: setLight,
		all: all,
		enableLog: enableLog,
		toggle: toggle,
		loadCycle: loadCycle,
		renameLights: renameLights,
		submitName: submitName,
		cycle: cycle,
		log: log,
		mood: setMood,
		color: setColor
	};
	
}).call(this);

