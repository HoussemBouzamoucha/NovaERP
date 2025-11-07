<?php

namespace App\Tests\Controller;

use App\Entity\Inventory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class InventoryControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $inventoryRepository;
    private string $path = '/inventory/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->inventoryRepository = $this->manager->getRepository(Inventory::class);

        foreach ($this->inventoryRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Inventory index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'inventory[itemName]' => 'Testing',
            'inventory[SKU]' => 'Testing',
            'inventory[quantity]' => 'Testing',
            'inventory[price]' => 'Testing',
            'inventory[SupplierName]' => 'Testing',
            'inventory[lastUpdated_at]' => 'Testing',
            'inventory[users]' => 'Testing',
            'inventory[Supplier]' => 'Testing',
            'inventory[Project]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->inventoryRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Inventory();
        $fixture->setItemName('My Title');
        $fixture->setSKU('My Title');
        $fixture->setQuantity('My Title');
        $fixture->setPrice('My Title');
        $fixture->setSupplierName('My Title');
        $fixture->setLastUpdated_at('My Title');
        $fixture->setUsers('My Title');
        $fixture->setSupplier('My Title');
        $fixture->setProject('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Inventory');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Inventory();
        $fixture->setItemName('Value');
        $fixture->setSKU('Value');
        $fixture->setQuantity('Value');
        $fixture->setPrice('Value');
        $fixture->setSupplierName('Value');
        $fixture->setLastUpdated_at('Value');
        $fixture->setUsers('Value');
        $fixture->setSupplier('Value');
        $fixture->setProject('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'inventory[itemName]' => 'Something New',
            'inventory[SKU]' => 'Something New',
            'inventory[quantity]' => 'Something New',
            'inventory[price]' => 'Something New',
            'inventory[SupplierName]' => 'Something New',
            'inventory[lastUpdated_at]' => 'Something New',
            'inventory[users]' => 'Something New',
            'inventory[Supplier]' => 'Something New',
            'inventory[Project]' => 'Something New',
        ]);

        self::assertResponseRedirects('/inventory/');

        $fixture = $this->inventoryRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getItemName());
        self::assertSame('Something New', $fixture[0]->getSKU());
        self::assertSame('Something New', $fixture[0]->getQuantity());
        self::assertSame('Something New', $fixture[0]->getPrice());
        self::assertSame('Something New', $fixture[0]->getSupplierName());
        self::assertSame('Something New', $fixture[0]->getLastUpdated_at());
        self::assertSame('Something New', $fixture[0]->getUsers());
        self::assertSame('Something New', $fixture[0]->getSupplier());
        self::assertSame('Something New', $fixture[0]->getProject());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Inventory();
        $fixture->setItemName('Value');
        $fixture->setSKU('Value');
        $fixture->setQuantity('Value');
        $fixture->setPrice('Value');
        $fixture->setSupplierName('Value');
        $fixture->setLastUpdated_at('Value');
        $fixture->setUsers('Value');
        $fixture->setSupplier('Value');
        $fixture->setProject('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/inventory/');
        self::assertSame(0, $this->inventoryRepository->count([]));
    }
}
