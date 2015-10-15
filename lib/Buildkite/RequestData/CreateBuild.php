<?php namespace EFrane\Buildkite\RequestData;

class CreateBuild extends AbstractRequestData
{
    public $commit  = null;
    public $branch  = null;
    public $message = null;

    public $author = null;
    public $env = null;
    public $meta_data = null;
    public $ignore_project_branch_filters = false;

    public function __construct($message, $commit = 'HEAD', $branch = 'master')
    {
        $this->commit = $commit;
        $this->branch = $branch;
        $this->message = $message;
    }

  /**
   * @return null|string
   */
  public function getCommit()
  {
      return $this->commit;
  }

  /**
   * @param null|string $commit
   */
  public function setCommit($commit)
  {
      $this->commit = $commit;
  }

  /**
   * @return null|string
   */
  public function getBranch()
  {
      return $this->branch;
  }

  /**
   * @param null|string $branch
   */
  public function setBranch($branch)
  {
      $this->branch = $branch;
  }

  /**
   * @return null
   */
  public function getMessage()
  {
      return $this->message;
  }

  /**
   * @param null $message
   */
  public function setMessage($message)
  {
      $this->message = $message;
  }

  /**
   * @return null
   */
  public function getAuthor()
  {
      return $this->author;
  }

  /**
   * @param null $author
   */
  public function setAuthor($name, $email)
  {
      $this->author = ['name' => $name, 'email' => $email];
  }

  /**
   * @return null
   */
  public function getEnv()
  {
      return $this->env;
  }

  /**
   * @param null $env
   */
  public function setEnv($env)
  {
      $this->env = $env;
  }

  /**
   * @return null
   */
  public function getMetaData()
  {
      return $this->meta_data;
  }

  /**
   * @param null $meta_data
   */
  public function setMetaData($meta_data)
  {
      $this->meta_data = $meta_data;
  }

  /**
   * @return boolean
   */
  public function isIgnoreProjectBranchFilters()
  {
      return $this->ignore_project_branch_filters;
  }

  /**
   * @param boolean $ignore_project_branch_filters
   */
  public function setIgnoreProjectBranchFilters($ignore_project_branch_filters)
  {
      $this->ignore_project_branch_filters = $ignore_project_branch_filters;
  }
}
