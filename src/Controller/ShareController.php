<?php

namespace App\Controller;

use App\Entity\ListElement;
use App\Entity\TodoList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShareController extends AbstractController
{
    /**
     * @Route("/share/list/{hashId}", name="share_list", methods={"GET"})
     * @param TodoList $list
     * @return Response
     */
    public function index(TodoList $list): Response
    {
        $em=$this->getDoctrine()->getManager();
        $elements=$em->getRepository(ListElement::class)->getListElementsSorted($list);
        return $this->render('share/index.html.twig', [
            'list'=>$list,
            'elements'=>$elements
        ]);
    }
}
