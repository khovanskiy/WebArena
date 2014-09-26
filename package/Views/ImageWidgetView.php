<?php

import("package/Views/View.php");

class ImageWidgetView extends View {

    public function render()
    {
        ?>
        <div style="width: 100%; height: 100%; border: 1px solid #ddd; background: url('../design/images/example.jpg'); background-size: cover;">
            <span style="color: #fff; opacity: 0.9;text-shadow: 1px 1px 5px #000;font-size:3em;">Hello, World!</span>
        </div>
        <?
    }
}