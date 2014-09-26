<?
WebPage::gi()->set(WebPage::TITLE, "Ресурсы для разработчиков");
WebPage::gi()->beginSet(WebPage::CONTENT);

import("package/Views/ImageWidgetView.php");
import("package/Views/VideoWidgetView.php");
import("package/Views/Collage1View.php");
import("package/Views/Collage2View.php");

$image = new ImageWidgetView();
$video = new VideoWidgetView();

$v1 = new Collage1View($video, $image, $image, $image, $image);
$v1->render();

$v2 = new Collage2View($image, $image, $video, $image, $image);
$v2->render();

?>

<?
/*$sth = Database::gi()->execute("select * from pages where 1 oder by desc");
while ($row = $sth->fetch(PDO::FETCH_ASSOC))
{

}*/

WebPage::gi()->endSet();