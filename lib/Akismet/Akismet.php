<?php namespace EFrane\Akismet;

use Guzzle\Http\Client;

class Akismet 
{
  const API_VERSION = '1.1';

  const SPAM = true;
  const HAM  = false;

  protected $key      = '';
  protected $homepage = '';

  protected $verified = false;

  protected $client = null;

  protected $debug = false;

  protected $proTip = '';

  public function __construct($key, $homepage, $debug = false)
  {
    $this->key      = $key;
    $this->homepage = $homepage;
    $this->debug    = $debug;

    $this->client = new Client(['base_url' => "https://{$key}.rest.akismet.com/".Akismet::API_VERSION."/"]);
  }

  /**
   * Check a comment for ham or spam.
   *
   * This method automatically sets the blog url, user ip, user agent, referrer and character set.
   * It is however possible to override any of the allowed fields.
   *
   * Additionally, requests will be send in debug mode if the object was initialized with debug=true.
   *
   * @param array $data
   **/
  public function checkComment(array $data)
  {
    // reset pro tip
    $this->proTip = '';

    $this->verifyKey();

    $data = $this->prepareData($data);

    /* @var \Guzzle\Http\Message\Response $response */
    $response = $this->client->post('comment-check', ['body' => $data]);

    $success = ((String)$response->getBody() == 'true') ? Akismet::SPAM : Akismet::HAM;

    if ($response->hasHeader('X-akismet-pro-tip'))
      $this->proTip = $response->getHeader('X-akismet-pro-tip');

    return $success;
  }

  public function submitSpam($data)
  {
    return $this->submit($data, 'spam');
  }

  public function submitHam($data)
  {
    return $this->submit($data, 'ham');
  }

  public function getProTip()
  {
    return $this->proTip;
  }

  public function hasProTip()
  {
    return strlen($this->proTip) > 0;
  }

  protected function verifyKey() {
    if (!$this->verified)
    {
      $response = $this->client->post('https://rest.akismet.com/'.Akismet::API_VERSION.'/verify-key', [
        'body' => [
          'key' => $this->key,
          'blog' => $this->homepage
        ]
      ]);

      if (intval($response->getHeader('content-length')) !== 5)
        throw new \RuntimeException("Invalid Akismet API key!");
    }

    $this->verified = true;
  }

  protected function submit(array $data, $type = 'spam')
  {
    $this->verifyKey();

    $data = $this->prepareData($data);

    $response = $this->client->post("submit-{$type}", ['body' => $data]);

    return (String)$response->getBody();
  }

  protected function prepareData(array $data)
  {
    $allowedDataFields = [
      'blog',
      'user_ip',
      'user_agent',
      'referrer',
      'permalink',
      'comment_type',
      'comment_author',
      'comment_author_email',
      'comment_author_url',
      'comment_content',
      'comment_date_gmt',
      'comment_post_modified_gmt',
      'blog_lang',
      'blog_charset',
      'user_role',
      'is_test',
    ];

    // check for a valid keyset

    foreach (array_keys($data) as $key) {
      if (!in_array($key, $allowedDataFields))
        throw new \RuntimeException("Invalid data key " . $key);
    }

    // set some default keys

    $ip = (isset($_SERVER['REMOTE_ADDR'])) ?     $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
    $ua = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $rf = (isset($_SERVER['HTTP_REFERER'])) ?    $_SERVER['HTTP_REFERER'] : '';

    if (!isset($data['blog']))         $data['blog'] = $this->homepage;
    if (!isset($data['user_ip']))      $data['user_ip'] = $ip;
    if (!isset($data['user_agent']))   $data['user_agent'] = $ua;
    if (!isset($data['referrer']))     $data['referrer'] = $rf;
    if (!isset($data['comment_type'])) $data['comment_type'] = 'comment';
    if (!isset($data['blog_charset'])) $data['blog_charset'] = 'utf-8';

    if ($this->debug) {
      $data['is_test'] = true;
      $data['test_discard'] = true;

      return $data;
    }

    return $data;
  }
}