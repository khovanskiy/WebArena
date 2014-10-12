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