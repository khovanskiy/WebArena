<?
error_reporting(0);
session_start();

include "config.php";
include "functions.php";

import("package/Request.php");
import("package/Database.php");
import("package/WebPage.php");
import("package/Account.php");

import("package/OEmbedParser.php");
import("package/Parsedown.php");

$webpage = Webpage::gi();

switch (Request::get("act")) {

    case "add-comment":
    {
        $post_id = Request::post("post_id", 0);
        if ($post_id == 0) {
            WebPage::gi()->redirect("/");
        }
        if (!Account::isAuth()) {
            WebPage::gi()->redirect("/post-" . $post_id);
        }
        $meta_text = Request::post("comment_text");
        if (empty($meta_text)) {
            WebPage::gi()->redirect("/post-" . $post_id);
        }
        $sth = Database::gi()->execute("select post_id from posts where post_id = ?", array($post_id));
        if ($sth->rowCount() == 0) {
            WebPage::gi()->redirect("/");
        }
        $cached_text = Parsedown::instance()->text(htmlspecialchars($meta_text));
        $sth = Database::gi()->execute("insert into comments (user_id, post_id, meta_text, cached_text, creation_time) values(?, ?, ?, ?, now())", array(Account::getCurrent()->getId(), $post_id, $meta_text, $cached_text));

        WebPage::gi()->redirect("/post-" . $post_id . "#comment_" . Database::gi()->lastInsertId("comment_id"));
    } break;

    case "add-post":
    {
        if (!Account::isAuth()) {
            WebPage::gi()->redirect("/");
        }

        $title = Request::post("title");
        $type = (int)Request::post("type");
        $meta_text = Request::post("meta_text", "");
        $content_url = Request::post("content_url", "");
        $tags = parseTags(Request::post("tags"));

        $post_id = 0;
        if ($type == 1) {
            $post_id = addTextPost($title, $meta_text, $content_url);
        } else if ($type == 2) {

        } else if ($type == 4) {
            $post_id = addVideoPost($title, $meta_text, $content_url);
        } else {
            WebPage::gi()->redirect("/");
        }
        addTags($post_id, $tags);
        updatePostsCache();
        WebPage::gi()->redirect("/post-" . $post_id);
    } break;

    case "auth":
    {
        $login = Request::post("login");
        $password = Request::post("password");
        if (!empty($login) && !empty($password)) {

            if (Account::auth($login, $password)) {
                $webpage->redirect("/");
            } else {
                $webpage->redirect("/auth/?status=1");
            }
        } else {
            $webpage->redirect("/auth/?status=2");
        }
    } break;

    case "exit":
    {
        session_destroy();
        header("Location: /");
    } break;

    case "registration":
    {
        $login = trim(Request::post("login"));
        $password = Request::post("password");
        $email = Request::post("email");

        if (!empty($login)) {
            $_SESSION["module-join"]["login"] = $login;
            if (!empty($email)) {
                $_SESSION["module-join"]["email"] = $email;
                if (!empty($password)) {
                    $res = Database::gi()->execute("select * from users where login = ? or email = ?", array($login, $email));

                    if ($res->rowCount() == 0) {
                        Database::gi()->execute("insert into users (login, password, email, creation_time) values(?, ?, ?, now())", array($login, $password, $email));
                        Account::auth($login, $password);
                        $webpage->redirect("/");
                    } else {
                        $row = $res->fetch(PDO::FETCH_ASSOC);
                        if ($row["login"] == $login) {
                            $webpage->redirect("/join/?status=5");
                        } else {
                            $webpage->redirect("/join/?status=4");
                        }
                    }
                } else {
                    $webpage->redirect("/join/?status=3");
                }
            } else {
                $webpage->redirect("/join/?status=2");
            }
        } else {
            $webpage->redirect("/join/?status=1");
        }

    } break;

    case "upload-photo":
    {
        if (Account::isAuth()) {
            if (isset($_FILES['image'])) {
                $id_user = Account::getCurrent()->get("id_user");

                $temp_path = $_SERVER['DOCUMENT_ROOT'] . "/temp/" . $id_user . ".jpg";
                $full_path = $_SERVER['DOCUMENT_ROOT'] . USERS_AVATARS_PATH . $id_user . ".jpg";

                if (move_uploaded_file($_FILES['image']['tmp_name'], $temp_path)) {
                    $image = new SimpleImage($temp_path);
                    $image->resizeToWidth(200);
                    $image->save($full_path);

                    unlink($temp_path);
                    $webpage->redirect("/profile/?status=1");
                }
            } else {
                $webpage->redirect("/profile/?status=2");
            }
        } else {
            $webpage->redirect("/");
        }
    }
        break;
    case "update-page":
    {
        if (Account::isAuth()) {
            $id_page = Request::post("id_page");
            if (!empty($id_page)) {
                $title = Request::post("title");
                $text = Request::post("text");
                $keywords = Request::post("keywords");
                $description = Request::post("description");
                $public = Request::post("public") != "" ? 1 : 0;

                Database::gi()->query("update pages set title=" . Database::gi()->quote($title) . ", text=" . Database::gi()->quote($text) . ",`date`=now(), public=" . $public . ",keywords=" . Database::gi()->quote($keywords) . ",description=" . Database::gi()->quote($description) . " where id_page='$id_page'");

                $webpage->redirect("/page-" . $id_page . "?act=edit&status=1");
            } else {
                $webpage->redirect("/");
            }
        } else {
            $webpage->redirect("/");
        }
    }
        break;
    case "update-news":
    {
        if (Account::isAuth()) {
            $id_news = Request::post("id_news");
            if (!empty($id_news)) {
                $title = Request::post("title");
                $text = Request::post("text");
                $id_type = Request::post("id_type", 1);

                Database::gi()->query("update news set id_type = " . $id_type . ", title = " . Database::gi()->quote($title) . ", text = " . Database::gi()->quote($text) . " where id_news='$id_news'");

                $webpage->redirect("/news-" . $id_news . "?act=edit&status=1");
            } else {
                $webpage->redirect("/");
            }
        } else {
            $webpage->redirect("/");
        }
    }
        break;

    default:
        {
        echo "unknown request";
        }
        break;
}