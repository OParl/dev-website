<?php namespace App\Http\ViewComposers;

use App\Model\Post;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class NewsArchive
{
    public function compose(View $view)
    {
        $archiveList = app('cache')->remember('news.archive', 300, function () {
            return $this->generateArchiveList();
        });

        return $view->with(compact('archiveList'));
    }

    /**
     * @return array
     **/
    protected function generateArchiveList()
    {
        $posts = Post::published()->get();

        try
        {
            $year = app('cache')->remember('news.archive.starting_year', 300, function () {
                $oldest = Post::published()->min('published_at');
                $year = Carbon::createFromFormat('Y-m-d H:i:s', $oldest)->year;

                return $year;
            });
        } catch (\Exception $e) {
            return [];
        }

        $archiveList = [];

        foreach ($posts as $post) {
            if ($post->published_at->year > $year) {
                $year = $post->published_at->year;
            }

            $numericMonth = $post->published_at->month;

            $month = null;

            if (!isset($archiveList[$year][$numericMonth])) {
                $month = [
                    'name' => $post->published_at->formatLocalized('%B'),
                    'numeric' => sprintf('%02d', $numericMonth),
                    'count' => 0
                ];
            } else {
                $month = $archiveList[$year][$numericMonth];
            }

            $month['count']++;

            $archiveList[$year][$numericMonth] = $month;
        }

        krsort($archiveList);

        return $archiveList;
    }
}
