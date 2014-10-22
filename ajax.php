<?
error_reporting(E_ALL);
session_start();

include("config.php");
include("functions.php");

import("package/Request.php");
import("package/Database.php");
import("package/Account.php");
import("package/Buffer.php");
import("package/OEmbedParser.php");

switch (Request::get("act"))
{
    case "vote-post":
    {
        $response = array();
        if (Account::isAuth()) {
            $post_id = Request::post("post_id");
            $sth = Database::gi()->execute("select post_id, positive, negative from posts where post_id = ?", array($post_id));
            $current_post = $sth->fetch(PDO::FETCH_ASSOC);
            if ($sth->rowCount() > 0) {
                $vote_type = Request::post("vote_type");
                $sth = Database::gi()->execute("select user_id from users_votes where user_id = ? and post_id = ?", array(Account::getCurrent()->getId(), $post_id));
                if ($sth->rowCount() == 0) {
                    if ($vote_type == "up") {
                        Database::gi()->execute("update posts set positive = positive + 1, popularity = ? where post_id = ?", array(getPopularity($current_post["positive"] + 1, $current_post["negative"]), $post_id));
                        Database::gi()->execute("insert into users_votes(user_id, post_id, direction) values(?, ?, ?)", array(Account::getCurrent()->getId(), $post_id, 1));
                        $response["vote_type"] = "up";

                        updatePostsCache();
                    } else if ($vote_type == "down") {
                        Database::gi()->execute("update posts set negative = negative + 1, popularity = ? where post_id = ?", array(getPopularity($current_post["positive"], $current_post["negative"] + 1), $post_id));
                        Database::gi()->execute("insert into users_votes(user_id, post_id, direction) values(?, ?, ?)", array(Account::getCurrent()->getId(), $post_id, -1));
                        $response["vote_type"] = "down";

                        updatePostsCache();
                    } else {

                    }
                } else {

                }
            }
        } else {

        }
        echo json_encode($response);
    } break;
    case "parse-video":
    {
        $response = array();
        if (Account::isAuth()) {
            $url = Request::post("url");
            if (!empty($url)) {
                $parser = new OEmbedParser();
                $video = $parser->parse($url);

                $response["title"] = $video->title;
                $response["content_url"] = $video->content_id;
                $response["thumbnail_url"] = $video->thumbnail_url;

                Buffer::beginRecord("html");
                ?>
                <? if ($video->content_type == 0) { ?>
                    <iframe width="100%" height="100%" src="//www.youtube-nocookie.com/embed/<?=$video->content_id;?>" frameborder="0" allowfullscreen></iframe>
                <? } else if ($video->content_type == 1) {?>
                    <iframe src="//player.vimeo.com/video/<?=$video->content_id;?>" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                <? } else if ($video->content_type == 2) { ?>
                    <iframe width="100%" height="100%" src="//coub.com/embed/<?=$video->content_id;?>?muted=false&autostart=false&originalSize=false&hideTopBar=true&startWithHD=false" allowfullscreen="allowfullscreen" frameborder="0"></iframe>
                <? } ?>
                <?
                Buffer::endRecord();
                $response["html"] = Buffer::get("html");
            } else {

            }
        } else {

        }
        echo json_encode($response);
    } break;
}
?>