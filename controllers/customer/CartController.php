<?php
class CartController
{
  private $cartModel;

  public function __construct()
  {
    $this->cartModel = new CartModel();
  }

  public function index()
  {
    $userId = (int)($_SESSION['currentUser']['id'] ?? 0);
    if ($userId <= 0) {
      redirect('login');
    }

    $items = $this->cartModel->getCartItems($userId);
    $appliedVoucher = $_SESSION['cart_voucher'] ?? null;

    $baseTotals = $this->cartModel->calculateTotals($userId, null);

    if (!empty($appliedVoucher)) {
      $validatedVoucher = $this->cartModel->validateVoucher($appliedVoucher['code'] ?? '', $baseTotals['subtotal']);
      if (!empty($validatedVoucher['is_valid'])) {
        $appliedVoucher = $validatedVoucher;
        $_SESSION['cart_voucher'] = [
          'code' => $validatedVoucher['code'],
        ];
      } else {
        $appliedVoucher = null;
        unset($_SESSION['cart_voucher']);
      }
    }

    $totals = $this->cartModel->calculateTotals($userId, $appliedVoucher);
    $suggestedVouchers = $this->cartModel->getSuggestedVouchers(3);

    require_once './views/customer/cart.php';
  }

  public function add()
  {
    $userId = (int)($_SESSION['currentUser']['id'] ?? 0);
    if ($userId <= 0) {
      Message::set('error', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.');
      redirect('login');
    }

    $bookId = (int)($_GET['id'] ?? 0);
    $qty = (int)($_GET['qty'] ?? 1);

    if ($bookId <= 0) {
      Message::set('error', 'Sản phẩm không hợp lệ.');
      redirect('books');
    }

    $result = $this->cartModel->addItem($userId, $bookId, $qty);

    if (!empty($result['ok'])) {
      Message::set('success', $result['message']);
    } else {
      Message::set('error', $result['message'] ?? 'Không thể thêm sản phẩm vào giỏ hàng.');
    }

    redirect('cart');
  }

  public function update()
  {
    $userId = (int)($_SESSION['currentUser']['id'] ?? 0);
    if ($userId <= 0) {
      Message::set('error', 'Vui lòng đăng nhập để thao tác giỏ hàng.');
      redirect('login');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('cart');
    }

    $bookId = (int)($_POST['book_id'] ?? 0);
    $mode = trim($_POST['mode'] ?? '');

    if ($bookId <= 0) {
      Message::set('error', 'Sản phẩm không hợp lệ.');
      redirect('cart');
    }

    $currentQty = (int)($_POST['current_qty'] ?? 1);
    $targetQty = $currentQty;

    if ($mode === 'inc') {
      $targetQty = $currentQty + 1;
    } elseif ($mode === 'dec') {
      $targetQty = $currentQty - 1;
    } else {
      $targetQty = (int)($_POST['quantity'] ?? $currentQty);
    }

    $result = $this->cartModel->updateItemQuantity($userId, $bookId, $targetQty);

    if (!empty($result['ok'])) {
      Message::set('success', $result['message']);
    } else {
      Message::set('error', $result['message'] ?? 'Không thể cập nhật giỏ hàng.');
    }

    redirect('cart');
  }

  public function remove()
  {
    $userId = (int)($_SESSION['currentUser']['id'] ?? 0);
    if ($userId <= 0) {
      Message::set('error', 'Vui lòng đăng nhập để thao tác giỏ hàng.');
      redirect('login');
    }

    $bookId = (int)($_GET['book_id'] ?? 0);
    if ($bookId <= 0) {
      Message::set('error', 'Sản phẩm không hợp lệ.');
      redirect('cart');
    }

    $result = $this->cartModel->removeItem($userId, $bookId);

    if (!empty($result['ok'])) {
      Message::set('success', $result['message']);
    } else {
      Message::set('error', $result['message'] ?? 'Không thể xóa sản phẩm.');
    }

    redirect('cart');
  }

  public function applyVoucher()
  {
    $userId = (int)($_SESSION['currentUser']['id'] ?? 0);
    if ($userId <= 0) {
      Message::set('error', 'Vui lòng đăng nhập để áp dụng mã giảm giá.');
      redirect('login');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('cart');
    }

    $code = trim($_POST['voucher_code'] ?? '');

    $totals = $this->cartModel->calculateTotals($userId, null);
    $validated = $this->cartModel->validateVoucher($code, $totals['subtotal']);

    if (!empty($validated['is_valid'])) {
      $_SESSION['cart_voucher'] = [
        'code' => $validated['code'],
      ];
      Message::set('success', $validated['message']);
    } else {
      unset($_SESSION['cart_voucher']);
      Message::set('error', $validated['message'] ?? 'Không thể áp dụng mã giảm giá.');
    }

    redirect('cart');
  }

  public function clearVoucher()
  {
    unset($_SESSION['cart_voucher']);
    Message::set('success', 'Đã gỡ mã giảm giá.');
    redirect('cart');
  }

  public function checkout()
  {
    $userId = (int)($_SESSION['currentUser']['id'] ?? 0);
    if ($userId <= 0) {
      redirect('login');
    }

    $items = $this->cartModel->getCartItems($userId);
    if (empty($items)) {
      Message::set('error', 'Giỏ hàng đang trống, chưa thể thanh toán.');
      redirect('cart');
    }

    $appliedVoucher = $_SESSION['cart_voucher'] ?? null;
    $baseTotals = $this->cartModel->calculateTotals($userId, null);

    if (!empty($appliedVoucher)) {
      $validatedVoucher = $this->cartModel->validateVoucher($appliedVoucher['code'] ?? '', $baseTotals['subtotal']);
      if (!empty($validatedVoucher['is_valid'])) {
        $appliedVoucher = $validatedVoucher;
      } else {
        $appliedVoucher = null;
        unset($_SESSION['cart_voucher']);
      }
    }

    $totals = $this->cartModel->calculateTotals($userId, $appliedVoucher);
    $customer = $this->cartModel->getCheckoutCustomer($userId);

    $checkoutOld = $_SESSION['checkout_old'] ?? [];

    require_once './views/customer/checkout.php';
  }

  public function placeOrder()
  {
    $userId = (int)($_SESSION['currentUser']['id'] ?? 0);
    if ($userId <= 0) {
      redirect('login');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('checkout');
    }

    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $addressLine = trim($_POST['address_line'] ?? '');
    $ward = trim($_POST['ward'] ?? '');
    $district = trim($_POST['district'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $note = trim($_POST['note'] ?? '');

    $_SESSION['checkout_old'] = [
      'full_name' => $fullName,
      'email' => $email,
      'phone' => $phone,
      'address_line' => $addressLine,
      'ward' => $ward,
      'district' => $district,
      'city' => $city,
      'note' => $note,
    ];

    $errors = validate([
      'full_name' => $fullName,
      'email' => $email,
      'phone' => $phone,
      'address_line' => $addressLine,
      'ward' => $ward,
      'district' => $district,
      'city' => $city,
    ], [
      'full_name' => 'required|min:3',
      'email' => 'required|email',
      'phone' => 'required|phone',
      'address_line' => 'required|min:5',
      'ward' => 'required',
      'district' => 'required',
      'city' => 'required',
    ]);

    if (!empty($errors)) {
      Message::set('error', 'Vui lòng điền đầy đủ thông tin giao hàng hợp lệ.');
      redirect('checkout');
    }

    $shippingAddress = $addressLine . ', ' . $ward . ', ' . $district . ', ' . $city;
    $voucherCode = $_SESSION['cart_voucher']['code'] ?? '';

    $result = $this->cartModel->placeOrder($userId, [
      'shipping_address' => $shippingAddress,
      'phone' => $phone,
      'note' => $note,
    ], $voucherCode);

    if (empty($result['ok'])) {
      Message::set('error', $result['message'] ?? 'Không thể đặt hàng, vui lòng thử lại.');
      redirect('checkout');
    }

    unset($_SESSION['cart_voucher']);
    unset($_SESSION['checkout_old']);

    $_SESSION['last_order'] = [
      'order_code' => $result['order_code'],
      'total' => $result['total'],
    ];

    redirect('checkout-success');
  }

  public function success()
  {
    $lastOrder = $_SESSION['last_order'] ?? null;
    if (empty($lastOrder)) {
      redirect('home');
    }

    unset($_SESSION['last_order']);

    require_once './views/customer/checkout_success.php';
  }

  public function history()
  {
    $userId = (int)($_SESSION['currentUser']['id'] ?? 0);
    if ($userId <= 0) {
      redirect('login');
    }

    $orders = $this->cartModel->getOrderHistory($userId);

    require_once './views/customer/order_history.php';
  }

  public function orderDetail()
  {
    $userId = (int)($_SESSION['currentUser']['id'] ?? 0);
    if ($userId <= 0) {
      redirect('login');
    }

    $orderCode = trim($_GET['code'] ?? '');
    if ($orderCode === '') {
      redirect('orders');
    }

    $order = $this->cartModel->getOrderDetailByCode($userId, $orderCode);
    if (!$order) {
      Message::set('error', 'Không tìm thấy đơn hàng.');
      redirect('orders');
    }

    require_once './views/customer/order_detail.php';
  }
}