var zw = {
    
    feed: '/blog/latest',
    
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
    }
    
}
