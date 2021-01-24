<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slogger;

    /**
     * AppFixtures constructor.
     * @param SluggerInterface $slogger
     */
    public function __construct(SluggerInterface $slogger)
    {
        $this->slogger = $slogger;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        // ajouter une méthode
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        // ne marche pas
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

        // pour ajouter l'image
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));


        // creer des categorées  : 3

        for ($c = 0; $c < 3; $c++) {
            $category = new Category;
            $category->setName($faker->department)  // ca donne une category
            ->setSlug(strtolower($this->slogger->slug($category->getName())));
            $manager->persist($category);

            for ($p = 0; $p < mt_rand(15, 20); $p++) {
                $product = new Product;
                $product->setName($faker->productName)
                    ->setPrice($faker->price(4000, 20000))
                    ->setSlug(strtolower($this->slogger->slug($product->getName())))
                    ->setCategory($category)
                    ->setShortDescription($faker->paragraph)
                    ->setMainPicture($faker->imageUrl(400,400, true));
                $manager->persist($product);
            }
            $manager->flush();
        }
    }
    // resume : creer 3 category aleatoire et pour chaque category cerer entre 15 et 20 produits
}
