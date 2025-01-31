<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\ArticleRepository;
use App\Repository\CustomerRepository;
use App\Repository\MeetingRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(UserInterface $user, CustomerRepository $customerRepo, UserRepository $userRepo, MeetingRepository $meetingRepo, EntityManagerInterface $em): Response
    {
        $userId = $user->getUserIdentifier();
        $userLogged = $userRepo->findOneBy(['username'=>$userId]);
        $account = $customerRepo->findOneBy(['username' => $userLogged->getId()]);
        $meetings = $meetingRepo->findBy(['customer' => $account]);

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'account' =>  $account,
            'meetings' => $meetings,
        ]);
    }
}
