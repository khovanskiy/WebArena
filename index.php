<?
session_start();

ini_set('display_errors','on');
error_reporting(E_ALL & ~E_NOTICE);

require "config.php";
require "package/Buffer.php";
require "package/Request.php";
require "package/Cookie.php";
require "package/Database.php";
require "package/WebPage.php";
require "package/Account.php";

$current = WebPage::gi();
$current->setModule(Request::get("module"));
$current->setTemplate("default");
$current->generate();