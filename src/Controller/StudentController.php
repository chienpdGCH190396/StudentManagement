<?php

namespace App\Controller;

use App\Entity\Student;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     /**
     * @Route("/student/delete/{id}", name="student_delete")
     */



     /**
     * @Route("/student/add", name="student_add")
     */


     /**
     * @Route("/student/edit/{id}", name="student_edit")
     */
}
