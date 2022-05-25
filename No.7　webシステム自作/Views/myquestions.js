$(function() {
    $('#answer_button').on('click', function() {
        $(this).hide();
        $('form').show();
    });

    $('form').submit(function(e) {
        var user_id = $(this).find('#user_id').val();
        var question_id = $(this).find('#question_id').val();
        var content = $(this).find('#content').val();
        var created_at = $(this).find('#created_at').val();
        if (content.length == 0) {
            e.preventDefault();
            alert("回答を入力してください。");
        } else {
            e.preventDefault();
            postAnswer(user_id, question_id, content, created_at);
        }
    });

    function postAnswer(user_id, question_id, content, created_at) {
        $.ajax({
            type: 'post',
            url: 'AjaxMyQuestions.php',
            datatype: 'json',
            data: {
                'user_id' : user_id,
                'question_id' : question_id,
                'content' : content,
                'created_at' : created_at
            }
        }).done(function(data) {
            console.log('ajax succeed.');
            console.log('data: ' + data);
            var answer = JSON.parse(data);
            $('form').html('<div class="answer_box"><span>➥</span><p class="answer">' + answer.content + '</p></div>');
        }).fail(function(e) {
            console.log('ajax failed.');
            console.log(e);
        });
    }
});
