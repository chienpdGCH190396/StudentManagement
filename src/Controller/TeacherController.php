<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\TeacherType;
use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\throwException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @IsGranted("ROLE_USER")
 */

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
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
     * @Route("/teacher/add", name="teacher_add")
     */
    public function teacherAdd(Request $request)
    {
        $teacher = new Teacher();
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //code xử lý ảnh
            //B1: lấy ảnh từ file upload
            $image = $teacher->getImage();
            //B2: đặt tên mới cho ảnh => đảm bảo mỗi ảnh sẽ có 1 tên duy nhất không trùng nhau            
            $imgName = uniqid();
            //B3: lấy đuôi ảnh (image extension) -> vào Entity xóa :?string, :self, ?string
            $imgExtension = $image->guessExtension();
            //B4: nối tên và đuôi để tạo thành tên file ảnh mới đầy đủ
            $imageName = $imgName . "." . $imgExtension;
            //B5: di chuyển ảnh vào thư mục chỉ định
            try {
                $image->move(
                    $this->getParameter('teacher_image'),
                    $imageName
                    //Lưu ý: cần khai báo đường dẫn thư mục chứa ảnh ở file config/services.yaml
                );
            } catch (FileException $e) {
                throwException($e);
            }
            //B6: lưu tên ảnh vào database
            $teacher->setImage($imageName);

            //đẩy dữ liệu nhập từ form vào database
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
     * @IsGranted("ROLE_ADMIN")
     * @Route("/teacher/edit/{id}", name="teacher_edit")
     */
    public function teacherEdit(Request $request, $id)
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //code xử lý ảnh
            //B1: lấy dữ liệu ảnh từ form
            $file = $form['image']->getData();
            //B2: check xem ảnh có null hay không
            if ($file != null) {
                //B1: lấy ảnh từ file upload
                $image = $teacher->getImage();
                //B2: đặt tên mới cho ảnh => đảm bảo mỗi ảnh sẽ có 1 tên duy nhất không trùng nhau            
                $imgName = uniqid();
                //B3: lấy đuôi ảnh (image extension) -> vào Entity xóa :?string, :self, ?string
                $imgExtension = $image->guessExtension();
                //B4: nối tên và đuôi để tạo thành tên file ảnh mới đầy đủ
                $imageName = $imgName . "." . $imgExtension;
                //B5: di chuyển ảnh vào thư mục chỉ định
                try {
                    $image->move(
                        $this->getParameter('teacher_image'),
                        $imageName
                    );
                } catch (FileException $e) {
                    throwException($e);
                }
                //B6: lưu tên ảnh vào database
                $teacher->setImage($imageName);
            }

            //đẩy dữ liệu nhập từ form vào database
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
