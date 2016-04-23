 $(document).ready(function() {
    var timeout = 15000;
    function updateViews() {
        $.get('/api/stats/get', function(data) {
            $('#views_amount_cnt').html(data.views_amount);
            $('#posts_amount_cnt').html(data.posts_amount);
        });
        setTimeout(function(){
            updateViews();
        }, timeout)
    }
    setTimeout(function(){
        updateViews();
    }, timeout);
 });