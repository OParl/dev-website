<?php

use EFrane\HubSync\RepositoryVersions;

/**
 * Class RepositoryVersionsTest
 * @after RepositoryTest
 */
class RepositoryVersionsTest extends TestCase
{
    protected $remoteURI = 'tests/assets/test.git';

    protected $localName = 'test';

    /**
     * @var \EFrane\HubSync\Repository
     */
    protected $repo;

    public function setUp()
    {
        parent::setUp();

        $fs = $this->app->make(\Illuminate\Contracts\Filesystem\Filesystem::class);
        $this->repo = new \EFrane\HubSync\Repository($fs, $this->localName, $this->remoteURI);
        $this->repo->update();
    }

    public function tearDown()
    {
        $this->repo->remove();
    }

    public function testCreates()
    {
        $sut = new RepositoryVersions($this->repo);
        $this->assertInstanceOf(RepositoryVersions::class, $sut);

        $sut = RepositoryVersions::forRepository($this->repo);
        $this->assertInstanceOf(RepositoryVersions::class, $sut);
    }

    /**
     * @covers RepositoryVersions::loadVersions
     */
    public function testGetAll()
    {
        $sut = new RepositoryVersions($this->repo);
        $versions = $sut->getAll();
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $versions);
        $this->assertGreaterThanOrEqual(2, $versions->count());
    }

    public function testGetLatestForMatchingConstraint()
    {
        $this->repo->update();
        $sut = new RepositoryVersions($this->repo);

        $this->assertEquals('master', $sut->getLatestMatchingConstraint('master'));
        $this->assertEquals('v1.0.1', $sut->getLatestMatchingConstraint('~1.0'));

        $this->expectException(UnexpectedValueException::class);
        $sut->getLatestMatchingConstraint('');

        $this->expectException(UnexpectedValueException::class);
        $sut->getLatestMatchingConstraint(null);
    }
}