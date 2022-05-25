$(function() {
    $('#magnifying_glass').on('click', function() {
        $('#search_bar').toggleClass('hide');
    });

    var pos = 0;
    var point = $(window).height();
    $(window).on('scroll', function () {
        if($(this).scrollTop() > pos) {
            var document_h = $(document).height();
            point = point + $(this).scrollTop();
            if(point >= document_h) {
                addAlbums();
                point = 0;
            }
        } else {
            point = point - $(this).scrollTop();
        }
        pos = $(this).scrollTop();
    });

    // アルバム追加表示
    function addAlbums() {
        var albums = ''; // 追加アルバム
        var count = $('li').length; // アルバム件数
        console.log('count:' + count);

        $.ajax({
            type: 'post', // HTTPリクエストメソッド
            url: 'AjaxIndex.php', // リクエストを送信する先のURL (省略した場合は呼び出しもとに送信)
            datatype: 'json', // サーバーからレスポンスされるデータの型を指定
            data: { count: count } // サーバーに送信する値 (オブジェクトが指定された場合、processDataを指定しない限りクエリ文字列に変換されてGETリクエストとして付加される)
        }).done(function(data) {
            console.log('ajax successed.');
            // 追加アルバム生成
            $.each(JSON.parse(data), function(index, album) {
                albums += '<li><a class="albums" href="othersalbumview.php?album_id=' + album.id + '&user_id=' + album.user_id + '">';
                if(album.image == 'no_image.jpg') {
                    albums += '<div class="empty_image"><img class="image" src="/img/' + album.image + '" alt="画像"><p class="no_image">No Image</p></div>';
                } else {
                    albums += '<div class="image_area"><img class="image" src="/album_img/' + album.image + '" alt="画像"></div>';
                }
                albums += '<p class="album_names">' + album.name + '</p></a></li>';
            });
            // アルバム追加
            $('#content').append(albums);
        }).fail(function(e) {
            console.log('ajax failed.');
            console.log(e);
        });
    }
});
