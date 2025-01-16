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
            'banquette[image]' => 'Testing',
            'banquette[finition]' => 'Testing',
            'banquette[tissu]' => 'Testing',
            'banquette[usagetxt]' => 'Testing',
            'banquette[materiaux]' => 'Testing',
            'banquette[temp]' => 'Testing',
            'banquette[recouverture]' => 'Testing',
            'banquette[largeur]' => 'Testing',
            'banquette[profondeur]' => 'Testing',
            'banquette[hauteur]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Banquette();
        $fixture->setTitle('My Title');
        $fixture->setImage('My Title');
        $fixture->setFinition('My Title');
        $fixture->setTissu('My Title');
        $fixture->setUsagetxt('My Title');
        $fixture->setMateriaux('My Title');
        $fixture->setTemp('My Title');
        $fixture->setRecouverture('My Title');
        $fixture->setLargeur('My Title');
        $fixture->setProfondeur('My Title');
        $fixture->setHauteur('My Title');

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
        $fixture->setImage('Value');
        $fixture->setFinition('Value');
        $fixture->setTissu('Value');
        $fixture->setUsagetxt('Value');
        $fixture->setMateriaux('Value');
        $fixture->setTemp('Value');
        $fixture->setRecouverture('Value');
        $fixture->setLargeur('Value');
        $fixture->setProfondeur('Value');
        $fixture->setHauteur('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'banquette[title]' => 'Something New',
            'banquette[image]' => 'Something New',
            'banquette[finition]' => 'Something New',
            'banquette[tissu]' => 'Something New',
            'banquette[usagetxt]' => 'Something New',
            'banquette[materiaux]' => 'Something New',
            'banquette[temp]' => 'Something New',
            'banquette[recouverture]' => 'Something New',
            'banquette[largeur]' => 'Something New',
            'banquette[profondeur]' => 'Something New',
            'banquette[hauteur]' => 'Something New',
        ]);

        self::assertResponseRedirects('/banquette/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getImage());
        self::assertSame('Something New', $fixture[0]->getFinition());
        self::assertSame('Something New', $fixture[0]->getTissu());
        self::assertSame('Something New', $fixture[0]->getUsagetxt());
        self::assertSame('Something New', $fixture[0]->getMateriaux());
        self::assertSame('Something New', $fixture[0]->getTemp());
        self::assertSame('Something New', $fixture[0]->getRecouverture());
        self::assertSame('Something New', $fixture[0]->getLargeur());
        self::assertSame('Something New', $fixture[0]->getProfondeur());
        self::assertSame('Something New', $fixture[0]->getHauteur());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Banquette();
        $fixture->setTitle('Value');
        $fixture->setImage('Value');
        $fixture->setFinition('Value');
        $fixture->setTissu('Value');
        $fixture->setUsagetxt('Value');
        $fixture->setMateriaux('Value');
        $fixture->setTemp('Value');
        $fixture->setRecouverture('Value');
        $fixture->setLargeur('Value');
        $fixture->setProfondeur('Value');
        $fixture->setHauteur('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/banquette/');
        self::assertSame(0, $this->repository->count([]));
    }
}
