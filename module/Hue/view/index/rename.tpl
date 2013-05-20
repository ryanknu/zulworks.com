<section id="rename">
	<div class="bigger">Rename Lights</div>
	<section>
		{foreach from=$lights item=light}
			<div style="display:none;" class="rename-light" lamp-id="{$light->id}">
				Only one lamp is on. Type in the name you want for that light.<br />
				Old Name: <span class="name">{$light->name}</span><br />
				<input type="text" /><br />
				<a onclick="hue.submitName($(this));">Submit Name</a>
			</div>
		{/foreach}
	</section>
</section>