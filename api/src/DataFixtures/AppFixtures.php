<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use App\Entity\User;
use DateTime;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('Marcelo');
        $user->setPassword('$2y$13$VitRjBn0.ka9bXZIWuAE3uyb.4zbTJlFENFhBMR6LzkiJWZZxjcI.');
        $user->setRoles(['ROLE_USER']);

        $secondUser = new User();
        $secondUser->setUsername('Hernan');
        $secondUser->setPassword('$2y$13$VitRjBn0.ka9bXZIWuAE3uyb.4zbTJlFENFhBMR6LzkiJWZZxjcI.');
        $secondUser->setRoles(['ROLE_USER']);
        
        $product = new Product();
        $product->setSku('SKU_123');
        $product->setProductName('First Product');
        $product->setDescription('My very first product. Look at it! Isn\'t it cute?');
        $product->setOwner($user);
        
        $secondProduct = new Product();
        $secondProduct->setSku('SKU_1234');
        $secondProduct->setProductName('Second Product');
        $secondProduct->setDescription('My second product. We\'re improving');
        $secondProduct->setOwner($user);

        $thirdProduct = new Product();
        $thirdProduct->setSku('SKU_12345');
        $thirdProduct->setProductName('Third Product');
        $thirdProduct->setDescription('This might be too much');
        $thirdProduct->setOwner($secondUser);
        
        $manager->persist($user);
        $manager->persist($product);
        $manager->persist($secondProduct);

        $manager->persist($secondUser);
        $manager->persist($thirdProduct);

        $manager->flush();
    }
}
