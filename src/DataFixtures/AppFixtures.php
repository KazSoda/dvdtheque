<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Category;
use App\Entity\Customer;
use App\Entity\Meeting;
use App\Entity\Product;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        //  Creating users
        $user1 = new User();
        $user1
            ->setUsername('Test')
            ->setPassword($this->hasher->hashPassword($user1, '123'));
        $manager->persist($user1);

        //  Creating store data
        $faker = Factory::create();

        $categories = [];
        $authors = [];
        $articles = [];
        $products = [];
        $customers = [];

        //  Categories
        for($i=0; $i<=25; $i++){
            $category = new Category;
            $category->setName($faker->realText(15));
            $manager->persist($category);
            array_push($categories,$category);
        }

        //  Authors
        for($i=0; $i<=40; $i++){
            $author = new Author;
            $author->setName($faker->realText(25));
            $manager->persist($author);
            array_push($authors,$author);
        }

        //  Articles
        for($i=0; $i<=100; $i++){
            $article = new Article;
            $article->setName($faker->realText(25))
                ->setYear(random_int(1920,2025))
                ->setCategory($faker->randomElement($categories))
                ->setAuthor($faker->randomElement($authors));
            $manager->persist($article);
            array_push($articles,$article);
        }

        //  Products
        for($i=0; $i<=300; $i++){
            $product = new Product;
            $product->setName($faker->realText(25))
                ->setContent($faker->randomElement($articles))
                ->setPrice(rand(100,9999)/10)
                ->setQuantity(rand(0,10));
            $manager->persist($product);
            array_push($products,$product);
        }

        //  Customers
        for($i=0; $i<=500; $i++){
            $customer = new Customer;
            $customer->setName($faker->realText(25));
            //  Add bits of bought items for every customer
            for($j=0; $j<=5; $j++){
                $customer->addProductsCurrentlyOwned($faker->randomElement($products));
            }
            // Adding at least one user to access the store
            if($i===0) {
                $customer->setUsername($user1);
            }
            $manager->persist($customer);
            array_push($customers,$customer);
        }

        //  Meetings
        for($i=0; $i<=800; $i++){
            $meeting = new Meeting();
            $customer = $faker->randomElement($customers);
            $meeting->setCustomer($customer);
            $date = new DateTime("2025-01-01 00:00:00");
            $meeting->setDate($date);
            $manager->persist($meeting);
        }

        $manager->flush();
    }
}
