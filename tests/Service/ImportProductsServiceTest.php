<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Service\ImportProductsService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ImportProductsServiceTest extends TestCase
{
    public function testCreateProductsFromFile()
    {
        $filePath = 'path/to/test/file.csv';

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $repository = $this->createMock(\Doctrine\Persistence\ObjectRepository::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->with(Product::class)
            ->willReturn($repository);

        $service = new ImportProductsService($entityManager);

        $file = $this->createMock(\stdClass::class);
        $file->expects($this->exactly(3))
            ->method('fgets')
            ->willReturnOnConsecutiveCalls(
                "Product1;12345678\n",
                "Product2;87654321\n",
                false
            );
        $file->expects($this->once())
            ->method('eof')
            ->willReturn(true);
        $file->expects($this->once())
            ->method('rewind');

        $exception = new \Exception('Wrong file extension, use CSV file!');

        $entityManager->expects($this->exactly(2))
            ->method('persist');
        $entityManager->expects($this->once())
            ->method('flush');
        $entityManager->expects($this->once())
            ->method('remove')
            ->with($file);

        $this->assertEquals(
            "Successfully added product: Product1\nSuccessfully added product: Product2\n",
            $service->createProductsFromFile($filePath)
        );

        $file->expects($this->once())
            ->method('fgets')
            ->willReturn("Product3;12345\n");

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $service->createProductsFromFile($filePath);
    }
}
