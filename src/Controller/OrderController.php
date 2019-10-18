<?php


namespace App\Controller;



use App\Entity\Product;
use App\Entity\Order;
use App\Entity\User;
use App\Form\OrderFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function home(EntityManagerInterface $em, Request $request)
    {

//        $user = new User();
//        dump($user);
//        $user->setName('Bob');
//        $em->persist($user);
//        $em->flush();
//
//
//        return new Response('added ' . $user->getName() . ' id: '.$user->getId());


        $form = $this->createForm(OrderFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'Order was created!');
            $order = $form->getData();
            $order->calculateOrder();
            $em->persist($form->getData());
            $em->flush();
//            dd($form->getData());
            return $this->redirectToRoute('app_order_home');
        }



        $repo = $em->getRepository(Order::class);
        $orders = $repo->findAll();



//        return new Response('OMG');
        return $this->render('Order/show.html.twig', [
            'orderForm' => $form->createView(),
            'orders' => $orders
        ]);
    }
}