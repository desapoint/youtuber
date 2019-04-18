<?php
function getYoutubeIframe($url) {
    if (preg_match('/(?:\/\/)?(?:[w]{3}\.|[w]{0})youtu(?:(?:\.be)|(?:be\.com))\//', $url)) {
        //single video
        $matches = array();
        if (preg_match('/(?:\/\/)?(?:[w]{3}\.|[w]{0})youtu(?:(?:\.be)|(?:be\.com))\/(?:(?:embed\/|(?:attribution_link\?[a-zA-Z0-9\=\-\&]+\/))?(?:(?:v\/|watch\/|watch\?(?:v\=)?|v=)?(?:feature\=[A-Za-z0-9\_\-]+\&v\=)?)){1}(?:(?:user|channel|playlist|results)\S+)*([a-zA-Z\_0-9\-]*)/', $url, $matches)) {
            if (!empty($matches[1]))
                return '<iframe src="https://www.youtube.com/embed/'.$matches[1].'" frameborder="0"></iframe>';
        }
        //user
        $matches = array();
        if (preg_match('/(?:\/\/)?(?:[w]{3}\.|[w]{0})youtu(?:(?:\.be)|(?:be\.com))\/(?:(?:embed\/|(?:attribution_link\?[a-zA-Z0-9\=\-\&]+\/))?(?:(?:v\/|watch\/|watch\?(?:v\=)?|v=)?(?:feature\=[A-Za-z0-9\_\-]+\&v\=)?)){1}(?:(?:user)\/)([^\/\s]+)/', $url, $matches)) {
            if (!empty($matches[1]))
                return '<iframe src="https://www.youtube.com/embed/?listType=user_uploads&list='.$matches[1].'" frameborder="0"></iframe>';
        }
        //playlist
        $matches = array();
        if (preg_match('/(?:\/\/)?(?:[w]{3}\.|[w]{0})youtu(?:(?:\.be)|(?:be\.com))\/(?:(?:embed\/|(?:attribution_link\?[a-zA-Z0-9\=\-\&]+\/))?(?:(?:v\/|watch\/|watch\?(?:v\=)?|v=)?(?:feature\=[A-Za-z0-9\_\-]+\&v\=)?)){1}(?:(?:playlist\?list\=))([^\/\s]+)/', $url, $matches)) {
            if (!empty($matches[1]))
                return '<iframe src="https://www.youtube.com/embed/?listType=playlist&list='.$matches[1].'" frameborder="0"></iframe>';
        }
        //recherche
        $matches = array();
        if (preg_match('/(?:\/\/)?(?:[w]{3}\.|[w]{0})youtu(?:(?:\.be)|(?:be\.com))\/(?:(?:embed\/|(?:attribution_link\?[a-zA-Z0-9\=\-\&]+\/))?(?:(?:v\/|watch\/|watch\?(?:v\=)?|v=)?(?:feature\=[A-Za-z0-9\_\-]+\&v\=)?)){1}(?:(?:results\?search_query\=))([^\/\s]+)/', $url, $matches)) {
            if (!empty($matches[1]))
                return '<iframe src="https://www.youtube.com/embed/?listType=search&list='.$matches[1].'" frameborder="0"></iframe>';
        }

        return null;

    }

    return null;
}

function getYoutubeId($url) {
    if (!empty($url)) {
        preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
        if (!empty($matches)) {
            return $matches[0];
        }
    }
    return false;
}

function getThumbnail($url) {
    $id = getYoutubeId($url);
    $urls = ["maxresdefault.jpg", "hqdefault.jpg", "0.jpg", "mqdefault.jpg", "1.jpg", "2.jpg", "3.jpg"];
    if (!empty($id)) {
        $image = null;
        foreach ($urls as $url) {
            $handle = curl_init();
            curl_setopt($handle, CURLOPT_URL,            "http://img.youtube.com/vi/".$id."/".$url);
            curl_setopt($handle, CURLOPT_HEADER,         true);
            curl_setopt($handle, CURLOPT_NOBODY,         true);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_TIMEOUT,        10);
            curl_exec($handle);
            if (curl_getinfo($handle, CURLINFO_HTTP_CODE) == "200") {
                $image = $url;
                break;
            }
        }
        return "http://img.youtube.com/vi/".$id."/".$image;
    } else {
        return null;
    }
}

function saveThumbnailFromMp3($fileInfo, $subfolder = null) {
    if (!isset($subfolder))
        $subfolder="thumbnails";
    $folder = pathinfo($fileInfo)["dirname"];
    $filename = pathinfo($fileInfo)["filename"];

    $cmd = 'eyeD3 -Q --write-image "'.$folder."/".$subfolder.'" "'.$fileInfo.'"';
    $data = array();
    exec($cmd, $data);
    $renamedFile = null;
    foreach($data as $value) {
        if (strpos($value, 'Writing') !== false) {
            $value = str_replace("\\", "/", $value);
            $value = preg_replace('/\S+\s([A-Za-z]\:[\S\s]+)\.\.\./', '$1', $value);
            $renamedFile = preg_replace('/([A-Za-z]\:[\S\s]+)(\/[A-Za-z\-\_0-9]+\.)\s([A-Za-z]+)/', '$1/'.$filename.'.$3', $value);
            rename($value, $renamedFile);
        }
    }

    return $renamedFile;
}

function hasThumbnail($fileInfo) {
    $folder = pathinfo($fileInfo)["dirname"];
    $filename = pathinfo($fileInfo)["filename"];

    $cmd = 'eyeD3 -Q --write-image "'.$folder.'" "'.$fileInfo.'"';
    $data = array();
    exec($cmd, $data);
    if (empty($data)) {
        return false;
    } else {
        foreach($data as $value) {
            if (strpos($value, 'Writing') !== false) {
                $value = str_replace("\\", "/", $value);
                $value = preg_replace('/\S+\s([A-Za-z]\:[\S\s]+)\.\.\./', '$1', $value);
                unlink($value);
            }
        }
        return true;
    }
}

function saveFromUrl($url, $name = null, $path = null) {
    if (!isset($path))
        $path = ROOT.'/download/';
    else
        $path = ROOT.(!startsWith($path, "/")?"/":null).$path;

    if (!endsWith($path, "/"))
        $path .= "/";

    if (!is_dir($path))
        mkdir($path);

    if (!isset($name))
        $name = pathinfo($url)["filename"];

    file_put_contents($path.$name.'.'.pathinfo($url)["extension"],file_get_contents($url));
}

function endsWith($haystack = null, $needle = null) {
    $length = strlen($needle);
    if (substr($haystack, -$length, $length) == $needle)
        return true;
    return false;
}

function startsWith($haystack = null, $needle = null) {
    $length = strlen($needle);
    if (substr($haystack, 0, $length) == $needle)
        return true;
    return false;
}
