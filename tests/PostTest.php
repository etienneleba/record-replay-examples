<?php

namespace App\Tests;

use App\RecordReplay\Mode;
use App\RecordReplay\RecordReplay;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\NativeHttpClient;

class PostTest extends WebTestCase
{
    public function testIndexPage(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();

        // retrieve the current repository implementation
        $postRepository = $container->get(PostRepository::class);

        static::ensureKernelShutdown();

        $client = static::createClient();
        $container = $client->getContainer();

        $recordReplay = new RecordReplay();

        // create a proxy around the current repository implementation
        $postRepository = $recordReplay->createProxy($postRepository);

        // override the current repository implementation with the proxy in the container
        $container->set(PostRepository::class, $postRepository);

        // record all the call to any secondary adapters that have been proxied
        $recordReplay->start("./tests/records/test-index-page.json", Mode::REPLAY);

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $recordReplay->save();
    }


}
