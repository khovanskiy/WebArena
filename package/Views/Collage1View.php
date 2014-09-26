<?php

import("package/Views/View.php");

class Collage1View extends View {

    private $view1;
    private $view2;
    private $view3;
    private $view4;
    private $view5;

    public function __construct(View $view1, View $view2, View $view3, View $view4, View $view5)
    {
        $this->view1 = $view1;
        $this->view2 = $view2;
        $this->view3 = $view3;
        $this->view4 = $view4;
        $this->view5 = $view5;
    }

    public function render()
    {
        ?>
        <table style="width: 100%; height: 500px;">
            <tr>
                <td rowspan="2" style="width: 50%;">
                    <?=$this->view1->render();?>
                </td>
                <td style="width: 25%; height: 50%;">
                    <?=$this->view2->render();?>
                </td>
                <td style="height: 50%;">
                    <?=$this->view3->render();?>
                </td>
            </tr>
            <tr>
                <td style="height: 50%;">
                    <?=$this->view4->render();?>
                </td>
                <td style="height: 50%;">
                    <?=$this->view5->render();?>
                </td>
            </tr>
        </table>
        <?
    }
}