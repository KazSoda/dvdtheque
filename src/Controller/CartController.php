<?php

namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use App\Service\CartService;
use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Meeting;
use Doctrine\ORM\EntityManagerInterface;

class CartController extends AbstractController
{

    #[Route('/cart', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getCart(),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(CartService $cartService, int $id, Request $request): Response
    {
        $cartService->add($id);

        return $this->redirect($request->headers->get('referer') ?: 'app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(CartService $cartService, int $id): Response
    {
        $cartService->remove($id);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/clear', name: 'cart_clear')]
    public function clear(CartService $cartService): Response
    {
        $cartService->clear();

        return $this->redirectToRoute('app_cart');
    }


    #[Route('/validate', name: 'cart_validate', methods: ['POST'])]
    public function validate(Request $request, EntityManagerInterface $entityManager, CartService $cartService): Response
    {
        $user = $this->getUser();


        $customer = $entityManager->getRepository(Customer::class)->findOneBy(['username' => $user]);

        $pickupDate = $request->request->get('pickup_date');

        $pickupDate = new \DateTime($pickupDate);

        $lastMeeting = $entityManager->getRepository(Meeting::class)->findOneBy(
            ['customer' => $customer],
            ['date' => 'DESC']
        );

        $returnedItems = $lastMeeting ? $lastMeeting->getBoughtItems() : new ArrayCollection();

        $cartItems = $cartService->getCart();

        if (empty($cartItems)) {
            return $this->redirectToRoute('app_cart');
        }

        $meeting = new Meeting();
        $meeting->setCustomer($customer);
        $meeting->setDate($pickupDate);

        foreach ($returnedItems as $item) {
            $meeting->addReturnedItem($item);
        }

        foreach ($cartItems as $cartItem) {
            $meeting->addBoughtItem($cartItem['product']);
        }

        $entityManager->persist($meeting);
        $entityManager->flush();

        $cartService->clear();

        return $this->redirectToRoute('app_cart');
    }
}
