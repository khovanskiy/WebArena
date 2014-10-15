<?
WebPage::gi()->set(WebPage::TITLE, "Список тегов");
WebPage::gi()->beginSet(WebPage::CONTENT);
$sth = Database::gi()->execute("select tags.* from tags order by name", null);
$tagArraySortedByName = $sth->fetchAll();
$sth = Database::gi()->execute("select tags.* from tags order by posts_total_count desc", null);
$tagArraySortedByPostsCount = $sth->fetchAll();
$sth = Database::gi()->execute("select tags.* from tags order by posts_best_count desc", null);
$tagArraySortedByBestPostsCount = $sth->fetchAll(); ?>
	
	<script>
        function chooseSorting(sorting_id) {
            for (i = 0; i < 3; ++i) {
                $("#tag_sorted_block_" + i).css("display", "none");
                $("#action_cell_" + i).removeClass("active_cell");
            }
            $("#tag_sorted_block_" + sorting_id).css("display", "block");
            $("#action_cell_" + sorting_id).addClass("active_cell");
        }
    </script>
	<div class="container">
        <div class="sorted_tags_page">
            <div style="width: 100%; height: 10px; background: #358cce;">

            </div>
            <div class="edit_bar">
                <div class="actions_grid">
                    <div class="action_cell" id="action_cell_0">
                        <a href="javascript: void(0);" onclick="chooseSorting(0);"><span class="fui-new"></span>По алфавиту</a>
                    </div>
                    <div class="action_cell" id="action_cell_1">
                        <a href="javascript: void(0);" onclick="chooseSorting(1);"><span class="fui-new"></span>По всем постам</a>
                    </div>
                    <div class="action_cell" id="action_cell_2">
                        <a href="javascript: void(0);" onclick="chooseSorting(2);"><span class="fui-new"></span>По лучшим постам</a>
                    </div>
                </div>
            </div>
            <div class="tag_sorted_block" id="tag_sorted_block_0">
                <h2>Все теги по алфавиту</h2>
				<?  $tagArray = $tagArraySortedByName;
					$tagArrayLength = count($tagArray); ?>
				<? for ($i = 0; $i < $tagArrayLength; $i++) { ?>
					<? $tag = $tagArray[$i]; ?>
					<span class = "tag"><?= $tag['name'] ?></span><br>
				<? } ?>                
            </div>
			<div class="tag_sorted_block" id="tag_sorted_block_1">
                <h2>Все теги по всем постам</h2>
				<?  $tagArray = $tagArraySortedByPostsCount;
					$tagArrayLength = count($tagArray); ?>
				<? for ($i = 0; $i < $tagArrayLength; $i++) { ?>
					<? $tag = $tagArray[$i]; ?>
					<span class = "tag"><?= $tag['name'] ?></span><br>
				<? } ?>                
            </div>
            <div class="tag_sorted_block" id="tag_sorted_block_2">
                <h2>Все теги по лучшим постам</h2>
				<?  $tagArray = $tagArraySortedByBestPostsCount;
					$tagArrayLength = count($tagArray); ?>
				<? for ($i = 0; $i < $tagArrayLength; $i++) { ?>
					<? $tag = $tagArray[$i]; ?>
					<span class = "tag"><?= $tag['name'] ?></span><br>
				<? } ?>
            </div>
        </div>
    </div>
	<script>
		chooseSorting(0);
	</script>
<?
WebPage::gi()->endSet();