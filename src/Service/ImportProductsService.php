<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class ImportProductsService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $filePath
     * @return string
     */
    public function createProductsFromFile(string $filePath): string
    {
        if(pathinfo($filePath, PATHINFO_EXTENSION) != 'csv') {
            throw new Exception('Wrong file extension, use CSV file!');
        }

        $file = fopen($filePath, 'r');
        $message = '';

        while (($line = fgets($file)) !== false) {
            $data = explode(';', $line);

            $name = $data[0];
            $number = $data[1];

            if(strlen((int)$number) != 8) {
                throw new Exception("Incorrect product number with name: ".$name."!\n");
            }

            $existingProduct = $this->entityManager->getRepository(Product::class)
                ->findOneBy([
                    'name' => $name,
                    'number' => $number
                ]);

            if ($existingProduct) {
                print "The product named: ". $name." and number: ". $number." already exists.\n";
            } else {
                $product = new Product();
                $product->setName($name);
                $product->setNumber($number);

                $this->entityManager->persist($product);

                $message .= "Successfully added product: ". $name ."\n";
            }
        }
        $this->entityManager->flush();

        unlink($filePath);

        return $message;
    }
}