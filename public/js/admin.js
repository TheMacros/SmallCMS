var admin = Object();
admin.menu = Object();
admin.menu.item = Object();
$(document).ready(function(){
    $('.menu-item-delete').livequery('click', function(){
        item_id = $(this).attr('itemid');
        admin.menu.item.remove(item_id, $(this).parent().parent().parent().parent());
        return false;
    });
    $('.menu-item').livequery('click', function(){
        $(this).parent().children('.menu-item-options').slideToggle("down");
        $(this).parent().toggleClass('active');
    });
    $('.menu-delete').livequery('click', function(){
        id = $(this).attr('itemid');
        admin.menu.remove(id, $('.nav-tabs li.active'));
    });
    
    $('select#icon option').each(function(){
       $(this).prepend('<i class="' + $(this).val() + '"></i>'); 
    });
    
    $('.menu-add-item').click(function(){
        admin.menu.item.addForm(this);
    });
    $('#menu-item-new-remove').click(function(){
        $('#menu-item-new').fadeOut();
    });
    $('#menu-item-new-add').click(function(){
        form = document.getElementById('menu-item-new-form');
        admin.menu.item.add(form);
    });
});
admin.menu.item.add = function(form){
    var errors = false;
    if ($(form.title).val() == ''){
        if ($(form).children('.alert.title').length == 0)
            $(form.title).after('<div class="alert alert-error title">Title must be non-empty<a class="close" data-dismiss="alert" href="#">&times;</a></div>');
        errors = true;
    }else{
        $(form).children('.alert.title').remove();
    }
    if ($(form.link).val() == ''){
        if ($(form).children('.alert.link').length == 0)
            $(form.link).after('<div class="alert alert-error link">Link must be non-empty<a class="close" data-dismiss="alert" href="#">&times;</a></div>');
        errors = true;
    }else{
        $(form).children('.alert.link').remove();
    }
    if (errors){
        return false;
    }else{
        $('#menu-item-new-add').button('loading');
        $.post(
            '/admin/menu/add-item',
            {
                'title': $(form.title).val(),
                'link': $(form.link).val(),
                'icon': $(form.icon).val(),
                'menu_id': $(form.menu_id).val(),
                'parent_id': $(form.parent_id).val()
            }, function(data){
                if (data.status == 'ok'){
                    $(form).parent().parent().children('.nav-pills').append(data.content);
                    $('#menu-item-new').fadeOut();
                }
                $('#menu-item-new-add').button('reset');
            }, 'json'
        )
    }
    
}
admin.menu.item.addForm = function(el){
    $('#menu-item-new #menu_id').val($(el).attr('itemid'));
    $('#menu-item-new').appendTo($(el).parent().parent().parent().parent().parent().parent());
    $('#menu-item-new').fadeIn();
}
admin.menu.remove = function(item_id, el){
    $('#modalPopup .modal-header h3').text('Are your shure to delete this menu?');
    $('#modalPopup .modal-body p').html('You can restore this menu just changing the <strong>Status</strong> option to Enabled');
    $('#modalPopup').modal();
    $('#modalPopup .btn-primary').click(function(){
        btn = this;
        $(this).button('loading');
        $.post(
            '/admin/menu/delete/',
            {
                'id': item_id
            }, function(data){
                if (data.status == 'ok'){
                    tab_id = el.children('a').attr('href');
                    el.remove();
                    $(tab_id).remove();
                    if ($('.tab-content div').length == 0){
                        $('.tabbable').html('<div class="alert alert-info"> No menu added yet. </div>');
                    }
                }
                $(btn).button('reset');
                $('#modalPopup').modal('hide');
                $('#modalPopup .btn-primary').unbind('click');
            }
        ) 
    });
}
admin.menu.item.remove = function(item_id, el){
    $('#modalPopup .modal-header h3').text('Are your shure to delete this menu item?');
    $('#modalPopup .modal-body p').html('You cannot restore this item later!');
    $('#modalPopup').modal();
    $('#modalPopup .btn-primary').click(function(){
        btn = this;
        $(this).button('loading');
        $.post(
            '/admin/menu/delete-item/',
            {
                'id': item_id
            }, function(data){
                if (data.status == 'ok'){
                    if (el.parent().find('>li').length == 1){
                        el.parent().remove();
                    }else{
                        el.remove();
                    }
                }
                $(btn).button('reset');
                $('#modalPopup').modal('hide');
                $('#modalPopup .btn-primary').unbind('click');
            }
        ) 
    });
    
}