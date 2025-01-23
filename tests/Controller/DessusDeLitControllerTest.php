<?php

namespace App\Tests\Controller;

use App\Entity\DessusDeLit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DessusDeLitControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/dessus/de/lit/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(DessusDeLit::class);

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
        self::assertPageTitleContains('DessusDeLit index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'dessus_de_lit[title]' => 'Testing',
            'dessus_de_lit[usetxt]' => 'Testing',
            'dessus_de_lit[length]' => 'Testing',
            'dessus_de_lit[width]' => 'Testing',
            'dessus_de_lit[lining]' => 'Testing',
            'dessus_de_lit[fabric]' => 'Testing',
            'dessus_de_lit[bedspread_finishes]' => 'Testing',
            'dessus_de_lit[time]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new DessusDeLit();
        $fixture->setTitle('My Title');
        $fixture->setUsetxt('My Title');
        $fixture->setLength('My Title');
        $fixture->setWidth('My Title');
        $fixture->setLining('My Title');
        $fixture->setFabric('My Title');
        $fixture->setBedspread_finishes('My Title');
        $fixture->setTime('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('DessusDeLit');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new DessusDeLit();
        $fixture->setTitle('Value');
        $fixture->setUsetxt('Value');
        $fixture->setLength('Value');
        $fixture->setWidth('Value');
        $fixture->setLining('Value');
        $fixture->setFabric('Value');
        $fixture->setBedspread_finishes('Value');
        $fixture->setTime('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'dessus_de_lit[title]' => 'Something New',
            'dessus_de_lit[usetxt]' => 'Something New',
            'dessus_de_lit[length]' => 'Something New',
            'dessus_de_lit[width]' => 'Something New',
            'dessus_de_lit[lining]' => 'Something New',
            'dessus_de_lit[fabric]' => 'Something New',
            'dessus_de_lit[bedspread_finishes]' => 'Something New',
            'dessus_de_lit[time]' => 'Something New',
        ]);

        self::assertResponseRedirects('/dessus/de/lit/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getUsetxt());
        self::assertSame('Something New', $fixture[0]->getLength());
        self::assertSame('Something New', $fixture[0]->getWidth());
        self::assertSame('Something New', $fixture[0]->getLining());
        self::assertSame('Something New', $fixture[0]->getFabric());
        self::assertSame('Something New', $fixture[0]->getBedspread_finishes());
        self::assertSame('Something New', $fixture[0]->getTime());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new DessusDeLit();
        $fixture->setTitle('Value');
        $fixture->setUsetxt('Value');
        $fixture->setLength('Value');
        $fixture->setWidth('Value');
        $fixture->setLining('Value');
        $fixture->setFabric('Value');
        $fixture->setBedspread_finishes('Value');
        $fixture->setTime('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/dessus/de/lit/');
        self::assertSame(0, $this->repository->count([]));
    }
}
