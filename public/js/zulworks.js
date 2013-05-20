var zw = {
    
    feed: '/blog/latest',
    rsData: '/zul/data',
    itunesFeed: 'http://zf.ryanknu.com/iTunes/Json?callback=?',
    
    getFeed:function()
    {
        $.ajax({
            url:this.feed,
            type:'GET',
            success:function(data)
            {
				$('#home').html( data );
            },
        })
    },
    
    getitunes:function()
    {
    	$.getJSON( this.itunesFeed, null, function(results) {
    		$('#index-card').html('<b>' + results.track.name + '</b><br /><i>by</i> ' + results.track.artist + '<br /><i>on</i> ' + results.track.album);
    		$('#album-cover').mouseenter(function() {
    			$('#index-card').slideDown('fast');
    		});
    		$('#album-cover').click(function() {
    			$('#index-card').slideDown('fast');
    		});
    		$('#index-card').mouseleave(function() {
    			$('#index-card').hide();
    		});
    	});
    },
    
    updateRsStats:function()
    {
        $.ajax({
            url:this.rsData,
            type:'GET',
            success:function(data)
            {
                dom = $(data);
                dom.find('#zrs-zul').each(function() {
                    $('#zrs-zul').html( $(this).html() );
                })
                $('#zrs-zul').html(dom.find('#zrs-zul').html());
            }
        })
    }
    
}
