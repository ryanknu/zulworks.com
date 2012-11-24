var zw = {
    
    feed: '/blog/latest',
    rsData: '/zul/data',
    
    getFeed:function()
    {
        $.ajax({
            url:this.feed,
            type:'GET',
            success:function(data)
            {
                $('#home').html(data);
            },
        })
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
