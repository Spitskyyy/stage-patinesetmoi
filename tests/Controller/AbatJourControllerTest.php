<?php

namespace App\Tests\Controller;

use App\Entity\AbatJour;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AbatJourControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/abat/jour/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(AbatJour::class);

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
        self::assertPageTitleContains('AbatJour index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'abat_jour[title]' => 'Testing',
            'abat_jour[picture]' => 'Testing',
            'abat_jour[width]' => 'Testing',
            'abat_jour[depth]' => 'Testing',
            'abat_jour[height]' => 'Testing',
            'abat_jour[fabric]' => 'Testing',
            'abat_jour[materials]' => 'Testing',
            'abat_jour[choice_of_strucure]' => 'Testing',
            'abat_jour[lampshade_finishes]' => 'Testing',
            'abat_jour[time]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new AbatJour();
        $fixture->setTitle('My Title');
        $fixture->setPicture('My Title');
        $fixture->setWidth('My Title');
        $fixture->setDepth('My Title');
        $fixture->setHeight('My Title');
        $fixture->setFabric('My Title');
        $fixture->setMaterials('My Title');
        $fixture->setChoice_of_strucure('My Title');
        $fixture->setLampshade_finishes('My Title');
        $fixture->setTime('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('AbatJour');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new AbatJour();
        $fixture->setTitle('Value');
        $fixture->setPicture('Value');
        $fixture->setWidth('Value');
        $fixture->setDepth('Value');
        $fixture->setHeight('Value');
        $fixture->setFabric('Value');
        $fixture->setMaterials('Value');
        $fixture->setChoice_of_strucure('Value');
        $fixture->setLampshade_finishes('Value');
        $fixture->setTime('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'abat_jour[title]' => 'Something New',
            'abat_jour[picture]' => 'Something New',
            'abat_jour[width]' => 'Something New',
            'abat_jour[depth]' => 'Something New',
            'abat_jour[height]' => 'Something New',
            'abat_jour[fabric]' => 'Something New',
            'abat_jour[materials]' => 'Something New',
            'abat_jour[choice_of_strucure]' => 'Something New',
            'abat_jour[lampshade_finishes]' => 'Something New',
            'abat_jour[time]' => 'Something New',
        ]);

        self::assertResponseRedirects('/abat/jour/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getPicture());
        self::assertSame('Something New', $fixture[0]->getWidth());
        self::assertSame('Something New', $fixture[0]->getDepth());
        self::assertSame('Something New', $fixture[0]->getHeight());
        self::assertSame('Something New', $fixture[0]->getFabric());
        self::assertSame('Something New', $fixture[0]->getMaterials());
        self::assertSame('Something New', $fixture[0]->getChoice_of_strucure());
        self::assertSame('Something New', $fixture[0]->getLampshade_finishes());
        self::assertSame('Something New', $fixture[0]->getTime());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new AbatJour();
        $fixture->setTitle('Value');
        $fixture->setPicture('Value');
        $fixture->setWidth('Value');
        $fixture->setDepth('Value');
        $fixture->setHeight('Value');
        $fixture->setFabric('Value');
        $fixture->setMaterials('Value');
        $fixture->setChoice_of_strucure('Value');
        $fixture->setLampshade_finishes('Value');
        $fixture->setTime('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/abat/jour/');
        self::assertSame(0, $this->repository->count([]));
    }
}
