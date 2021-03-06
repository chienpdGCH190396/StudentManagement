<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\TeacherType;
use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\throwException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class TeacherController extends AbstractController
{
    /**
     * @Route("/teacher", name="teacher_index")
     */
    public function teacherIndex()
    {
        $teachers = $this->getDoctrine()->getRepository(Teacher::class)->findAll();
        return $this->render(
            'teacher/index.html.twig',
            [
                'teachers' => $teachers
            ]
        );
    }

    /**
     * @Route("/teacher/detail/{id}", name="teacher_detail")
     */
    public function teacherDetail($id)
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
        if ($teacher == null) {
            $teacher = $this->addFlash('Error', 'Teacher is not exists');
            return $this->redirectToRoute('teacher_index');
        } else {
            return $this->render(
                'teacher/detail.html.twig',
                [
                    'teacher' => $teacher
                ]
            );
        }
    }

    /**
     * @Route("/teacher/delete/{id}", name="teacher_delete")
     */
    public function teacherDelete($id)
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
        if ($teacher == null) {
            $this->addFlash('Error', 'Teacher is not existed');
        } else {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($teacher);
            $manager->flush();
            $this->addFlash('Success', 'Teacher has been deleted successfully !');
        }
        return $this->redirectToRoute('teacher_index');
    }

    /**
     * @Route("/teacher/add", name="teacher_add")
     */
    public function teacherAdd(Request $request)
    {
        $teacher = new Teacher();
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //code x??? l?? ???nh
            //B1: l???y ???nh t??? file upload
            $image = $teacher->getImage();
            //B2: ?????t t??n m???i cho ???nh => ?????m b???o m???i ???nh s??? c?? 1 t??n duy nh???t kh??ng tr??ng nhau            
            $imgName = uniqid();
            //B3: l???y ??u??i ???nh (image extension) -> v??o Entity x??a :?string, :self, ?string
            $imgExtension = $image->guessExtension();
            //B4: n???i t??n v?? ??u??i ????? t???o th??nh t??n file ???nh m???i ?????y ?????
            $imageName = $imgName . "." . $imgExtension;
            //B5: di chuy???n ???nh v??o th?? m???c ch??? ?????nh
            try {
                $image->move(
                    $this->getParameter('teacher_image'),
                    $imageName
                    //L??u ??: c???n khai b??o ???????ng d???n th?? m???c ch???a ???nh ??? file config/services.yaml
                );
            } catch (FileException $e) {
                throwException($e);
            }
            //B6: l??u t??n ???nh v??o database
            $teacher->setImage($imageName);

            //?????y d??? li???u nh???p t??? form v??o database
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($teacher);
            $manager->flush();

            $this->addFlash('Success', 'Add new teacher successfully !');
            return $this->redirectToRoute('teacher_index');
        }
        return $this->render(
            'teacher/add.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @Route("/teacher/edit/{id}", name="teacher_edit")
     */
    public function teacherEdit(Request $request, $id)
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //code x??? l?? ???nh
            //B1: l???y d??? li???u ???nh t??? form
            $file = $form['image']->getData();
            //B2: check xem ???nh c?? null hay kh??ng
            if ($file != null) {
                //B1: l???y ???nh t??? file upload
                $image = $teacher->getImage();
                //B2: ?????t t??n m???i cho ???nh => ?????m b???o m???i ???nh s??? c?? 1 t??n duy nh???t kh??ng tr??ng nhau            
                $imgName = uniqid();
                //B3: l???y ??u??i ???nh (image extension) -> v??o Entity x??a :?string, :self, ?string
                $imgExtension = $image->guessExtension();
                //B4: n???i t??n v?? ??u??i ????? t???o th??nh t??n file ???nh m???i ?????y ?????
                $imageName = $imgName . "." . $imgExtension;
                //B5: di chuy???n ???nh v??o th?? m???c ch??? ?????nh
                try {
                    $image->move(
                        $this->getParameter('teacher_image'),
                        $imageName
                    );
                } catch (FileException $e) {
                    throwException($e);
                }
                //B6: l??u t??n ???nh v??o database
                $teacher->setImage($imageName);
            }

            //?????y d??? li???u nh???p t??? form v??o database
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($teacher);
            $manager->flush();

            $this->addFlash('Success', 'Edit teacher successfully !');
            return $this->redirectToRoute('teacher_index');
        }
        return $this->render(
            'teacher/edit.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }
}
