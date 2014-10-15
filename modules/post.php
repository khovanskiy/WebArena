<?
WebPage::gi()->beginSet(WebPage::CONTENT);
$post_id = Request::get("post_id");
$sth = Database::gi()->execute("select posts.*, posts.creation_time as post_creation_time, users.login from posts, users where posts.user_id =  users.user_id and posts.post_id = ?", array($post_id));
$current_post = $sth->fetch(PDO::FETCH_ASSOC);

WebPage::gi()->set(WebPage::TITLE, $current_post["title"]);
?>
<script>
    function votePost(vote_type) {
        $.ajax({
            type: "post",
            url: "/ajax.php?act=vote-post",
            traditional: true,
            data: {"post_id": <?=$current_post["post_id"];?>, "vote_type": vote_type},
            success: function(e) {
                alert("Voted! " + e);
                var obj = $.parseJSON(e);
            }
        });
    }
</script>
<div class="container">
    <div class="default_page post_page">
        <article>
            <h1><?=$current_post["title"];?></h1>
            <? if (!empty($current_post["content_url"])) { ?>
                <? if ($current_post["content_type"] == 0) { ?>
                    <div class="video">
                        <iframe width="100%" height="400px" src="//www.youtube-nocookie.com/embed/<?=$current_post["content_url"];?>" frameborder="0" allowfullscreen></iframe>
                    </div>
                <? } else if ($current_post["content_type"] == 1) {?>
                    <div class="video">
                        <iframe src="//player.vimeo.com/video/<?=$current_post["content_url"];?>" width="100%" height="400px" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </div>
                <? } else if ($current_post["content_type"] == 2) { ?>
                    <div class="video">
                        <iframe width="100%" height="400px" src="//coub.com/embed/<?=$current_post["content_url"];?>?muted=false&autostart=false&originalSize=false&hideTopBar=true&startWithHD=false" allowfullscreen="allowfullscreen" frameborder="0"></iframe>
                    </div>
                <? } ?>
            <? } else if (!empty($current_post["thumbnail_url"])) { ?>
                <div class="thumbnail">
                    <img src="<?=$current_post["thumbnail_url"];?>"/>
                </div>
            <? } ?>
            <div class="text"><?=$current_post["cached_text"];?></div>
            <section>
                <h2>Оставить комментарий</h2>
                <? if (Account::isAuth()) { ?>
                <div class="comment_add_form">
                    <form action="/transition.php?act=add-comment" method="post">
                        <input type="hidden" name="post_id" value="<?=$current_post["post_id"];?>"/>
                        <div class="row">
                            <label>Сообщение</label>
                            <textarea name="comment_text"></textarea>
                        </div>
                        <button type="submit">Отправить</button>
                    </form>
                </div>
                <? } else { ?>
                    <div class="message primary-message">
                        Только зарегистрированные пользователи могут оставлять комментарии. <br/><a href="/auth/">Войдите, пожалуйста.</a>
                    </div>
                <? } ?>
                <h2>Комментарии</h2>
                <div class="comments">
                    <?
                        $sth = Database::gi()->execute("select users.user_id, users.login, comments.comment_id, comments.user_id, comments.creation_time as comment_creation_time, comments.cached_text as comment_cached_text from comments, users where comments.post_id = ? and comments.user_id = users.user_id", array($current_post["post_id"]));
                        while ($comment_row = $sth->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <div class="comment_row" id="comment_<?=$comment_row["comment_id"];?>">
                                <div class="img fl_l"><a href="/community/<?=$comment_row["login"];?>"><img src="/design/images/example.jpg" style="width:100%;"/></a></div>
                                <?/* if ($row['id_user']==$_SESSION['my']['id_user'] || $_SESSION['my']['id_user']==$this->getOwner()) { ?>
                                    <div class='actions fl_r'><a href='javascript:void(0);' onClick="wall('<?=$row['id_wall'];?>').deleteComment('<?=$row['id_comment'];?>');">удалить</a></div>
                                <? } */?>
                                <div class="info">
                                    <div class="name"><a href="/community/<?=$comment_row["login"];?>"><?=$comment_row["login"];?></a></div>
                                    <div class="text"><?=$comment_row["comment_cached_text"];?></div>
                                    <div><span class="time"><?=decorateDatetime($comment_row["comment_creation_time"]);?></span></div>
                                </div>
                            </div>
                            <?
                        }
                    ?>
                </div>
            </section>
        </article>
        <aside>
            <div class="post_description">
                Опубликовано <?=decorateDatetime($current_post["post_creation_time"]);?>
            </div>
            <div class="author_badge">
                <div class="login">
                    <a href="/community/<?=$current_post["login"];?>"><?=$current_post["login"];?></a>
                </div>
                <div class="author_description">
                    <div class="fl_l" style="color:#fff; font-size: 1.4em">
                        <div style="padding: 0 5px 5px 0;">Рейтинг: 0</div>
                        <div style="padding: 0 5px 5px 0;">Кол-во постов: 0</div>
                    </div>
                    <div class="avatar">
                        <img src="/design/images/example.jpg"/>
                    </div>
                </div>
            </div>
            <?
            $sth = Database::gi()->execute("select user_id from users_votes where user_id = ? and post_id = ?", array(Account::getCurrent()->getId(), $post_id));
            $current_vote = $sth->fetch(PDO::FETCH_ASSOC);
            ?>
            <? if ($sth->rowCount() > 0) { ?>
                <div class="header">
                    Вы оценили материал
                </div>
            <? } else if (Account::isAuth()) { ?>
                <div class="header">
                    Оцените материал
                </div>
                <div class="vote_actions">
                    <div>
                        <a href="javascript:void(0);" class="vote_up_button" onclick="votePost('up');"></a>
                    </div>
                    <div>
                        <a href="javascript:void(0);" class="vote_down_button" onclick="votePost('down');"></a>
                    </div>
                </div>
            <? } ?>
        </aside>
    </div>
</div>
<?
WebPage::gi()->endSet();