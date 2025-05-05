<?php
session_start();

// Include các model cần thiết nếu có (các model chung, ví dụ ProductModel, UserModel, …)
require_once 'app/models/ProductModel.php';
require_once 'app/models/UserModel.php';

// Lấy URL từ query string, loại bỏ dấu '/' cuối cùng, làm sạch và tách thành mảng
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Nếu dự án nằm trong thư mục con, cần đảm bảo cấu hình RewriteBase trong .htaccess (ví dụ: /DA_ADMIN_TEST/)

// 1. Route cho trang giới thiệu: /gioi-thieu
if (isset($url[0]) && strtolower($url[0]) === 'gioi-thieu') {
    require_once 'app/views/introduce.php';
    exit;
}

// 2. Route cho trang thanh toán giỏ hàng: /gio-hang/thanh-toan
if (
    isset($url[0]) && strtolower($url[0]) === 'gio-hang'
    && isset($url[1]) && strtolower($url[1]) === 'thanh-toan'
) {
    $controllerName = 'CheckoutController';
    $action = isset($url[2]) && $url[2] !== '' ? $url[2] : 'index';
    $controllerPath = 'app/controllers/CheckoutController.php';
}
// 3. Route cho trang giỏ hàng: /gio-hang
else if (isset($url[0]) && strtolower($url[0]) === 'gio-hang') {
    $controllerName = 'CartController';
    $action = 'index';
    $controllerPath = 'app/controllers/CartController.php';
}
// 4. Route cho trang quản trị (admin): /admin/...
else if (isset($url[0]) && strtolower($url[0]) === 'admin') {
    array_shift($url);
    $controllerName = isset($url[0]) && $url[0] !== ''
        ? ucfirst($url[0]) . 'Controller'
        : 'ProductController';
    $action = isset($url[1]) && $url[1] !== '' ? $url[1] : 'index';
    $controllerPath = 'app/controllers/admin/' . $controllerName . '.php';
}
// 5. Route cho trang wishlist: /yeu-thich
else if (isset($url[0]) && strtolower($url[0]) === 'yeu-thich') {
    $controllerName = 'WishListController';
    $action = 'index';
    $controllerPath = 'app/controllers/WishListController.php';
}
// 6. Route cho trang kiểm tra đơn hàng: /kiem-tra-don-hang
else if (isset($url[0]) && strtolower($url[0]) === 'kiem-tra-don-hang') {
    $controllerName = 'CheckOrderController';
    $action = isset($url[1]) && $url[1] !== '' ? $url[1] : 'index';
    $controllerPath = 'app/controllers/CheckOrderController.php';
}
// 7. Route cho chức năng "Mua ngay": /san-pham/mua-ngay hoặc /san-pham/mua-ngay/thanh-toan/...
else if (
    isset($url[0]) && strtolower($url[0]) === 'san-pham'
    && isset($url[1]) && strtolower($url[1]) === 'mua-ngay'
) {

    $controllerName = 'CheckoutController';
    // Nếu có phần thứ 3 và nó là "thanh-toan"
    if (isset($url[2]) && strtolower($url[2]) === 'thanh-toan') {
        // Nếu có phần thứ 4, dùng nó làm action, ngược lại mặc định là "thanhToan"
        $action = isset($url[3]) && $url[3] !== '' ? $url[3] : 'thanhToan';
    } else {
        $action = 'buyNow';
    }
    $controllerPath = 'app/controllers/CheckoutController.php';
}

// Route cho trang chi tiết sản phẩm: /san-pham/chi-tiet-san-pham/...
else if (
    isset($url[0]) && strtolower($url[0]) === 'san-pham'
    && isset($url[1]) && strtolower($url[1]) === 'chi-tiet-san-pham'
) {

    $controllerName = 'ProductDetailController';

    // Nếu phần thứ ba của URL là số (ID sản phẩm), đặt action là "index"
    if (isset($url[2]) && is_numeric($url[2])) {
        $action = 'index';
    } else {
        // Nếu phần thứ ba không phải là số, dùng nó làm action (hoặc mặc định "index")
        $action = isset($url[2]) && $url[2] != '' ? $url[2] : 'index';
    }

    $controllerPath = 'app/controllers/ProductDetailController.php';
}

// 8. Route cho trang sản phẩm chung: /san-pham
else if (isset($url[0]) && strtolower($url[0]) === 'san-pham') {
    $controllerName = 'ProductController';
    $action = 'index';
    $controllerPath = 'app/controllers/ProductController.php';
}
// 9. Route cho trang chủ: /trang-chu
else if (isset($url[0]) && strtolower($url[0]) === 'trang-chu') {
    $controllerName = 'HomeController';
    $action = 'index';
    $controllerPath = 'app/controllers/HomeController.php';
}
// 11. Route cho tài khoản: /account/...
else if (isset($url[0]) && strtolower($url[0]) === 'account') {
    if (isset($url[1]) && strtolower($url[1]) === 'profile') {
        $controllerName = 'UserAPIController';
        $action = 'showProfilePage';
        $controllerPath = 'app/controllers/UserAPIController.php';
    } else {
        $controllerName = 'UserAPIController';
        // Sửa default action từ 'index' thành 'showLoginForm' vì AccountController không có method index()
        $action = isset($url[1]) && $url[1] !== '' ? $url[1] : 'showLoginForm';
        $controllerPath = 'app/controllers/UserAPIController.php';
    }
}
// 10. Mặc định: nếu không khớp với các điều kiện trên, chuyển đến HomeController
else {
    $controllerName = isset($url[0]) && $url[0] !== ''
        ? ucfirst($url[0]) . 'Controller'
        : 'HomeController';
    $action = isset($url[1]) && $url[1] !== '' ? $url[1] : 'index';
    $controllerPath = 'app/controllers/' . $controllerName . '.php';
}

// Kiểm tra tồn tại file controller
if (!file_exists($controllerPath)) {
    die('Controller not found');
}
require_once $controllerPath;

// Khởi tạo controller
$controller = new $controllerName();

// Kiểm tra tồn tại method action trong controller
if (!method_exists($controller, $action)) {
    die('Action not found');
}

// Gọi method action với các tham số còn lại của URL (nếu có)
call_user_func_array([$controller, $action], array_slice($url, 2));
?>