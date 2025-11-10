<?php

class PostsController extends BaseController
{
    private $postModel;
    protected $userInfo;

    // Xử lý đăng nhập
    public function __construct()
    {
        $this->requireLogin();
        $this->postModel = new Posts();
        $this->userInfo = getSession('getInfo');
    }

    public function list()
    {
        // Get data from GET
        $filter = filterData('get');
        $whereSql = '';
        $keyword = '';

        if (isGet()) {
            // Search handling
            if (isset($filter['keyword'])) {
                $keyword = trim($filter['keyword']);
                $keyword = addslashes($keyword);
            }
            if (!empty($keyword)) {
                $whereSql .= " WHERE title LIKE '%{$keyword}%'";
            }

            // Pagination
            $maxData = (int)$this->postModel->getRowPosts("SELECT COUNT(id) FROM posts{$whereSql}"); // Total of data
            $perPage = 10; // Row per page 
            $maxPage = max(1, (int)ceil($maxData / $perPage)); // Calculate max page, ceil giúp làm tròn lên
            $page = 1;

            // Get page
            if (isset($filter['page'])) {
                $page = (int)$filter['page'];
            }

            // Over max page or page 0¡¡¡
            if ($page > $maxPage || $page < 1) {
                $page = 1;
            }

            $offset =  ($page - 1) * $perPage;

            $sqlList = "$whereSql LIMIT $offset, $perPage";
            $postDetail = $this->postModel->getAllPosts($sqlList);

            // Xử lý querry
            $queryString = $_SERVER['QUERY_STRING'] ?? '';
            if (!empty($queryString)) {
                // Cắt chuỗi để không bị &page=1&page=2
                $queryString = str_replace('page=' . $page, '', $queryString);
            }

            // Nếu có thực hiện truy vấn keyword 
            if (!empty($keyword)) {
                $maxData2 = $this->postModel->getScalarPosts($whereSql);
                $maxPage = ceil($maxData2 / $perPage);
            }

            $data = [
                'postModel' => $this->postModel,
                'postDetail' => $postDetail,
                'page' => $page,
                'maxPage' => $maxPage,
                'keyword' => $keyword,
                'queryString' => $queryString,
                'offset' => $offset
            ];
            $this->renderView('layouts-part/posts/list', $data);
        }
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
                    'author' => $this->userInfo['fullname'],
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

    public function showEdit()
    {
        $filter = filterData('get');
        // Get exist value(s) from post
        $rel = $this->postModel->getOnePost("id = " . $filter['id']);
        $data = [
            'postData' => $rel,
            'idPost' => $filter['id']
        ];
        $this->renderView('layouts-part/posts/edit', $data);
    }

    public function edit()
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
                // UPDATE DATA
                $dataUpdate = [
                    'title' => $filter['title'],
                    'content' => $filter['content'],
                    'tags' => $filter['tags'],
                    'minutes_read' => $filter['minutes_read'],
                    'views' => $filter['views'],
                    'comments' => $filter['comments'],
                    'shares' => $filter['shares'],
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $idPost = $filter['idPost'];
                $updateStatus = $this->postModel->updatePost($dataUpdate, "id=$idPost");

                if ($updateStatus) {
                    setSessionFlash('msg', 'Chỉnh sửa bài viết thành công.');
                    setSessionFlash('msg_type', 'success');
                    redirect(('/posts'));
                } else {
                    setSessionFlash('msg', 'Chỉnh sửa bài viết thất bại');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                redirect('/posts/edit?id=' . $filter['id']);
            }
        }
        $this->renderView('layouts-part/posts/edit');
    }

    public function delete()
    {
        $filter = filterData('get');

        if (!empty($filter)) {
            $idPost = $filter['id'];
            $condition = 'id=' . $idPost;
            $checkPost = $this->postModel->getOnePost($condition);
            if (!empty($checkPost)) {
                $deleteStatus = $this->postModel->deletePost($condition);

                if ($deleteStatus) {
                    setSessionFlash('msg', 'Xóa bài viết thành công.');
                    setSessionFlash('msg_type', 'success');
                    redirect('/posts');
                }
            } else {
                setSessionFlash('msg', 'Bài viết không tồn tại.');
                setSessionFlash('msg_type', 'danger');
                redirect('/posts');
            }
        } else {
            setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại sau.');
            setSessionFlash('msg_type', 'danger');
        }
    }
}
