<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\ResolvedFormTypeFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/{slug}", name="product_category")
     * @param $slug
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy(
            ['slug' => $slug]
        );
        if (!$category) {
            throw  $this->createNotFoundException("La categorie demandé n'existe pas");
        };

        return $this->render('product/category.html.twig', [
            'category' => $category
        ]);
    }

    /**
     * @Route("/{category_slug}/{product_slug}",  name="product_show" )
     * @param $product_slug
     * @param ProductRepository $productRepository
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     */

    public function showProduct($product_slug, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy(
            ['slug' => $product_slug]
        );

        if (!$product) {
            throw  $this->createNotFoundException("Le produit demandé n'existe pas");
        };

        return $this->render('product/show.html.twig', [
            'product' => $product]);
    }

    /**
     * @Route("/admin/product/create", name="product_create")
     * @param FormFactoryInterface $factory
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function create(FormFactoryInterface $factory, Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        // les aramètres pour dire qu'on récurérer une entité Product depuis le form
        $builder = $factory->createBuilder(FormType::class, null, [
            'data_class'=>Product::class
        ]);

        $builder->add('name', TextType::class, [
            'label' => 'Nom du produit',
            'attr' => ['placeholder' => 'Tapez le nom du prouit']
        ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'Description courte',
                'attr' => [
                    'placeholder' => 'Tapez une description assez courte mais parlante au visiteur']
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix du produit ',
                'attr' => [
                    'placeholder' => 'Tapez le prix du produit en €']
            ])
            ->add('mainPicture', UrlType::class, [
                'label' => 'Image du produit ',
                'attr' => [
                    'placeholder' => 'Tapez une URL d\'image']
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',

                'placeholder' => '-- Choisir une catégorie --',
                'class' => Category::class,
                'choice_label' => 'name'
            ]);
        // si on veut affichier les categories en majuscule :
        /*
         *  'choice_label' => function (Category $category){
         * }return strtoupper($category->getName());
        */

        // $form est une classe énorme
        $form = $builder->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $product = $form->getData();
            $product->setSlug(strtolower($slugger->slug($product->getName())));

            $em->persist($product);
            $em->flush();

            dd($product);
        }
        $formView = $form->createView();

        return $this->render('product/create.html.twig', ['formView' => $formView]);
    }
}
