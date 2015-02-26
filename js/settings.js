
$(document).ready(function() {
    var times = $.parseJSON($('.times').text());
    var positions = $.parseJSON($('.positions').text());

    $(".editable_textarea").editable("/settings/saveTableField", {
        indicator : "<img src='images/ajax-loader.gif'>",
        type   : 'textarea',
        submitdata: { _method: "post" },
        select : true,
        submit : 'OK',
        /*cancel : 'cancel',*/
        cssclass : "editable"
    });

    $(".editable_select.time_select").editable("/settings/saveTableField", {
        indicator : "<img src='images/ajax-loader.gif'>",
        data   : times,
        type   : "select",
        submit : "OK",
        style  : "inherit",
        submitdata : function() {
            return {id : $(this).attr('id')};
        }
    });

    $(".editable_select.display_order").editable("/settings/saveTableField", {
        indicator : "<img src='images/ajax-loader.gif'>",
        data   : positions,
        type   : "select",
        submit : "OK",
        style  : "inherit",
        submitdata : function() {
            return {id : $(this).attr('id'), dataType : 'int'};
        }
    });

    $('.remove-influence').click(function(){
        var deleteSuccess = function() {
            location.reload();
        };
        var confirmAction = confirm('Are you sure you want to remove this?');
        if (confirmAction==true) {
            $.ajax({
                type: "POST",
                url: '/settings/removeInfluence',
                data: {id : $(this).data('id')},
                success: deleteSuccess,
                dataType: 'json'
            });
        }
    });

    $('.add-influence').click(function(){
        var addSuccess = function() {
            location.reload();
        };
        $.ajax({
            type: "POST",
            url: '/settings/addInfluence',
            data: {id : $(this).data('id')},
            success: addSuccess,
            dataType: 'json'
        });
    });

    $('.remove-aspect').click(function(){
        var deleteSuccess = function() {
            location.reload();
        };
        var confirmAction = confirm('Are you sure you want to remove this?');
        if (confirmAction==true) {
            $.ajax({
                type: "POST",
                url: '/settings/removeAspect',
                data: {id : $(this).data('id')},
                success: deleteSuccess,
                dataType: 'json'
            });
        }
    });

    $('.add-aspect').click(function(){
        var addSuccess = function() {
            location.reload();
        };
        $.ajax({
            type: "POST",
            url: '/settings/addAspect',
            data: {id : $(this).data('id')},
            success: addSuccess,
            dataType: 'json'
        });
    });
});