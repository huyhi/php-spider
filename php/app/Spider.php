<?php
namespace App;


class Spider
{
  static public function run()
  {
    $config = require('config.php');

    foreach($config['class'] as $class){

      $pages = $config['page'] ? $config['page'] : getPageCounts($config['root'] . $class);

      $url = $config['root'] . $class . 'page/';

      for($i = 1; $i < $pages; $i++){
        $urls = getPerPageUrls($url . $i);
        foreach($urls as $item){
          getImgsPerPage($item);
        }
      }

    }
  }
}