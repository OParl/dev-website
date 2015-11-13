<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewsControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testIndexWithoutPosts()
    {
        $this->visit('/')->see('Leider sind keine Nachrichten für diese Anzeige vorhanden.');
    }

    public function testIndexWithSinglePost()
    {
        $user = factory(App\Model\User::class)->create();
        $post = factory(App\Model\Post::class)->create();

        $user->posts()->save($post);

        $this->visit('/')
            ->see($post->title)
            ->see($user->name)
            ->see('Ein Projekt von:');
    }

    public function testIndexShowsPagination()
    {
        $user = factory(App\Model\User::class)->create();
        factory(App\Model\Post::class, 30)->create();

//        $this->visit('/')
//            ->see();
    }
}
