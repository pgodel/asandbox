<?php

class aSoundCloud extends aEmbedService
{
  protected $features = null;
  protected $apiUrl = null;
  protected $consumerKey = null;
  protected $showTypes = null;

  // Constructor for aSlideShare (obtains API key and shared secret)
  public function __construct()
  {
    $settings = sfConfig::get('app_a_soundcloud');
    
    $this->features = array('thumbnail', 'search', 'browseUser');
    $this->apiUrl = 'http://api.soundcloud.com/';
        
    if (isset($settings['consumerKey']))
    {
      $this->consumerKey = $settings['consumerKey'];
    }
  }

  public function configured()
  {
    if (isset($this->consumerKey))
    {
      return true;
    }
    
    return false;
  }
  
  public function configurationHelpUrl()
  {
    return 'http://trac.apostrophenow.org/wiki/EmbedSoundCloud';
  }
  
  public function supports($feature)
  {
    return in_array($feature, $this->features);
  }

  public function search($q, $page=1, $perPage=50)
  {
    /* SoundCloud returns a maximum of 50 items per set. If $perPage > 50,
     * their API will still return only 50 items. */
    $call = 'tracks';
    $offset = ($page-1)*$perPage; // Search doesn't support pages, so we must calculate an offset
    $params = array('q' => $q, 'offset' => $offset, 'limit' => 50, 'order' => 'hotness');
    
    return $this->searchApi($call, $params, $perPage);
  }
  
  public function browseUser($user, $page=1, $perPage=50)
  {
    $user_id = $this->getIdFromName($user);
    $call = "users/$user_id/tracks";
    $offset = ($page-1)*$perPage; // Search by user doesn't support page and items_per_page, so we must calculate an offset
    $params = array('limit' => 50, 'offset' => $offset);
    
    return $this->searchApi($call, $params, $perPage, true);
  }
  
  public function embed($id, $width, $height, $title='', $wmode='opaque', $autoplay=false)
  {
return <<<EOT
<object height="$height" width="$width">
    <param name="movie" value="http://player.soundcloud.com/player.swf?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F$id"></param>
    <param name="allowscriptaccess" value="always"></param>
    <embed allowscriptaccess="always" height="81" src="http://player.soundcloud.com/player.swf?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F$id" type="application/x-shockwave-flash" width="100%"></embed>
</object>
EOT;
  }
  
  public function getInfo($id)
  {
    $data = $this->getTrackInfo($id);
    
    if ($data)
    {
      return array('id' => $data['id'],
           'url' => $data['url'],
           'title' => $data['title'],
           'description' => html_entity_decode($data['description'], ENT_COMPAT, 'UTF-8'),
           'tags' => $data['tags'],
           'credit' => $data['credit']);
    }
    
    return false;
  }
  
  public function getUserInfo($user)
  {
    $user_id = $this->getIdFromName($user);
    $call = "users/$user_id";
    $data = $this->getData($call);
    
    if (!$data)
    {
      return array('name' => $user, 'description' => '');
    }
    
    $data = new SimpleXMLElement($data);
    
    return array('name' => $user, 'description' => $data->user->description);
  }
  
  public function getIdFromUrl($url)
  {
    // Apostrophe calls both this and the getIdFromEmbed method,
    // let's make sure it happens in the cheapest order, avoiding
    // unnecessary API calls and ruling out situations where we
    // would have a false positive on an embed code as a URL
    $id = $this->getIdFromEmbed($url);
    
    if ($id)
    {
      return $id;
    }
    
    if (strpos($url, 'soundcloud.com') !== false)
    {
      $call = 'resolve';
      $params = array('url' => $url);
            
      $data = $this->getData($call, $params);
      
      if (!$data)
      {
        return false;
      }
      
      $data = new SimpleXMLElement($data);
      
      return $data->id;
    }
    
    return false;
  }
  
  public function getUrlFromId($id)
  {
    $trackInfo = $this->getTrackInfo($id);
    
    if ($trackInfo)
    {
      return $trackInfo['url'];
    }
    
    return false;
  }
  
  public function getIdFromEmbed($embed)
  {
    if (preg_match('/http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F(\d+)/', $embed, $matches))
    {
      return $matches[1];
    }
    
    return false;
  }
  
  public function getThumbnail($id)
  {
    $trackInfo = $this->getTrackInfo($id);
    
    if (isset($trackInfo['thumbnail']) && (strlen($trackInfo['thumbnail']) > 0))
    {
      return $trackInfo['thumbnail'];
    }
    
    return false;
  }
  
  public function getName()
  {
    return 'SoundCloud';
  }

  // This is the only function that actually talks to the SoundCloud API
  private function getData($call, $params=array())
  {
    try
    {
      $params['consumer_key'] = $this->consumerKey;
      $url = $this->apiUrl . "$call?" . http_build_query($params);
      
      $result = file_get_contents($url);
    }
    catch (Exception $e)
    {
      return false;
    }
    
    if ($result)
    {
      return utf8_encode($result);
    }
    
    return false;
  }
  
  private function searchApi($call, $params, $limit, $browseUser=false)
  {
    $soundTracks = array();
    
    $data = $this->getData($call, $params);
    
    // If our API call fails, return false so we don't error on our foreach() call
    if (!$data)
    {
      return false;
    }
    
    $data = new SimpleXMLElement($data);
    
    foreach ($data->track as $track)
    {
      $soundTracks[] = array(
                   'id' => (int) $track->id,
                       'url' => (string) $track->{'permalink-url'},
                       'title' => (string) $track->title,
                       'description' => (string) $track->description
                       );
    }
    
    // Since SoundCloud doesn't provide a total number of search results, we must paginate ourselves
    $tracks = array_slice($soundTracks, $params['offset'], $limit);
    
    return array('total' => count($soundTracks), 'results' => $tracks);
  }
  
  /*
   * Returns the appropriate ID for a given user or track name
   * 
   * Input can be either 'username' or 'username/trackname'
   */
  private function getIdFromName($name) {
    return $this->getIdFromUrl("http://soundcloud.com/$name");
  }
  
  // Will retrieve sound track from given ID or URL (intelligently decides which to use)
  private function getTrackInfo($id)
  {
    // Check if we have the media cached before hitting the API
    $cacheKey = "get-trackinfo:$id";
    $trackInfo = $this->getCached($cacheKey);
    
    if (!is_null($trackInfo))
    {
      return $trackInfo;
    }
    
    // If a URL was passed in, obtain the correct ID
    if (strpos($id, 'http://') !== false && preg_match('/soundcloud.com\/(\s+)/', $id, $matches))
    {
      $id = $this->getIdFromName($matches[1]);
    }
    
    if (!$id)
    {
      return false;
    }

    $call = "tracks/$id";
    $data = $this->getData($call);
    
    if (!$data)
    {
      return false;
    }
    
    $data = new SimpleXMLElement($data);
    
    $trackInfo = array(
           'id' => (int) $data->id,
           'url' => (string) $data->{'permalink-url'},
           'title' => (string) $data->title,
           'description' => (string) $data->description,
           'tags' => str_replace(' ', ',', $data->{'tag-list'}),
           'credit' => (string) $data->user->username,
           'thumbnail' => (string) $data->{'waveform-url'}
         );
    
    // Cache this media for a day to reduce our API hits
    $this->setCached($cacheKey, $trackInfo, aEmbedService::SECONDS_IN_DAY);
    
    return $trackInfo;
  }
 
}

?>
