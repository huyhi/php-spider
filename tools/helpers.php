<?php

function httpGet($url){
  $request = curl_init();
  curl_setopt($request, CURLOPT_URL, $url);
  curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($request, CURLOPT_FOLLOWLOCATION, 5);
  $res = curl_exec($request);
  curl_close($request);
  return $res;
}


function getImgsPerPage($url)
{
  $res = httpGet($url);
  $htmlDom = new \HtmlParser\ParserDom($res);

  $breadcrumbs = $htmlDom->find('#breadcrumbs h1', 0)->getPlainText();
  $dirPath = 'img/' . ltrim($breadcrumbs);
  
  if(! is_dir($dirPath)){
    mkdir($dirPath, 0777, true);
    $imgsDom = $htmlDom->find('.single-text p img');
    $imgsCount = count($imgsDom);
  
    foreach($imgsDom as $img){
      static $i = 1;
  
      $imgLink = $img->getAttr('src');
      $binStream = file_get_contents($imgLink);
      $objFile = fopen($dirPath . '/' . $i . '.jpg', 'w');
      fwrite($objFile, $binStream);
      fclose($objFile);
  
      printf("%s %d / %d", $breadcrumbs, $i++, $imgsCount);
    }
  }
}

function getPerPageUrls($url){
  $res = httpGet($url);
  $htmlDom = new \HtmlParser\ParserDom($res);
  $anchors = $htmlDom->find('.bloglist-container article a');
  
  $urls = [];
  foreach($anchors as $anchor){
    array_push($urls, $anchor->getAttr('href'));
  }

  return $urls;
}