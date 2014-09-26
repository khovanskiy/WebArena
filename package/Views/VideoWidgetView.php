<?php

import("package/Views/View.php");

class VideoWidgetView extends View {
    public function render()
    {
        ?>
        <iframe width="100%" height="100%" src="//www.youtube.com/embed/mmf0KAHWlnk" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
        <?
    }
}