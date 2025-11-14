<?php

declare(strict_types=1);

final class PostController extends BaseController
{
    private PostModel $postModel;
    protected array $currentUser;

    public function __construct()
    {
        $this->requireLogin();
        $this->postModel = new PostModel();
        $this->currentUser = (array)getSession('current_user');
    }

    /** List posts with search + pagination */
    public function list(): void
    {
        $input = filterData('get');

        // Get keyword
        $keyword = trim((string)($input['keyword'] ?? ''));

        // Pagination config
        $perPage = 10;
        $page = isset($input['page']) ? max(1, (int) $input['page']) : 1;

        // Total rows matching
        $total = $this->postModel->countPostsByKeyword($keyword);
        $maxPage = max(1, (int) ceil($total / $perPage));

        // Clamp page to valid range
        if ($page > $maxPage) {
            $page = $maxPage;
        }

        // Offset calculation
        $offset = ($page - 1) * $perPage;

        // Fetch posts for current page
        $posts = $this->postModel->getPosts($perPage, $offset, $keyword);

        // Clean query string for pagination links
        $queryString = cleanQuery('page');

        // Prepare view data
        $data = [
            'posts'       => $posts,
            'page'        => $page,
            'maxPage'     => $maxPage,
            'keyword'     => $keyword,
            'queryString' => $queryString,
            'offset'      => $offset,
            'total'       => $total
        ];

        // Render list view
        $this->renderView('layouts-part/posts/list', $data);
    }

    public function showAdd()
    {
        $this->renderView('layouts-part/posts/add');
    }

    public function add()
    {
        if (isPost()) {
            $input = filterData();
            $errors = [];

            // VALIDATE

            // Validate title
            if (empty(trim($input['title']))) {
                $errors['title']['required'] = 'Tiêu đề bắt buộc phải nhập';
            } else {
                if (strlen(trim($input['title'])) < 5) {
                    $errors['title']['length'] = 'Tiêu đề phải lớn hơn 5 ký tự';
                }
            }
            // Validate content
            if (empty(trim($input['content']))) {
                $errors['content']['required'] = 'Nội dung bắt buộc phải nhập';
            }

            if (empty($errors)) {
                // INSERT DATA
                $dataInsert = [
                    'title' => $input['title'],
                    // 'author' => getSession('current_user['']'),
                    'content' => $input['content'],
                    'tags' => $input['tags'],
                    'minutes_read' => $input['minutes_read'],
                    'views' => $input['views'],
                    'comments' => $input['comments'],
                    'shares' => $input['shares'],
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
                setSessionFlash('oldData', $input);
                setSessionFlash('errors', $errors);
                redirect('/posts/add');
            }
        }
        $this->renderView('layouts-part/posts/add');
    }

    public function showEdit()
    {
        $input = filterData('get');
        // Get exist value(s) from post
        $rel = $this->postModel->getOnePost("id = " . $input['id']);
        $data = [
            'postData' => $rel,
            'idPost' => $input['id']
        ];
        $this->renderView('layouts-part/posts/edit', $data);
    }

    public function edit()
    {
        if (isPost()) {
            $input = filterData();
            $errors = [];

            // VALIDATE

            // Validate title
            if (empty(trim($input['title']))) {
                $errors['title']['required'] = 'Tiêu đề bắt buộc phải nhập';
            } else {
                if (strlen(trim($input['title'])) < 5) {
                    $errors['title']['length'] = 'Tiêu đề phải lớn hơn 5 ký tự';
                }
            }
            // Validate content
            if (empty(trim($input['content']))) {
                $errors['content']['required'] = 'Nội dung bắt buộc phải nhập';
            }

            if (empty($errors)) {
                // UPDATE DATA
                $dataUpdate = [
                    'title' => $input['title'],
                    'content' => $input['content'],
                    'tags' => $input['tags'],
                    'minutes_read' => $input['minutes_read'],
                    'views' => $input['views'],
                    'comments' => $input['comments'],
                    'shares' => $input['shares'],
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $idPost = $input['idPost'];
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
                setSessionFlash('oldData', $input);
                setSessionFlash('errors', $errors);
                redirect('/posts/edit?id=' . $input['id']);
            }
        }
        $this->renderView('layouts-part/posts/edit');
    }

    public function delete()
    {
        $input = filterData('get');

        if (!empty($input)) {
            $idPost = $input['id'];
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
