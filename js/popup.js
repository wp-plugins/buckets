jQuery(function($){

    $("#delete-action a").removeClass('submitdelete deletion').html('Cancel').show();
    $("#delete-action a").click(function(){
        parent.eval('update_buckets()');
        return false;
    });

    $('#publish').click(function(){
        parent.eval('update_buckets()');
    });

});