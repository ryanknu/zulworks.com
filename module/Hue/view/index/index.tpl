<div class="light-row" id="utility"></div>

<div class="light-row" id="cycle-master">
	<div class="well" color="black"><div style="background-color: black"></div></div>
	<div class="row-rest master">
    	Master Cycle
    	<a onclick="hue.cycle.start();">Start</a>
    	<a onclick="hue.cycle.stop();">Stop</a>
    </div>
    <div class="cycle"></div>
</div>

<div style="height:25px;">&nbsp;</div>

<section id="nlights">
	{foreach from=$lights item=light}
	    <div class="light-row" lamp-id="{$light->id}" state="{if $light->state->on}on{else}off{/if}">
	        <well color="{$light->color->hex}"></well>
	    </div>
	{/foreach}
</section>

<div id="detail-wrapper">
	{foreach from=$lights item=light}
		<section class="details" light-id="{$light->id}">
			<div style="float:right">{if $light->state->on}ON{else}OFF{/if}</div>
			{$light->name}
		</section>
	{/foreach}
</div>

<section id="lights" style="display:none;">
	{foreach from=$lights item=light}
	    <div class="light-row" lamp-id="{$light->id}" state="{if $light->state->on}on{else}off{/if}">
	        <div class="row-rest">
	        	{$light->name} is {if $light->state->on}ON{else}OFF{/if}
	        	<a onclick="hue.toggle($(this))">Turn on/off</a>
	        	<a onclick="hue.loadCycle($(this))">Edit cycle</a>
	        </div>
	        <div class="cycle"></div>
	    </div>
	{/foreach}
</section>

<section id="picker">
	<div>
		<img id="pimg" src="/h/wheel.png" />
		<img src="/h/wheel-indicator.png" />
		<brightness><indicator class="brightness"></indicator></brightness>
		<saturation><indicator class="saturation"></indicator></saturation>
	</div>
</section>
