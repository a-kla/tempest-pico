<?php

/**
 *  @var string|null $title The webpage's title
 * 
 * ERROR: Tempest use it's own x-base => renamed to x-mybase
 **/

?>
<!doctype html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>{{$title}}</title>
    <x-slot name="head">
        <meta name="robots" content="noindex,nofollow">
    </x-slot>
</head>

<body>
    <x-slot />
</body>

</html>