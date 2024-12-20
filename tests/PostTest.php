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

        $postRepository = $container->get(PostRepository::class);

        static::ensureKernelShutdown();

        $client = static::createClient();
        $container = $client->getContainer();

        $recordReplay = new RecordReplay();

        $postRepository = $recordReplay->createProxy($postRepository);

        $container->set(PostRepository::class, $postRepository);

        $recordReplay->start("./tests/records/test-index-page.json", Mode::RECORD);

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $recordReplay->save();
    }


}
