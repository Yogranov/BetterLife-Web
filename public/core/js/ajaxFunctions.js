function likeArticle(likeButton, userId, articleId, token) {
    $.post( "https://betterlife.845.co.il/core/services/AjaxApi.php",
        {
            Type: "ArticleLike",
            UserId: userId,
            ArticleId:  articleId,
            Token: token
        }, function (data) {
            likeButton.html(data + " <i class='fas fa-thumbs-up' style='margin-bottom: 2px' ></i>");
    });
}


function addOrRemoveLike(likeButton, userId, commentId,token) {
    $.post( "https://betterlife.845.co.il/core/services/AjaxApi.php",
        {
            Type: "CommentLike",
            UserId: userId,
            CommentId:  commentId,
            Token: token
        }, function (data) {
            likeButton.html("<i class='fas fa-thumbs-up' style='margin-bottom: 2px' ></i> " + data);
    });
}


function addComment(articleId, userId, textArea, token) {
    $.post(
        "https://betterlife.845.co.il/core/services/AjaxApi.php",
        {
            Type: "ArticleComment",
            UserId: userId,
            ArticleId:  articleId,
            Content: textArea.val(),
            Token: token
        }, function (data) {
            var response = jQuery.parseJSON(data);

            if(response.Error) {
                $('#articleCommentsError').html('<div class="text-center">לא הוזן תוכן לתגובה</div>')
                return;
            }
            let sexImg = "";
            if(response.Sex == 0)
                sexImg = "male1.jpg";
            else
                sexImg = "female2.jpg";



            let comment = `
                <div class="row align-items-center">
                    <div class="col-4 col-md-1">
                        <img class="img-fluid" src="../../media/characters/${sexImg}">
                    </div>
                    <div class="col-md-9 col-8">
                        <h6><strong> ${response.FullName}</strong> <i style="font-size: 12px; color: #4e4e4e"> ${response.Timestamp} </i></h6>
                        <p>${textArea.val()}</p>
                    </div>
                    <div class="col-md-2 col-12 text-left">
                        <span class='ml-4 like-button like-button-active' onclick=\"addOrRemoveLike($(this), ${userId}, ${response.CommentId} ,'${token}')\"><i class='fas fa-thumbs-up' style='margin-bottom: 2px' ></i> 0 </span>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                </div>
            `;
            textArea.val('');
            $('#commentsRow').prepend(comment);
    });
}


function showHideArticle(articleId, method, userId, token, button) {
    $.post( "https://betterlife.845.co.il/core/services/AjaxApi.php",
        {
            Type: "showHideArticle",
            ArticleId: articleId,
            UserId: userId,
            Method:  method,
            Token: token
        }, function (data) {
            if(method == "hide")
                button.addClass("btn-success").removeClass("btn-danger").text("הצג").attr("onclick", 'showHideArticle('+ articleId +', "show",'+ userId + ', "'+ token + '", $(this))');

            if(method == "show")
                button.addClass("btn-danger").removeClass("btn-success").text("הסתר").attr("onclick", 'showHideArticle('+ articleId +', "hide",'+ userId + ', "'+ token + '", $(this))');
        });
}


function enableDisableUser(userId, method, adminId, adminToken, button) {
    $.post( "https://betterlife.845.co.il/core/services/AjaxApi.php",
        {
            Type: "enableDisableUser",
            UserId: userId,
            Method: method,
            adminId:  adminId,
            adminToken: adminToken
        }, function (data) {
            if(method == "enable")
                button.addClass("btn-danger").removeClass("btn-success").text("השבת חשבון").attr("onclick", 'enableDisableUser('+ userId +', "disable",'+ adminId + ', "'+ adminToken + '", $(this))');

            if(method == "disable")
                button.addClass("btn-success").removeClass("btn-danger").text("הפעל חשבון").attr("onclick", 'enableDisableUser('+ userId +', "enable",'+ adminId + ', "'+ adminToken + '", $(this))');

    });
}

function addConMessage(conId, message, userId, token) {
    $.post(
        "https://betterlife.845.co.il/core/services/AjaxApi.php",
        {
            Type: "ConMessage",
            ConId:  conId,
            UserId: userId,
            Message: message.val(),
            Token: token
        }, function (data) {
            var response = jQuery.parseJSON(data);

            if(response.Error) {
                $('#messageEmpty').text('ההודעה לא מכילה תוכן');
                return;
            }
            let sexImg = "";
            if(response.Sex == 0)
                sexImg = "male1.jpg";
            else
                sexImg = "female2.jpg";
            let comment = `<li class='self'>
                                <div class='avatar'><img src='../media/characters/${sexImg}'/></div>
                                <div class='msg'>
                                    <h6>${response.FirstName}</h6>
                                    <p>${message.val()}</p>
                                    <time>${response.Timestamp}</time>
                                </div>
                            </li>`;

            message.val('');
            $('.chat').append(comment);
        });
}

function loadMessages(conId, userId, token) {
    setInterval(function(){
        $.post(
            "https://betterlife.845.co.il/core/services/AjaxApi.php",
            {
                Type: "LoadMessages",
                ConId:  conId,
                UserId: userId,
                Token: token
            }, function (data) {
                var response = jQuery.parseJSON(data);
                $('.chat').html(response.Messages);
            });
    }, 3000);
}

function qrCodeStore(qrCode) {

    $.post( "https://betterlife.845.co.il/core/services/AjaxApi.php",
        {
            Type: "qrCodeStore",
            QrCode: qrCode,
        }
    );

    var interval = setInterval(checkQr, 2000);
    var counter = 0;
    function checkQr() {
        if(counter > 60)
            clearInterval(interval);

        counter++;
        qrCodeCheck(qrCode);
    }
}

function qrCodeCheck(qrCode) {
    $.post( "https://betterlife.845.co.il/core/services/AjaxApi.php",
        {
            Type: "qrCodeCheck",
            QrCode: qrCode,
        },
        function (data) {
            var response = jQuery.parseJSON(data);
            if(response.UserToken != 'noToken'){
                $("#qrLogin").append(`<input id='userTokenInput' name="userTokenInput" type='hidden' value=${response.UserToken}>`);
                $("#qrLogin").submit();
            }
        }
    );

}