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
            return $this->redirectToRoute('app_order_home');
        }

        $repo = $em->getRepository(Order::class);
        $orders = $repo->findAll();

        return $this->render('Order/show.html.twig', [
            'orderForm' => $form->createView(),
            'orders' => $orders
        ]);
    }


    /**
     * @Route("/delete/{id}")
     */
    public function delete($id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Order::class);
        $order = $repo->find($id);

        if( !isset($order) ){
            $this->addFlash('error', 'Order does not exist!');
        }else{
            $em->remove($order);
            $em->flush();
            $this->addFlash('success', 'Order has been deleted!');

        }
        return $this->redirectToRoute('app_order_home');
    }

    /**
     * @Route("/edit/{id}")
     */
    public function edit(EntityManagerInterface $em, Request $request, Order $order)
    {

        $form = $this->createForm(OrderFormType::class, $order);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'Order was edited!');
            $order = $form->getData();
            $order->calculateOrder();
            $em->persist($form->getData());
            $em->flush();
            return $this->redirectToRoute('app_order_home');
        }

        return $this->render('Order/edit.html.twig', [
            'orderForm' => $form->createView(),
        ]);
    }
}