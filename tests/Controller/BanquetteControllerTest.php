<?php

namespace App\Tests\Controller;

use App\Entity\Banquette;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class BanquetteControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/banquette/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Banquette::class);

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
        self::assertPageTitleContains('Banquette index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'banquette[title]' => 'Testing',
            'banquette[picture]' => 'Testing',
            'banquette[usetxt]' => 'Testing',
            'banquette[width]' => 'Testing',
            'banquette[depth]' => 'Testing',
            'banquette[height]' => 'Testing',
            'banquette[covering_or_complete_repair]' => 'Testing',
            'banquette[materials]' => 'Testing',
            'banquette[fabric]' => 'Testing',
            'banquette[finishes]' => 'Testing',
            'banquette[time]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Banquette();
        $fixture->setTitle('My Title');
        $fixture->setPicture('My Title');
        $fixture->setUsetxt('My Title');
        $fixture->setWidth('My Title');
        $fixture->setDepth('My Title');
        $fixture->setHeight('My Title');
        $fixture->setCovering_or_complete_repair('My Title');
        $fixture->setMaterials('My Title');
        $fixture->setFabric('My Title');
        $fixture->setFinishes('My Title');
        $fixture->setTime('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Banquette');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Banquette();
        $fixture->setTitle('Value');
        $fixture->setPicture('Value');
        $fixture->setUsetxt('Value');
        $fixture->setWidth('Value');
        $fixture->setDepth('Value');
        $fixture->setHeight('Value');
        $fixture->setCovering_or_complete_repair('Value');
        $fixture->setMaterials('Value');
        $fixture->setFabric('Value');
        $fixture->setFinishes('Value');
        $fixture->setTime('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'banquette[title]' => 'Something New',
            'banquette[picture]' => 'Something New',
            'banquette[usetxt]' => 'Something New',
            'banquette[width]' => 'Something New',
            'banquette[depth]' => 'Something New',
            'banquette[height]' => 'Something New',
            'banquette[covering_or_complete_repair]' => 'Something New',
            'banquette[materials]' => 'Something New',
            'banquette[fabric]' => 'Something New',
            'banquette[finishes]' => 'Something New',
            'banquette[time]' => 'Something New',
        ]);

        self::assertResponseRedirects('/banquette/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getPicture());
        self::assertSame('Something New', $fixture[0]->getUsetxt());
        self::assertSame('Something New', $fixture[0]->getWidth());
        self::assertSame('Something New', $fixture[0]->getDepth());
        self::assertSame('Something New', $fixture[0]->getHeight());
        self::assertSame('Something New', $fixture[0]->getCovering_or_complete_repair());
        self::assertSame('Something New', $fixture[0]->getMaterials());
        self::assertSame('Something New', $fixture[0]->getFabric());
        self::assertSame('Something New', $fixture[0]->getFinishes());
        self::assertSame('Something New', $fixture[0]->getTime());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Banquette();
        $fixture->setTitle('Value');
        $fixture->setPicture('Value');
        $fixture->setUsetxt('Value');
        $fixture->setWidth('Value');
        $fixture->setDepth('Value');
        $fixture->setHeight('Value');
        $fixture->setCovering_or_complete_repair('Value');
        $fixture->setMaterials('Value');
        $fixture->setFabric('Value');
        $fixture->setFinishes('Value');
        $fixture->setTime('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/banquette/');
        self::assertSame(0, $this->repository->count([]));
    }
}
