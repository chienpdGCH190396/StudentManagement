<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ClassroomType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'classroom_index')]
    public function classroomIndex()
    {
        $classrooms = $this->getDoctrine()->getRepository(Classroom::class)->findAll();
        return $this->render(
            'classroom/index.html.twig',
            [
                'classrooms' => $classrooms
            ]
        );
    }

    /**
     * @Route("/classroom/detail/{id}", name="classroom_detail")
     */
    public function classroomDetail($id)
    {
        $classroom = $this->getDoctrine()->getRepository(Classroom::class)->find($id);
        if ($classroom == null) {
            $this->addFlash('Error', 'Classroom is not existed');
            return $this->redirectToRoute('classroom_index');
        } else {
            return $this->render(
                'classroom/detail.html.twig',
                [
                    'classroom' => $classroom
                ]
            );
        }
    }

    /**
     * @Route("/classroom/delete/{id}", name="classroom_delete")
     */
    public function classroomDelete($id)
    {
        $classroom = $this->getDoctrine()->getRepository(Classroom::class)->find($id);
        if ($classroom == null) {
            $this->addFlash('Error', 'Classroom is not existed');
        } else {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($classroom);
            $manager->flush();
            $this->addFlash('Success', 'Classroom has been deleted successfully !');
        }
        return $this->redirectToRoute('classroom_index');
    }

    /**
     * @Route("/classroom/add", name="classroom_add")
     */
    public function classroomAdd(Request $request)
    {
        $classroom = new Classroom();
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($classroom);
            $manager->flush();

            $this->addFlash('Success', 'Add new classroom successfully !');
            return $this->redirectToRoute('classroom_index');
        }
        return $this->render(
            'classroom/add.html.twig', 
            [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @Route("/classroom/edit/{id}", name="classroom_edit")
     */
    public function classroomEdit(Request $request, $id)
    {
        $classroom = $this->getDoctrine()->getRepository(Classroom::class)->find($id);
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($classroom);
            $manager->flush();

            $this->addFlash('Success', 'Edit classroom successfully !');
            return $this->redirectToRoute('classroom_index');
        }
        return $this->render(
            'classroom/edit.html.twig', 
            [
                "form" => $form->createView()
            ]
        );
    }
}
