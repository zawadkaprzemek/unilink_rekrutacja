<?php

namespace App\Controller;

use App\Entity\ListElement;
use App\Entity\TodoList;
use App\Entity\User;
use App\Form\ListElementsType;
use App\Form\TodoListType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/list")
 */
class ListController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/{status}", name="list_my", defaults={"status":"active"}, requirements={"status":"active|done|all"})
     * @param Request $request
     * @param string $status
     * @return Response
     */
    public function index(Request $request,string $status): Response
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $em=$this->getDoctrine()->getManager();
        $lists=$em->getRepository(TodoList::class)->getMyList($this->getUser(),$status);

        return $this->render('list/index.html.twig', [
            'lists' => $lists,
        ]);
    }

    /**
     * @Route("/new", name="app_new_list", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function newList(Request $request)
    {
        /** @var User $user */
        $user=$this->getUser();
        $list=new TodoList($user);
        $form=$this->createForm(TodoListType::class,$list);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $list->setHashId(uniqid());
            $em=$this->getDoctrine()->getManager();
            $em->persist($list);
            $em->flush();
            $this->addFlash('success',$this->translator->trans('Dodano nową liste'));
            return $this->redirectToRoute('list_show',['id'=>$list->getId()]);
        }

        return $this->render('list/new.html.twig', [
           'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="list_show", methods={"GET"})
     * @param TodoList $list
     * @return Response
     */
    public function showList(TodoList $list): Response
    {
        if($list->getUser()!==$this->getUser())
        {
            return $this->redirectToRoute('list_my');
        }
        $em=$this->getDoctrine()->getManager();
        $elements=$em->getRepository(ListElement::class)->getListElementsSorted($list);
        return $this->render('list/show.html.twig', [
           'list'=>$list,
            'elements'=>$elements
        ]);
    }

    /**
     * @Route("/edit/{id}", name="list_edit", methods={"GET","POST"})
     * @param Request $request
     * @param TodoList $list
     * @return Response
     */
    public function editList(Request $request,TodoList $list): Response
    {
        if($list->getUser()!==$this->getUser())
        {
            return $this->redirectToRoute('list_my');
        }
        $form=$this->createForm(TodoListType::class,$list);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($list);
            $em->flush();
            $this->addFlash('success',$this->translator->trans('Edycja listy zakończona powodzeniem'));
            return $this->redirectToRoute('list_show',['id'=>$list->getId()]);
        }

        return $this->render('list/edit.html.twig', [
            'form'=>$form->createView(),
            'list'=>$list
        ]);
    }

    /**
     * @Route("/delete/{id}",name="list_delete", methods={"DELETE"})
     * @param Request $request
     * @param TodoList $list
     * @return Response
     */
    public function deleteList(Request $request,TodoList $list): Response
    {
        if($list->getUser()!==$this->getUser())
        {
            return $this->redirectToRoute('list_my');
        }

        if ($this->isCsrfTokenValid('delete'.$list->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($list);
            foreach($list->getListElements() as $element)
            {
                $entityManager->remove($element);
            }
            $entityManager->flush();
        }

        return $this->redirectToRoute('list_my');
    }

    /**
     * @Route("/list/{id}/done", name="list_make_done",methods={"PATCH"})
     * @param Request $request
     * @param TodoList $list
     * @return Response
     */
    public function doneList(Request $request,TodoList $list): Response
    {
        if($list->getUser()!==$this->getUser())
        {
            return $this->redirectToRoute('list_my');
        }
        $em=$this->getDoctrine()->getManager();
        if($list->getElementsDoneStatus())
        {
            $list->setDone(!$list->getDone());
            $em->persist($list);
            $em->flush();
        }else{
            $this->addFlash('warning',$this->translator->trans('Nie można zakończyć listy, ukończ wszystkie elementy listy najpierw'));
        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/{id}/edit_elements", name="list_edit_elements", methods={"GET", "POST"})
     * @param Request $request
     * @param TodoList $list
     * @return RedirectResponse|Response
     */
    public function editListElements(Request $request, TodoList $list)
    {
        if($list->getUser()!==$this->getUser())
        {
            return $this->redirectToRoute('list_my');
        }
        $em=$this->getDoctrine()->getManager();
        $originalElements=new ArrayCollection();
        $elements=$em->getRepository(ListElement::class)->getListElementsSorted($list);
        foreach ($elements as $element)
        {
            $originalElements->add($element);
        }
        $sort=sizeof($list->getListElements());
        for($i=0;$i<10;$i++)
        {
            $element=new ListElement();
            $sort++;
            $element->setSort($sort);
            $list->addListElement($element);
        }
        $form = $this->createForm(ListElementsType::class,$list);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {

            foreach($list->getListElements() as $element)
            {
                if(is_null($element->getContent()))
                {
                    $list->removeListElement($element);
                    if(false === $list->getListElements()->contains($element))
                    {
                        $em->remove($element);
                    }
                }
            }

            $em->persist($list);
            $em->flush();
            $this->addFlash('success',$this->translator->trans('Edycja zakończona sukcesem'));
            return $this->redirectToRoute('list_show',['id'=>$list->getId()]);
        }

        return $this->render('list/_elements_form.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/list/{parent_id}/element/{child_id}/done", name="list_element_make_done",methods={"PATCH"})
     * @ParamConverter("list", options={"mapping": {"parent_id": "id"}})
     * @ParamConverter("element", options={"mapping": {"child_id": "id"}})
     * @param Request $request
     * @param TodoList $list
     * @param ListElement $element
     * @return Response
     */
    public function doneElement(Request $request,TodoList $list,ListElement $element): Response
    {
        if($list->getUser()!==$this->getUser())
        {
            return $this->redirectToRoute('list_my');
        }
        $em=$this->getDoctrine()->getManager();
        $element->setDone(!$element->getDone());
        $em->persist($element);
        $em->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/list/{id}/done_all", name="list_element_make_done_all",methods={"PATCH"})
     * @param Request $request
     * @param TodoList $list
     * @return Response
     */
    public function doneAllElements(Request $request,TodoList $list): Response
    {
        if($list->getUser()!==$this->getUser())
        {
            return $this->redirectToRoute('list_my');
        }
        $em=$this->getDoctrine()->getManager();
        foreach ($list->getListElements() as $element)
        {
            $element->setDone(true);
            $em->persist($element);
        }
        $em->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/list/{parent_id}/element/{sort}/delete", name="list_element_delete",methods={"DELETE"})
     * @ParamConverter("list", options={"mapping": {"parent_id": "id"}})
     * @param Request $request
     * @param TodoList $list
     * @param ListElement $element
     * @return Response
     */
    public function deleteElement(Request $request,TodoList $list,ListElement $element): Response
    {
        if($list->getUser()!==$this->getUser())
        {
            return $this->redirectToRoute('list_my');
        }
        $em=$this->getDoctrine()->getManager();
        $otherElements=$em->getRepository(ListElement::class)->getElementWithHiggerSort($element->getList(),$element->getSort());
        foreach ($otherElements as $otherElement)
        {
            $otherElement->setSort($otherElement->getSort()-1);
            $em->persist($otherElement);
        }
        $em->remove($element);
        $em->flush();
        return $this->redirect($request->headers->get('referer'));
    }


    /**
     * @Route("/list/{parent_id}/element/{sort}/move/{type}", name="list_element_move",methods={"PATCH"}, requirements={"type":"up|down"})
     * @ParamConverter("list", options={"mapping": {"parent_id": "id"}})
     * @param Request $request
     * @param TodoList $list
     * @param ListElement $element
     * @param string $type
     * @return RedirectResponse
     */
    public function moveElement(Request $request,TodoList $list,ListElement $element,string $type)
    {
        if($list->getUser()!==$this->getUser())
        {
            return $this->redirectToRoute('list_my');
        }
        $em=$this->getDoctrine()->getManager();

        $element->setSort($element->getSort() + ($type=="up" ? -1 : 1 ) );
        /**
         * Pobieranie elementu nad tym elementem jeśli $type=up, lub pod tym elementem jeśli $type=down
         * i przesuwanie go pod ten element lub nad ten element
         */
        $second_element=$em->getRepository(ListElement::class)->getElementWithSort($list,$element->getSort());
        if(!is_null($second_element))
        {
            $second_element->setSort($second_element->getSort() + ($type=="up" ? 1 : -1 ));
        }

        $em->persist($element);
        $em->persist($second_element);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
