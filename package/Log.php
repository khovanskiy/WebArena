<?
class Log {

    public static function d($tag, $message) {
        ?>
        <div style="border-bottom: 1px solid #ddd; padding: 10px; color: #111; font-size: 1.4em;">
            <?=$message;?>
        </div>
        <?
    }
} 