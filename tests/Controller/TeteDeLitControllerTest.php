<?php

namespace App\Tests\Controller;

use App\Entity\TeteDeLit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TeteDeLitControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/tete/de/lit/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(TeteDeLit::class);

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
        self::assertPageTitleContains('TeteDeLit index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'tete_de_lit[title]' => 'Testing',
            'tete_de_lit[picture]' => 'Testing',
            'tete_de_lit[width]' => 'Testing',
            'tete_de_lit[height]' => 'Testing',
            'tete_de_lit[fabric]' => 'Testing',
            'tete_de_lit[materials]' => 'Testing',
            'tete_de_lit[support]' => 'Testing',
            'tete_de_lit[headboard_finishes]' => 'Testing',
            'tete_de_lit[time]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new TeteDeLit();
        $fixture->setTitle('My Title');
        $fixture->setPicture('My Title');
        $fixture->setWidth('My Title');
        $fixture->setHeight('My Title');
        $fixture->setFabric('My Title');
        $fixture->setMaterials('My Title');
        $fixture->setSupport('My Title');
        $fixture->setHeadboard_finishes('My Title');
        $fixture->setTime('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('TeteDeLit');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new TeteDeLit();
        $fixture->setTitle('Value');
        $fixture->setPicture('Value');
        $fixture->setWidth('Value');
        $fixture->setHeight('Value');
        $fixture->setFabric('Value');
        $fixture->setMaterials('Value');
        $fixture->setSupport('Value');
        $fixture->setHeadboard_finishes('Value');
        $fixture->setTime('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'tete_de_lit[title]' => 'Something New',
            'tete_de_lit[picture]' => 'Something New',
            'tete_de_lit[width]' => 'Something New',
            'tete_de_lit[height]' => 'Something New',
            'tete_de_lit[fabric]' => 'Something New',
            'tete_de_lit[materials]' => 'Something New',
            'tete_de_lit[support]' => 'Something New',
            'tete_de_lit[headboard_finishes]' => 'Something New',
            'tete_de_lit[time]' => 'Something New',
        ]);

        self::assertResponseRedirects('/tete/de/lit/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getPicture());
        self::assertSame('Something New', $fixture[0]->getWidth());
        self::assertSame('Something New', $fixture[0]->getHeight());
        self::assertSame('Something New', $fixture[0]->getFabric());
        self::assertSame('Something New', $fixture[0]->getMaterials());
        self::assertSame('Something New', $fixture[0]->getSupport());
        self::assertSame('Something New', $fixture[0]->getHeadboard_finishes());
        self::assertSame('Something New', $fixture[0]->getTime());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new TeteDeLit();
        $fixture->setTitle('Value');
        $fixture->setPicture('Value');
        $fixture->setWidth('Value');
        $fixture->setHeight('Value');
        $fixture->setFabric('Value');
        $fixture->setMaterials('Value');
        $fixture->setSupport('Value');
        $fixture->setHeadboard_finishes('Value');
        $fixture->setTime('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/tete/de/lit/');
        self::assertSame(0, $this->repository->count([]));
    }
}
