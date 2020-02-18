function addComment(articleId, userId, content, token) {
    $.post( "core/services/AjaxApi.php",
        {
            Type: "ArticleLike",
            UserId: userId,
            ArticleId:  articleId,
            Content: content,
            Token: token
        }, function (data) {
            likeButton.html(data + " <i class='fas fa-thumbs-up' style='margin-bottom: 2px' ></i>");
        });
}