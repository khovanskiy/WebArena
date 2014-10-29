<?
import("package/PostStacker.php");

WebPage::gi()->set(WebPage::TITLE, "Лента - WebArena");
WebPage::gi()->beginSet(WebPage::CONTENT);

$current_page = (int)Request::get("page", 0);
if ($current_page == 0) {
    $current_page = 1;
}
$sorting = Request::get("sorting", "popular", array("popular", "fresh"));

?>
    <div class="posts_stream_page">
        <div class="filter_bar">
            <div class="container">
                <nav>
                    <ul>
                        <li class="<?=($sorting == "popular" ? "active_item" : "");?>"><a href="<?=URL::getCurrent()->setParam("sorting", "popular")->removeParam("page")->relative();?>">Популярное</a></li>
                        <li class="<?=($sorting == "fresh" ? "active_item" : "")?>"><a href="<?=URL::getCurrent()->setParam("sorting", "fresh")->removeParam("page")->relative();?>">Свежее</a></li>
                    </ul>
                </nav>
            </div>
        </div>


        <div class="container">
            <?
            $sth = null;
            switch ($sorting)
            {
                case "popular":
                {
                    $sth= Database::gi()->execute("select posts.type, posts.*, posts.creation_time as post_creation_time, users.login from posts, users where posts.user_id =  users.user_id and posts.popular_page = ? order by posts.popularity desc", array($current_page));
                    //$sth = Database::gi()->execute("select posts.*, posts.creation_time as post_creation_time, users.login, ((positive + 1.9208) / (positive + negative) - 1.96 * SQRT((positive * negative) / (positive + negative) + 0.9604) / (positive + negative)) / (1 + 3.8416 / (positive + negative)) AS ci_lower_bound from posts, users where posts.user_id =  users.user_id order by ci_lower_bound desc limit 100");
                } break;
                case "fresh":
                {
                    $sth= Database::gi()->execute("select posts.type, posts.*, posts.creation_time as post_creation_time, users.login from posts, users where posts.user_id =  users.user_id and posts.fresh_page = ? order by posts.creation_time desc", array($current_page));
                } break;

            }
            $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

            function densityUp(array &$density, $pattern)
            {
                switch (count($pattern)) {
                    case 1:
                    {
                        $density[PostStacker::TO_4_RULES]++;
                    }
                        break;
                    case 2:
                    {
                        $density[PostStacker::TO_22_RULES]++;
                    }
                        break;
                    case 3:
                    {
                        $density[PostStacker::TO_121_RULES]++;
                    }
                        break;
                    case 4:
                    {
                        $density[PostStacker::MAX_DENSITY_RULES]++;
                    }
                        break;
                }
            }

            $offset = 0;
            $read_count = 0;

            $density = array(0, 0, 0, 0);
            $pattern = PostStacker::getInstance()->match($rows, 0, 0);
            densityUp($density, $pattern);
            while (($read_count = buildGrid($rows, $offset, $pattern)) > 0) {
                $offset += $read_count;

                $min = $density[0];
                $current_density = 0;
                for ($i = 0; $i < count($density); ++$i) {
                    if ($min > $density[$i]) {
                        $min = $density[$i];
                        $current_density = $i;
                    }
                }
                $pattern = PostStacker::getInstance()->match($rows, $offset, $current_density);
                densityUp($density, $pattern);
            }
            ?>
            <?
            $sth = null;
            switch ($sorting)
            {
                case "popular":
                {
                    $sth= Database::gi()->execute("select max(popular_page) as max_page from posts");
                } break;
                case "fresh":
                {
                    $sth= Database::gi()->execute("select max(fresh_page) as max_page from posts");
                } break;

            }
            $row = $sth->fetch(PDO::FETCH_ASSOC);
            generateMenu($current_page, (int)$row["max_page"]);
            ?>
        </div>
    </div>
<?
WebPage::gi()->endSet();