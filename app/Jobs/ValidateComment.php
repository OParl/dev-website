<?php namespace App\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

use App\Model\Comment;

use EFrane\Akismet\Akismet;

class ValidateComment extends Job implements SelfHandling, ShouldQueue
{
	use InteractsWithQueue, SerializesModels;

  protected $comment = null;
  protected $validationData = [];

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Comment $comment)
	{
    $this->comment = $comment;

    $this->validationData = [
      'user_ip'    => $_SERVER['REMOTE_ADDR'],
      'user_agent' => $_SERVER['HTTP_USER_AGENT'],
      'referrer'   => $_SERVER['HTTP_REFERER'],

      'blog_lang'  => 'en',

      'user_role'  => (\Auth::check()) ? 'administrator' : null,
    ];
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
    $data = array_merge($this->validationData, [
      'comment_author'       => $this->comment->author_name,
      'comment_author_email' => $this->comment->author_email,
      'comment_content'      => $this->comment->content,
      'comment_date_gmt'     => $this->comment->created_at->toIso8601String(),
    ]);

    $result = app('EFrane\Akismet\Akismet')->checkComment($data);
    \Log::debug($result);

    switch ($result)
    {
      case Akismet::HAM:
        // handle ham
        $this->comment->status = 'ham';
        break;

      case Akismet::SPAM:
        // handle spam
        $this->comment->status = 'spam';

        if (app('akismet')->hasProTip())
        {
          // TODO: process pro tip app('akismet')->getProTip();
        }

        // TODO: info to admin!

        break;
    }

    $this->comment->save();

    $this->delete();
	}
}
