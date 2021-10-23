<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class StudentController extends AbstractController
{
    #[Route('/student', name: 'student_index')]
    public function viewAllStudentAction(){
        $students = new Student();
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        return $this->render(
        'student/index.html.twig', [
            'students' => $students
        ]
        );
    }

    
      #[Route('/student/detail/{id}', name: 'student_detail')]
     

    public function studentdetailAction($id){
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        if($student == null){
            $student = $this->addFlash('Error','Student does not exist.');
            return $this->redirectToRoute('student_index');
            
        }else{
            return $this->render('student/detail.html.twig',[
                'student' => $student
            ]);
        }
    }

        
    #[Route("/student/add", name:"student_add")]
    public function addStudentAction(request $request)
        {
            $student = new Student();
            $form = $this->createForm(StudentType::class, $student);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $image = $student->getImage();          
                $imgName = uniqid();         
                $imgExtension = $image->guessExtension();
                $imageName = $imgName . "." . $imgExtension;
                try {
                    $image->move(
                        $this->getParameter('student_image'),
                        $imageName
                    );
                } catch (FileException $a) {
                    // throwException($a);
                }
                
                $student->setImage($imageName);
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($student);
                $manager->flush();
    
                $this->addFlash('Success', 'Add new teacher successfully !');
                return $this->redirectToRoute('student_index');
            }
            return $this->render(
                'teacher/add.html.twig',
                [
                    "form" => $form->createView()
                ]
            );
        }
    


}

     /**
     * @Route("/student/delete/{id}", name="student_delete")
     */





     /**
     * @Route("/student/edit/{id}", name="student_edit")
     */


