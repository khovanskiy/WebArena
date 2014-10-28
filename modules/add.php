<?
WebPage::gi()->beginSet(WebPage::CONTENT);
?>

    <script>
        function choosePostEditBlock(block_id) {
            for (i = 0; i < 3; ++i) {
                $("#post_edit_block_" + i).css("display", "none");
                $("#action_cell_" + i).removeClass("active_cell");
            }
            $("#post_edit_block_" + block_id).css("display", "block");
            $("#action_cell_" + block_id).addClass("active_cell");
        }
        function preloadVideo(url) {
            $.ajax({
                type: "post",
                url: "/ajax.php?act=parse-video",
                traditional:true,
                data: {"url": url},
                success: function(e) {
                    var obj = $.parseJSON(e);
                    $("#post_title").val(obj["title"]);
                    $("#post_preview").html(obj["html"]);
                }
            });
        }
    </script>

    <div class="container">
        <div class="default_page post_edit_page">
            <div class="edit_bar">
                <div class="actions_grid">
                    <div class="action_cell" id="action_cell_0">
                        <a href="javascript: void(0);" onclick="choosePostEditBlock(0);"><span class="fui-new"></span> Текстовой пост</a>
                    </div>
                    <!--<div class="action_cell" id="action_cell_1">
                        <a href="javascript: void(0);" onclick="choosePostEditBlock(1);"><span class="fui-photo"></span> Графический пост</a>
                    </div>-->
                    <div class="action_cell" id="action_cell_2">
                        <a href="javascript: void(0);" onclick="choosePostEditBlock(2);"><span class="fui-video"></span> Мультимедийный пост</a>
                    </div>
                </div>
            </div>
            <div class="post_edit_block" id="post_edit_block_0">
                <h2>Добавление нового текстового поста</h2>

                <form action="/transition.php?act=add-post" method="post">
                    <input type="hidden" name="type" value="1"/>

                    <div class="row title">
                        <label>Название</label>
                        <input type="text" name="title"/>
                    </div>

                    <div class="row text">
                        <label>Текст</label>
                        <textarea name="meta_text"></textarea>
                    </div>

                    <div class="row text">
                        <label>Теги</label>
                        <input type="text" name="tags"/>
                    </div>

                    <div class="row">
                        <button type="submit" class="submit_button">Запостить</button>
                    </div>
                </form>
            </div>
            <div class="post_edit_block" id="post_edit_block_2">
                <h2>Добавление нового видео поста</h2>

                <form action="/transition.php?act=add-post" method="post">
                    <input type="hidden" name="type" value="4"/>

                    <div style="overflow: auto; height: 350px; padding: 0 0 10px 0;">
                        <div class="video" id="post_preview">

                        </div>
                        <div class="fl_l" style="width: 50%; height: 100%; padding: 0 0 0 10px;">
                            <div class="row title">
                                <label>Ссылка на видео</label>
                                <input type="text" name="content_url" onchange="preloadVideo(this.value);"/>
                            </div>

                            <div class="row title">
                                <label>Название</label>
                                <input type="text" name="title" id="post_title"/>
                            </div>

                            <div class="row title" style="padding: 0;">
                                <label>Поддерживаются</label>
                                <table style="width: 100%; table-layout: fixed;">
                                    <tr>
                                        <td>
                                            <img src="/design/images/logo-youtube.png" style="width: 100%;"/>
                                        </td>
                                        <td>
                                            <img src="/design/images/logo-vimeo.png" style="width: 100%;"/>
                                        </td>
                                        <td>
                                            <img src="/design/images/logo-coub.png" style="width: 100%;"/>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row text">
                        <label>Описание</label>
                        <textarea name="meta_text"></textarea>
                    </div>

                    <div class="row text">
                        <label>Теги</label>
                        <input type="text" name="tags"/>
                    </div>

                    <div class="row">
                        <button type="submit">Запостить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script>
    choosePostEditBlock(0);
</script>
<?
WebPage::gi()->endSet();