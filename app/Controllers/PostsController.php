<?php

class PostsController extends BaseController
{
    private $postModel;

    // Xử lý đăng nhập
    public function __construct()
    {
        $this->postModel = new Posts();
    }

    // public function showPost()
    // {

    // }

    public function list()
    {
        // Get data from GET
        $filter = filterData();
        $chuoiWhere = '';
        $cate = '0';
        $keyword = '';

        if (isGet()) {
            if (isset($filter['keyword'])) {
                $keyword = $filter['keyword'];
            }
            if (isset($filter['cate'])) {
                $cate = $filter['cate'];
            }

            if (!empty($keyword)) {
                if (strpos($chuoiWhere, 'WHERE') === false) { //  Phải so sánh chặt (===) vì strpos trả về vị trí đầu tiên 
                    $chuoiWhere .= ' WHERE ';                  //    tìm thấy chữ Where, tức là vị trí 0, mà 0 thì là false trong PHP
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "(a.name LIKE '%$keyword%' OR a.description LIKE '%$keyword%')";
            }
            if (!empty($cate)) {
                if (strpos($chuoiWhere, 'WHERE') === false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }

                $chuoiWhere .= " a.category_id = $cate";
            }
        }

        // Pagination
        $maxData = $this->postModel->getRowPosts("SELECT id FROM posts"); // Total of data
        $perPage = 3; // Row per page 
        $maxPage = ceil($maxData / $perPage); // Calculate max page, ceil giúp làm tròn lên
        $offset = 0;
        $page = 1;

        // Get page
        if (isset($filter['page'])) {
            $page = $filter['page'];
        }

        // Over max page or page 0
        if ($page > $maxPage || $page < 1) {
            $page = 1;
        }

        $offset =  ($page - 1) * $perPage;

        // Get data from posts table
        $postDetail = $this->postModel->getAllPosts("SELECT * FROM posts 
        $chuoiWhere 
        LIMIT $offset, $perPage");

        // Xử lý querry
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = $_SERVER['QUERY_STRING'];
            // Cắt chuỗi để không bị &page=1&page=2
            $queryString = str_replace('&page=' . $page, '', $queryString);
        }

        // Nếu có thực hiện truy vấn cate hoặc keyword 
        if ($cate > 0 || !empty($keyword)) {
            $maxData2 = $this->postModel->getRowPosts("SELECT id FROM posts a $chuoiWhere");
            $maxPage = ceil($maxData2 / $perPage);
        }

        $data = [
            'postModel' => $this->postModel,
            'postDetail' => $postDetail
        ];
        $this->renderView('layouts-part/posts/list', $data);
    }

    // public function add()
    // {
    //     if (isPost()) {

    //         $filter = filterData();
    //         $errors = [];

    //         // Validate name
    //         if (empty(trim($filter['name']))) {
    //             $errors['name']['required'] = 'Tên khóa học bắt buộc phải nhập';
    //         } else {
    //             if (strlen(trim($filter['name'])) < 5) {
    //                 $errors['name']['length'] = 'Tên khóa học phải lớn hơn 5 ký tự';
    //             }
    //         }

    //         // Validate slug
    //         if (empty(trim($filter['slug']))) {
    //             $errors['slug']['required'] = 'Slug bắt buộc phải nhập';
    //         }


    //         // Validate price
    //         if (empty($filter['price'])) {
    //             $errors['price']['required'] = 'Giá bắt buộc phải nhập';
    //         }

    //         // Validate description
    //         if (empty($filter['description'])) {
    //             $errors['description']['required'] = 'Mô tả bắt buộc phải nhập';
    //         }


    //         if (empty($errors)) {

    //             // Xử lý thumbnail upload
    //             $uploadDir = './templates/uploads/';

    //             if (!file_exists($uploadDir)) {
    //                 mkdir($uploadDir, 0777, true); // Create new upload folder if it doesn't exist
    //             }

    //             $fileName = basename($_FILES['thumbnail']['name']);

    //             $targetFile = $uploadDir . time() . '-' . $fileName;

    //             $thumb = '';
    //             $checkMove = move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetFile);

    //             if ($checkMove) {
    //                 $thumb = $targetFile;
    //             }

    //             $dataInsert = [
    //                 'name' => $filter['name'],
    //                 'slug' => $filter['slug'],
    //                 'price' => $filter['price'],
    //                 'description' => $filter['description'],
    //                 'thumbnail' => $thumb,
    //                 'category_id' => $filter['category_id'],
    //                 'created_at' => date('Y:m:d H:i:s')
    //             ];

    //             $insertStatus = insert('course', $dataInsert);

    //             if ($insertStatus) {
    //                 setSessionFlash('msg', 'Thêm khóa học thành công.');
    //                 setSessionFlash('msg_type', 'success');
    //                 redirect(('?module=course&action=list'));
    //             } else {
    //                 setSessionFlash('msg', 'Thêm khóa học thất bại');
    //                 setSessionFlash('msg_type', 'danger');
    //             }
    //         } else {
    //             setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
    //             setSessionFlash('msg_type', 'danger');
    //             setSessionFlash('oldData', $filter);
    //             setSessionFlash('errors', $errors);
    //         }
    //         $msg = getSessionFlash('msg');
    //         $msg_type = getSessionFlash('msg_type');
    //         $oldData = getSessionFlash('oldData');
    //         $errorsArr = getSessionFlash('errors');
    //     }
    //     $this->renderView('layouts-part/posts/add');
    // }
}
