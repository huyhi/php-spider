<?php
namespace App;


class Spider
{
  static public function run()
  {
    $config = require('config.php');
    $url = $config['root'] . $config['class'][0] . 'page/';

    for($i = 1; $i < $config['page']; $i++){
      $urls = getPerPageUrls($url . $i);
      foreach($urls as $item){
        getImgsPerPage($item);
      }
    }
  }
}