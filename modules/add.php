<?
WebPage::gi()->beginSet(WebPage::CONTENT);

import("package/OEmbedParser.php");

?>
    <div class="post_choose_page">
        <h2>Выбор</h2>
    </div>
    <div class="post_edit_page">
        <h2>Добавление нового текстового поста</h2>
        <form action="/transition.php?act=add-post" method="post">
            <input type="hidden" name="type" value="1"/>
            <div class="row title">
                <label>Название</label>
                <input type="text" name="title"/>
            </div>

            <div class="row text">
                <label>Текст</label>
                <textarea name="meta_text">
                </textarea>
            </div>

            <div class="row">
                <button type="submit">Запостить</button>
            </div>
        </form>
    </div>
<?
WebPage::gi()->endSet();
/*
<div class="form">
        <form>
            <div class="row">
                <div class="label">
                    Название
                </div>
                <div class="input">
                    <input type="text"/>
                </div>
            </div>
            <div class="row">
                <div class="label">
                    Изображение
                </div>
                <div class="input">
                    <input type="text"/>
                </div>
            </div>
            <div class="row">
                <div class="label">
                    Видео
                </div>
                <div class="input">
                    <input type="text"/>
                </div>
            </div>
        </form>
    </div>
 */