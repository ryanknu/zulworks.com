<!DOCTYPE HTML>
<html>
    <head>
        <link href='http://fonts.googleapis.com/css?family=Questrial' rel='stylesheet' type='text/css' />
        <link href='/h/style.css' rel='stylesheet' type='text/css' />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type="text/javascript" src="/h/hue.js"></script>
        
		<script type="text/javascript" src="/h/colors.min.js"></script>
        
        <title>Zue</title>
    </head>
    <body>
    	<div class="zue-header">
    		<div class="toolbox">
    			<a onclick="hue.enableLog();">Log</a>
    			<a onclick="hue.renameLights();">Rename</a>
    			<a onclick="hue.mood();">Mood</a>
    			<a onclick="hue.color();">Color</a>
    			<a onclick="hue.all('on');">On</a>
    			<a onclick="hue.all('off');">Off</a>
    		</div>
			Zue
		</div>


        {$content}

    </body>
</html>