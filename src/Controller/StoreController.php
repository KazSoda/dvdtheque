<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StoreController extends AbstractController
{
    #[Route('/store', name: 'app_store')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('store/index.html.twig', [
            'products' => $products,
            'controller_name' => 'IndexController',
        ]);
    }
}
