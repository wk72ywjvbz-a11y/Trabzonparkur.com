<?php
header('Content-Type: text/html; charset=utf-8');
header('X-Robots-Tag: noindex, nofollow');
$html = file_get_contents(__DIR__.'/index.html');
echo str_replace('<html lang="tr">','<html lang="tr" data-live>',$html);
