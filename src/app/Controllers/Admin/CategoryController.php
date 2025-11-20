<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use App\Middlewares\AuthMiddleware;
use App\Models\Category;
use Core\Session;

final class CategoryController extends Controller
{
    private Category $categoryModel;
    protected array $userData;

    public function __construct()
    {
        parent::__construct();
        AuthMiddleware::requireAuth();
        $this->categoryModel = new Category();

        $this->userData = AuthMiddleware::userData();
    }

    /** 
     * List categories with search + pagination
     * 
     * @return void
     */
    public function index(): void
    {
        // Get search keyword from query string
        $input = filterData('get');

        // Get keyword
        $keyword = trim((string)($input['keyword'] ?? ''));

        // Pagination config
        $perPage = 10;
        $page = isset($input['page']) ? max(1, (int) $input['page']) : 1;

        // Total rows matching
        $total = $this->categoryModel->countCategoriesByKeyword($keyword);
        $maxPage = max(1, (int) ceil($total / $perPage));

        // Clamp page to valid range
        if ($page > $maxPage) {
            $page = $maxPage;
        }

        // Offset calculation
        $offset = ($page - 1) * $perPage;

        // Fetch posts for current page
        $categories = $this->categoryModel->getCategories($perPage, $offset, $keyword);

        // Clean query string for pagination links
        $queryString = cleanQuery('page');

        // Prepare view data
        $data = [
            'headerData' => ['title' => 'Quản lý danh mục - DevBlog CMS'],
            'categories'  => $categories,
            'page'        => $page,
            'maxPage'     => $maxPage,
            'keyword'     => $keyword,
            'queryString' => $queryString,
            'offset'      => $offset,
            'total'       => $total
        ];

        // Render list view
        $this->view->render('admin/categories/index', 'admin', $data);
    }

    public function showAdd()
    {
        $data = [
            'headerData' => ['title' => 'Thêm danh mục - DevBlog CMS']
        ];

        $this->view->render('admin/categories/add', 'admin', $data);
    }

    public function add(): void
    {
        if (!isPost()) {
            return;
        }

        $input = filterData('post');

        // Validation rules
        $errors = [];

        if (empty(trim($input['name']))) {
            $errors['name'] = 'Tên danh mục không được để trống';
        } elseif (strlen(trim($input['name'])) < 3) {
            $errors['name'] = 'Tên danh mục phải có ít nhất 3 ký tự';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('oldData', $input);
            redirect('/categories/add');
        }

        // Create category
        $created = $this->categoryModel->createCategory([
            'name' => trim($input['name']),
            'description' => trim($input['description'] ?? '')
        ]);

        if ($created) {
            Session::flash('msg', 'Thêm danh mục thành công');
            Session::flash('msg_type', 'success');
            redirect('/categories');
        } else {
            Session::flash('msg', 'Thêm danh mục thất bại');
            Session::flash('msg_type', 'danger');
            redirect('/categories/add');
        }
    }

    public function showEdit()
    {
        // Get category ID from query string
        $input = filterData('get');
        $categoryId = (int)($input['id'] ?? 0);

        // Validate category ID
        if ($categoryId <= 0) {
            Session::flash('msg', 'ID danh mục không hợp lệ');
            Session::flash('msg_type', 'danger');
            redirect('/categories');
        }

        // Get category data
        $category = $this->categoryModel->getCategoryById($categoryId);

        if (empty($category)) {
            Session::flash('msg', 'Danh mục không tồn tại');
            Session::flash('msg_type', 'danger');
            redirect('/categories');
        }
        $data = [
            'headerData' => ['title' => 'Chỉnh sửa danh mục - DevBlog CMS'],
            'category' => $category
        ];

        $this->view->render('admin/categories/edit', 'admin', $data);
    }

    public function edit(): void
    {
        if (!isPost()) {
            return;
        }

        // Handle form submission
        $input = filterData('post');
        $categoryId = (int)($input['id'] ?? 0);
        // Validation rules
        $errors = [];

        if (empty(trim($input['name']))) {
            $errors['name'] = 'Tên danh mục không được để trống';
        } elseif (strlen(trim($input['name'])) < 3) {
            $errors['name'] = 'Tên danh mục phải có ít nhất 3 ký tự';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('oldData', $input);
            redirect('/categories/edit?id=' . $categoryId);
        }

        // Update category
        $updated = $this->categoryModel->updateCategory($categoryId, [
            'name' => trim($input['name']),
            'description' => trim($input['description'] ?? '')
        ]);

        if ($updated) {
            Session::flash('msg', 'Cập nhật danh mục thành công');
            Session::flash('msg_type', 'success');
            redirect('/categories');
        } else {
            Session::flash('msg', 'Cập nhật danh mục thất bại');
            Session::flash('msg_type', 'danger');
            redirect('/categories/edit?id=' . $categoryId);
        }
    }

    /** Handle delete post action */
    public function delete()
    {
        // Get id from query string
        $input = filterData('get');
        $categoryId = (int)($input['id'] ?? 0);

        // Validate post ID 
        if ($categoryId <= 0) {
            Session::flash('msg', 'ID danh mục không hợp lệ');
            Session::flash('msg_type', 'danger');
            redirect('/categories');
        }

        // Check if post exists
        $category = $this->categoryModel->getCategoryById($categoryId);

        if (empty($category)) {
            Session::flash('msg', 'Danh mục không tồn tại.');
            Session::flash('msg_type', 'danger');
            redirect('/categories');
        }

        // Delete post
        $deleted = $this->categoryModel->deleteCategory($categoryId);

        if (!$deleted) {
            Session::flash('msg', 'Xóa danh mục thất bại, vui lòng thử lại.');
            Session::flash('msg_type', 'danger');
            redirect('/categories');
        }

        // Delete failed
        Session::flash('msg', 'Xóa danh mục thành công.');
        Session::flash('msg_type', 'success');
        redirect('/categories');
    }
}
