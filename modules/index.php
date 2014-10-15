<?
import("package/PostStacker.php");

WebPage::gi()->set(WebPage::TITLE, "Лента - WebArena");
WebPage::gi()->beginSet(WebPage::CONTENT);

?>
    <div class="posts_stream_page">
        <div class="filter_bar">
            <div class="container">
                <nav>
                    <ul>
                        <li><a href="">Популярное</a></li>
                        <li><a href="">Свежее</a></li>
                    </ul>
                </nav>
            </div>
        </div>


        <div class="container">
            <?

            $sth = Database::gi()->execute("select posts.type, posts.*, posts.creation_time as post_creation_time, users.login from posts, users where posts.user_id =  users.user_id order by posts.creation_time desc");
            //$sth = Database::gi()->execute("select posts.*, posts.creation_time as post_creation_time, users.login, ((positive + 1.9208) / (positive + negative) - 1.96 * SQRT((positive * negative) / (positive + negative) + 0.9604) / (positive + negative)) / (1 + 3.8416 / (positive + negative)) AS ci_lower_bound from posts, users where posts.user_id =  users.user_id order by ci_lower_bound desc");
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
        </div>
    </div>
<?
WebPage::gi()->endSet();