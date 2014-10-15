<?
WebPage::gi()->beginSet(WebPage::CONTENT);
$post_id = Request::get("post_id");
$sth = Database::gi()->execute("select posts.*, posts.creation_time as post_creation_time, users.login from posts, users where posts.user_id =  users.user_id and posts.post_id = ?", array($post_id));
$current_post = $sth->fetch(PDO::FETCH_ASSOC);
?>
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
                <h2>Комментарии</h2>
            </section>
        </article>
        <aside>
            <?=$current_post["login"];?>
        </aside>
    </div>
</div>
<?
WebPage::gi()->endSet();