<?php

header('Content-Type: application/json');

$url = $_GET['url'] ?? null;

if (!$url) {
    echo json_encode(['error' => 'URL not provided']);
    exit;
}

$feed = simplexml_load_file($url);

$newsArray = [];

foreach ($feed->channel->item as $item) {
    $newsArray[] = [
        'title' => (string) $item->title,
        'link' => (string) $item->link,
        'description' => (string) $item->description,
        'pubDate' => (string) $item->pubDate
    ];
}

echo json_encode($newsArray);

?>
