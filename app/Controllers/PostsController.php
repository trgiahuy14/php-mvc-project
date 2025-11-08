<?php

class PostsController extends BaseController
{
    private $postModel;

    // Xử lý đăng nhập
    public function __construct()
    {
        $this->postModel = new Posts();
    }

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

    public function showAdd()
    {
        $this->renderView('layouts-part/posts/add');
    }

    public function add()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];

            // VALIDATE

            // Validate title
            if (empty(trim($filter['title']))) {
                $errors['title']['required'] = 'Tiêu đề bắt buộc phải nhập';
            } else {
                if (strlen(trim($filter['title'])) < 5) {
                    $errors['title']['length'] = 'Tiêu đề phải lớn hơn 5 ký tự';
                }
            }
            // Validate content
            if (empty(trim($filter['content']))) {
                $errors['content']['required'] = 'Nội dung bắt buộc phải nhập';
            }


            if (empty($errors)) {
                // INSERT DATA
                $dataInsert = [
                    'title' => $filter['title'],
                    'content' => $filter['content'],
                    'tags' => $filter['tags'],
                    'minutes_read' => $filter['minutes_read'],
                    'views' => $filter['views'],
                    'comments' => $filter['comments'],
                    'shares' => $filter['shares'],
                    'created_at' => date('Y-m-d H:i:s')

                ];

                $insertStatus = $this->postModel->insertPost($dataInsert);

                if ($insertStatus) {
                    setSessionFlash('msg', 'Thêm bài viết thành công.');
                    setSessionFlash('msg_type', 'success');
                    redirect(('/posts'));
                } else {
                    setSessionFlash('msg', 'Thêm bài viết thất bại');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                redirect('/posts/add');
            }
        }
        $this->renderView('layouts-part/posts/add');
    }
}
