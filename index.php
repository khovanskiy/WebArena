<?
session_start();

ini_set('display_errors','on');
error_reporting(E_ALL & ~E_NOTICE);

require "config.php";
require "functions.php";

import("package/Buffer.php");
import("package/Request.php");
import("package/Cookie.php");
import("package/Database.php");
import("package/WebPage.php");
import("package/Account.php");

$current = WebPage::gi();
$current->setModule(Request::get("module", "index"));
$current->setTemplate("default");
$current->generate();