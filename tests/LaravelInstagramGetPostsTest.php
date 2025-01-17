<?php

namespace TPoeschl\LaravelInstagram\Tests;

use GuzzleHttp\Psr7\Response;
use Http\Mock\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TPoeschl\LaravelInstagram\InstagramPost;
use TPoeschl\LaravelInstagram\LaravelInstagram;
use TPoeschl\LaravelInstagram\LaravelInstagramException;
use Vinkla\Instagram\Instagram;

class LaravelInstagramGetPostsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $response = new Response(200, [], json_encode([
            'data' => range(0, 19),
            'meta' => [],
        ]));
        $client = new Client();
        $client->addResponse($response);
        $instagram = new Instagram('jerryseinfield', $client);
        LaravelInstagram::updateCache($instagram);
    }

    use RefreshDatabase;

    /** @test */
    public function it_gets_all_posts_if_null_is_given()
    {
        $posts = LaravelInstagram::getPosts();
        $this->assertCount(InstagramPost::all()->count(), $posts);
    }

    /** @test */
    public function it_get_the_asked_posts_quantity()
    {
        $number = rand(1, 20);
        $posts = LaravelInstagram::getPosts($number);
        $this->assertCount($number, $posts);
    }

    /** @test */
    public function it_throws_an_exception_if_negative()
    {
        $this->expectException(LaravelInstagramException::class);
        $number = rand(-1, -20);
        $posts = LaravelInstagram::getPosts($number);
    }
}
