 $(document).ready(function() {
     /*
     $('#new_post').submit(function(){
        $.post(
            $(this).attr('action'),
            $(this).serialize(),
            function(response) {
                var obj = JSON.parse(response);
                alert(obj.description);
                location.href = '/';
            }, 'json'
        )
        .fail(function(xhr, textStatus, errorThrown) {
            response = JSON.parse(xhr.responseText)
            alert( response.description );
        })
        ;
        return false;
     })
     */
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