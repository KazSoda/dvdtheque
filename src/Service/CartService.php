<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class CartService
{
    private $session;
    private EntityManagerInterface $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->session = $requestStack->getSession(); // Gets session through RequestStack
        $this->entityManager = $entityManager;
    }

    public function add(int $productId): void
    {
        $cart = $this->session->get('cart', []);

        if (!isset($cart[$productId])) {
            $cart[$productId] = 1;
        } else {
            $cart[$productId]++;
        }

        $this->session->set('cart', $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->session->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        $this->session->set('cart', $cart);
    }

    public function getCart(): array
    {
        $cart = $this->session->get('cart', []);
        $cartData = [];

        foreach ($cart as $id => $quantity) {
            $product = $this->entityManager->getRepository(Product::class)->find($id);
            if ($product) {
                $cartData[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartData;
    }

    public function clear(): void
    {
        $this->session->remove('cart');
    }
}
