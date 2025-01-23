<?php

namespace App\Tests\Controller;

use App\Entity\MisesEnScene;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MisesEnSceneControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/mises/en/scene/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(MisesEnScene::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('MisesEnScene index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'mises_en_scene[title]' => 'Testing',
            'mises_en_scene[picture]' => 'Testing',
            'mises_en_scene[detail]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new MisesEnScene();
        $fixture->setTitle('My Title');
        $fixture->setPicture('My Title');
        $fixture->setDetail('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('MisesEnScene');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new MisesEnScene();
        $fixture->setTitle('Value');
        $fixture->setPicture('Value');
        $fixture->setDetail('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'mises_en_scene[title]' => 'Something New',
            'mises_en_scene[picture]' => 'Something New',
            'mises_en_scene[detail]' => 'Something New',
        ]);

        self::assertResponseRedirects('/mises/en/scene/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getPicture());
        self::assertSame('Something New', $fixture[0]->getDetail());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new MisesEnScene();
        $fixture->setTitle('Value');
        $fixture->setPicture('Value');
        $fixture->setDetail('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/mises/en/scene/');
        self::assertSame(0, $this->repository->count([]));
    }
}
