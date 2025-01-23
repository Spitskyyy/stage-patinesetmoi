<?php

namespace App\Tests\Controller;

use App\Entity\ObjetsDeDecoration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ObjetsDeDecorationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/objets/de/decoration/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(ObjetsDeDecoration::class);

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
        self::assertPageTitleContains('ObjetsDeDecoration index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'objets_de_decoration[title]' => 'Testing',
            'objets_de_decoration[picture]' => 'Testing',
            'objets_de_decoration[detail]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new ObjetsDeDecoration();
        $fixture->setTitle('My Title');
        $fixture->setPicture('My Title');
        $fixture->setDetail('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('ObjetsDeDecoration');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new ObjetsDeDecoration();
        $fixture->setTitle('Value');
        $fixture->setPicture('Value');
        $fixture->setDetail('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'objets_de_decoration[title]' => 'Something New',
            'objets_de_decoration[picture]' => 'Something New',
            'objets_de_decoration[detail]' => 'Something New',
        ]);

        self::assertResponseRedirects('/objets/de/decoration/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getPicture());
        self::assertSame('Something New', $fixture[0]->getDetail());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new ObjetsDeDecoration();
        $fixture->setTitle('Value');
        $fixture->setPicture('Value');
        $fixture->setDetail('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/objets/de/decoration/');
        self::assertSame(0, $this->repository->count([]));
    }
}
