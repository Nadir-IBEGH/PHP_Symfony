<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slogger;
    protected $encoder;

    /**
     * AppFixtures constructor.
     * @param SluggerInterface $slogger
     * +
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(SluggerInterface $slogger, UserPasswordEncoderInterface $encoder)
    {
        $this->slogger = $slogger;
        $this->encoder = $encoder;
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

        $admin = new User();
        $hash = $this->encoder->encodePassword($admin, 'password');
        $admin->setEmail('admin@gmail.com')
            ->setFullName('Admin')
            ->setPassword($hash)
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $users = [];
        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $hash = $this->encoder->encodePassword($user, 'password');
            $user->setEmail("user$u@gmail.com")
                ->setFullName($faker->name())
                ->setPassword($hash);
            $users [] = $user;
            $manager->persist($user);
        }

        // creer des categorées  : 3

        $products = [];
        for ($c = 0; $c < 3; $c++) {
            $category = new Category;
            $category->setName($faker->department)  // ca donne une category
            ->setSlug(strtolower($this->slogger->slug($category->getName())));
            $manager->persist($category);

         //   dd($faker->price(4000, 20000));
            for ($p = 0; $p < mt_rand(15, 20); $p++) {
                $product = new Product;
                $product->setName($faker->productName)
                    ->setPrice($faker->price(40, 200))
                    ->setSlug(strtolower($this->slogger->slug($product->getName())))
                    ->setCategory($category)
                    ->setShortDescription($faker->paragraph)
                    ->setMainPicture($faker->imageUrl(400, 400, true));
                $products[] = $product;
                $manager->persist($product);
            }

            for ($o = 0; $o < mt_rand(20, 40); $o++) {
                $purchase = new Purchase();
                $purchase->setFullName($faker->name)
                    ->setCity($faker->city)
                    ->setPostalCode($faker->postcode)
                    ->setAddress($faker->streetAddress)
                    ->setUser($faker->randomElement($users))
                    ->setTotal(mt_rand(2000, 30000))
                    ->setPurchasedAt($faker->dateTimeBetween('-6 month'));

                $selectedProducts = $faker->randomElements($products, mt_rand(3, 5));

                foreach ($selectedProducts as $product) {
                    /** @var Product $product */
                    $purchaseItem = new PurchaseItem();
                    $purchaseItem
                        ->setProduct($product)
                        ->setProductName($product->getName())
                        ->setProductPrice($product->getPrice())
                        ->setQuantity(mt_rand(1, 5))
                        ->setTotal($purchaseItem->getProductPrice() * $purchaseItem->getQuantity())
                        ->setPurchase($purchase);

                    $manager->persist($purchaseItem);

                }

                if ($faker->boolean(90)) {
                    $purchase->setStatus(Purchase::STATUS_PEND);
                }
                $manager->persist($purchase);
            }
        }

        $manager->flush();
    }
}
