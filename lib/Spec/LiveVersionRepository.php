<?php namespace OParl\Spec;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Finder\Finder;

/**
 * Class LiveCopyRepository
 * @package OParl\Spec
 **/
class LiveVersionRepository
{
    /**
     * @const string Livecopy storage path inside the app/storage directory
     */
    const PATH = 'live_version';

    /**
     * @var LiveVersionUpdater
     */
    protected $loader = null;

    /**
     * @var LiveVersionBuilder
     */
    protected $builder = null;

    public function __construct(LiveVersionUpdater $loader, LiveVersionBuilder $builder)
    {
        $this->loader = $loader;
        $this->builder = $builder;
    }

    /**
     * @return mixed
     **/
    public function getRaw()
    {
        return $this->builder->getRaw();
    }

    /**
     * @return string
     **/
    public function getContent()
    {
        return $this->builder->getContent();
    }

    /**
     * @return string
     **/
    public function getNav()
    {
        return $this->builder->getNav();
    }

    /**
     * @return static
     **/
    public function getLastModified()
    {
        return $this->loader->getRepositoryStatus()['last_modified'];
    }

    /**
     * @return string
     **/
    public function getHash()
    {
        return $this->loader->getRepositoryStatus()['hash'];
    }

    /**
     * @return string
     **/
    public static function getChapterPath()
    {
        return storage_path('app/' . LiveVersionRepository::PATH . '/src/');
    }

    /**
     * @return string
     **/
    public static function getImagesPath()
    {
        return storage_path('app/' . LiveVersionRepository::PATH . '/src/images/');
    }

    /**
     * @return string
     **/
    public static function getSchemaPath()
    {
        return storage_path('app/' . LiveVersionRepository::PATH . '/schema/');
    }

    /**
     * @return string
     **/
    public static function getExamplesPath()
    {
        return storage_path('app/' . LiveVersionRepository::PATH . '/examples/');
    }

    /**
     * @return string
     **/
    public static function getLiveVersionPath()
    {
        return storage_path('app/' . LiveVersionRepository::PATH . '/out/live.html');
    }
}
