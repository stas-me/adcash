<?php


namespace App\Controller;



use App\Entity\Order;
use App\Form\OrderFormType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function home(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator )
    {
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

        $search = $request->query->get('q');
        $period = $request->query->get('p');
        if( $period != 7 && $period != 1 ){
            $period = 0;
        }

        $repo = $em->getRepository(Order::class);
        $ordersQB = $repo->getFilterQueryBuilder($search, $period);

        $pagination = $paginator->paginate(
            $ordersQB,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('Order/show.html.twig', [
            'orderForm' => $form->createView(),
            'pagination' => $pagination
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