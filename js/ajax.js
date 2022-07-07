// $(document).ready(function(){

    // удалить товар (админ)
    $(".product-item__delete").click(function(e){
        e.preventDefault();
        var del_id = $(this).attr('id');
        var parent = $(this).parent();
        $.ajax({
            type:'POST',
            url:'delete.php',
            data:'delete_id='+del_id,
            success:function(data) {
                if(data) {
                    $("#result").html(data);
                    parent.remove();
                }
            }
        });
    });

    // сортировка и порядок (главная)
    $("#sort").change(function(e){
        e.preventDefault();

        var val = $(this).val();
        const url = new URL(window.location);  // == window.location.href
        url.searchParams.set('sort_by', val); 
        history.pushState(null, null, url);    // == url.href

        var strGET = window.location.search.replace( '?', '');

        $.ajax({
            type:'GET',
            url:'sort_products.php',
            data: strGET,
            success:function(data) {
                $('.shop__list').fadeOut(0);
                $('.shop__list').html(data).fadeIn();
                $('.myclass').append('<input type="text" name="sort_by" value="'+val+'" hidden>');
                // $('input[name="sort_by"]').val(val);
            }
        });
    });
    $("#order").change(function(e){
        e.preventDefault();

        var val = $(this).val();
        const url = new URL(window.location);  // == window.location.href
        url.searchParams.set('order_by', val); 
        history.pushState(null, null, url);    // == url.href

        var strGET = window.location.search.replace( '?', '');

        $.ajax({
            type:'GET',
            url:'sort_products.php',
            data: strGET,
            success:function(data) {
                $('.shop__list').fadeOut(0);
                $('.shop__list').html(data).fadeIn();
                $('.myclass').append('<input type="text" name="order_by" value="'+val+'" hidden>');
                // $('input[name="order_by"]').val(val);
            }
        });
    });

    //номер страницы (главная)
    $(".paginator__item").click(function(e){
        e.preventDefault();

        var val = $(this).val();
        const url = new URL(window.location);  // == window.location.href
        url.searchParams.set('page', val); 
        history.pushState(null, null, url);    // == url.href

        var strGET = window.location.search.replace( '?', '');

        $.ajax({
            type:'GET',
            url:'sort_products.php',
            data: strGET,
            success:function(data) {
                $('.shop__list').fadeOut(0);
                $('.shop__list').html(data).fadeIn();
            }
        });
    });

    // создать заказ
    $("#send_order").click(function(d){
        d.preventDefault();
        $.ajax({
            type:'POST',
            url:'send_order.php',
            dataType: 'json',
            data: $('#order_form').serialize(),
        }).done(function(data){
            $("#order_form").reset();
        });
    });

    // изменить статус заказа
    $(".order-item__btn").click(function(d){
        d.preventDefault();
        let id = $(this).attr('id');
        let stat = $(this).val();
        $.ajax({
            type:'POST',
            url:'change_status.php',
            context: $(this),
            data: $(this).attr('id')+'='+$(this).val(),
        }).done(function(data){
            // меняем значение статуса в кнопке на противоположное
            if (stat == 'yes') {
                $(this).val('no');
            } else { $(this).val('yes'); }
        });
    });

// });

