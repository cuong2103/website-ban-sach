<?php
class CartModel
{
  private $conn;

  public function __construct()
  {
    $this->conn = connectDB();
  }

  public function getOrCreateCartId($userId)
  {
    $stmt = $this->conn->prepare("SELECT cart_id FROM carts WHERE user_id = :user_id LIMIT 1");
    $stmt->execute(['user_id' => (int)$userId]);
    $cart = $stmt->fetch();

    if (!empty($cart['cart_id'])) {
      return (int)$cart['cart_id'];
    }

    $insert = $this->conn->prepare("INSERT INTO carts (user_id) VALUES (:user_id)");
    $insert->execute(['user_id' => (int)$userId]);

    return (int)$this->conn->lastInsertId();
  }

  public function addItem($userId, $bookId, $qty = 1)
  {
    $cartId = $this->getOrCreateCartId($userId);
    $qty = max(1, (int)$qty);

    $book = $this->getBookForCart($bookId);
    if (!$book) {
      return ['ok' => false, 'message' => 'Sách không tồn tại hoặc đã ngừng bán.'];
    }

    if ((int)$book['stock'] <= 0) {
      return ['ok' => false, 'message' => 'Sách hiện đã hết hàng.'];
    }

    $currentItem = $this->findCartItem($cartId, $bookId);
    $currentQty = (int)($currentItem['quantity'] ?? 0);
    $newQty = $currentQty + $qty;

    if ($newQty > (int)$book['stock']) {
      return ['ok' => false, 'message' => 'Số lượng vượt quá tồn kho hiện tại.'];
    }

    if ($currentItem) {
      $stmt = $this->conn->prepare("UPDATE cart_items SET quantity = :quantity, price = :price WHERE cart_id = :cart_id AND book_id = :book_id");
      $stmt->execute([
        'quantity' => $newQty,
        'price' => $this->getEffectivePrice($book),
        'cart_id' => $cartId,
        'book_id' => (int)$bookId,
      ]);
    } else {
      $stmt = $this->conn->prepare("INSERT INTO cart_items (cart_id, book_id, quantity, price) VALUES (:cart_id, :book_id, :quantity, :price)");
      $stmt->execute([
        'cart_id' => $cartId,
        'book_id' => (int)$bookId,
        'quantity' => $qty,
        'price' => $this->getEffectivePrice($book),
      ]);
    }

    return ['ok' => true, 'message' => 'Đã thêm sách vào giỏ hàng.'];
  }

  public function updateItemQuantity($userId, $bookId, $quantity)
  {
    $cartId = $this->getOrCreateCartId($userId);
    $quantity = (int)$quantity;

    if ($quantity <= 0) {
      return $this->removeItem($userId, $bookId);
    }

    $book = $this->getBookForCart($bookId);
    if (!$book) {
      return ['ok' => false, 'message' => 'Sách không tồn tại hoặc đã ngừng bán.'];
    }

    if ($quantity > (int)$book['stock']) {
      return ['ok' => false, 'message' => 'Số lượng vượt quá tồn kho hiện tại.'];
    }

    $stmt = $this->conn->prepare("UPDATE cart_items SET quantity = :quantity, price = :price WHERE cart_id = :cart_id AND book_id = :book_id");
    $stmt->execute([
      'quantity' => $quantity,
      'price' => $this->getEffectivePrice($book),
      'cart_id' => $cartId,
      'book_id' => (int)$bookId,
    ]);

    return ['ok' => true, 'message' => 'Đã cập nhật số lượng sản phẩm.'];
  }

  public function removeItem($userId, $bookId)
  {
    $cartId = $this->getOrCreateCartId($userId);
    $stmt = $this->conn->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id AND book_id = :book_id");
    $stmt->execute([
      'cart_id' => $cartId,
      'book_id' => (int)$bookId,
    ]);

    return ['ok' => true, 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.'];
  }

  public function getCartItems($userId)
  {
    $cartId = $this->getOrCreateCartId($userId);

    $stmt = $this->conn->prepare("
      SELECT 
        ci.book_id,
        ci.quantity,
        ci.price as unit_price,
        b.title,
        b.author,
        b.thumbnail,
        b.stock,
        b.status
      FROM cart_items ci
      INNER JOIN books b ON b.book_id = ci.book_id
      WHERE ci.cart_id = :cart_id
      ORDER BY ci.cart_item_id DESC
    ");
    $stmt->execute(['cart_id' => $cartId]);
    $items = $stmt->fetchAll();

    foreach ($items as &$item) {
      if ((int)$item['status'] !== 1) {
        $item['is_available'] = false;
      } else {
        $item['is_available'] = true;
      }
      $item['line_total'] = (float)$item['unit_price'] * (int)$item['quantity'];
    }

    return $items;
  }

  public function getItemCount($userId)
  {
    $cartId = $this->findCartIdByUser($userId);
    if ($cartId <= 0) {
      return 0;
    }

    $stmt = $this->conn->prepare("SELECT COALESCE(SUM(quantity), 0) as total_qty FROM cart_items WHERE cart_id = :cart_id");
    $stmt->execute(['cart_id' => $cartId]);
    $row = $stmt->fetch();

    return (int)($row['total_qty'] ?? 0);
  }

  public function calculateTotals($userId, $voucher = null)
  {
    $items = $this->getCartItems($userId);

    $subtotal = 0;
    $itemCount = 0;

    foreach ($items as $item) {
      if (!$item['is_available']) {
        continue;
      }
      $subtotal += $item['line_total'];
      $itemCount += (int)$item['quantity'];
    }

    $shippingFee = $subtotal > 0 ? 0 : 0;
    $discount = 0;

    if (!empty($voucher) && !empty($voucher['is_valid'])) {
      if ($voucher['discount_type'] === 'percent') {
        $discount = $subtotal * ((float)$voucher['discount_value'] / 100);
        if (!empty($voucher['max_discount'])) {
          $discount = min($discount, (float)$voucher['max_discount']);
        }
      } else {
        $discount = (float)$voucher['discount_value'];
      }
      $discount = min($discount, $subtotal);
    }

    $total = max(0, $subtotal + $shippingFee - $discount);

    return [
      'item_count' => $itemCount,
      'subtotal' => $subtotal,
      'shipping_fee' => $shippingFee,
      'discount' => $discount,
      'total' => $total,
    ];
  }

  public function validateVoucher($code, $subtotal)
  {
    $cleanCode = strtoupper(trim((string)$code));
    if ($cleanCode === '') {
      return [
        'is_valid' => false,
        'message' => 'Vui lòng nhập mã giảm giá.',
      ];
    }

    $stmt = $this->conn->prepare("
      SELECT 
        voucher_id,
        code,
        discount_type,
        discount_value,
        max_discount,
        min_order_value,
        start_date,
        end_date,
        status
      FROM vouchers
      WHERE UPPER(code) = :code
      LIMIT 1
    ");
    $stmt->execute(['code' => $cleanCode]);
    $voucher = $stmt->fetch();

    if (!$voucher) {
      return [
        'is_valid' => false,
        'message' => 'Mã giảm giá không tồn tại.',
      ];
    }

    if ((int)$voucher['status'] !== 1) {
      return [
        'is_valid' => false,
        'message' => 'Mã giảm giá hiện không khả dụng.',
      ];
    }

    $now = time();
    $start = strtotime($voucher['start_date']);
    $end = strtotime($voucher['end_date']);

    if ($start && $now < $start) {
      return [
        'is_valid' => false,
        'message' => 'Mã giảm giá chưa đến thời gian áp dụng.',
      ];
    }

    if ($end && $now > $end) {
      return [
        'is_valid' => false,
        'message' => 'Mã giảm giá đã hết hạn.',
      ];
    }

    if ((float)$subtotal < (float)$voucher['min_order_value']) {
      return [
        'is_valid' => false,
        'message' => 'Đơn hàng chưa đạt giá trị tối thiểu để dùng mã.',
      ];
    }

    return [
      'is_valid' => true,
      'voucher_id' => (int)$voucher['voucher_id'],
      'code' => $voucher['code'],
      'discount_type' => $voucher['discount_type'],
      'discount_value' => (float)$voucher['discount_value'],
      'max_discount' => $voucher['max_discount'] !== null ? (float)$voucher['max_discount'] : null,
      'min_order_value' => (float)$voucher['min_order_value'],
      'message' => 'Áp dụng mã giảm giá thành công.',
    ];
  }

  public function getSuggestedVouchers($limit = 3)
  {
    $stmt = $this->conn->prepare("
      SELECT code, discount_type, discount_value
      FROM vouchers
      WHERE status = 1 AND start_date <= NOW() AND end_date >= NOW()
      ORDER BY end_date ASC
      LIMIT :limit
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  public function getCheckoutCustomer($userId)
  {
    $stmt = $this->conn->prepare("SELECT full_name, email, phone, address FROM users WHERE user_id = :user_id LIMIT 1");
    $stmt->execute(['user_id' => (int)$userId]);
    $user = $stmt->fetch();

    return [
      'full_name' => $user['full_name'] ?? '',
      'email' => $user['email'] ?? '',
      'phone' => $user['phone'] ?? '',
      'address' => $user['address'] ?? '',
    ];
  }

  public function placeOrder($userId, $checkoutData, $voucherCode = '')
  {
    $cartId = $this->findCartIdByUser($userId);
    if ($cartId <= 0) {
      return ['ok' => false, 'message' => 'Giỏ hàng đang trống.'];
    }

    $cartItems = $this->getCartItems($userId);
    if (empty($cartItems)) {
      return ['ok' => false, 'message' => 'Giỏ hàng đang trống.'];
    }

    $validItems = [];
    foreach ($cartItems as $item) {
      if (!$item['is_available']) {
        return ['ok' => false, 'message' => 'Có sản phẩm không còn kinh doanh, vui lòng kiểm tra lại giỏ hàng.'];
      }
      if ((int)$item['stock'] < (int)$item['quantity']) {
        return ['ok' => false, 'message' => 'Số lượng một số sản phẩm vượt tồn kho, vui lòng kiểm tra lại giỏ hàng.'];
      }
      $validItems[] = $item;
    }

    $subtotal = 0;
    foreach ($validItems as $item) {
      $subtotal += (float)$item['line_total'];
    }

    $voucher = null;
    $cleanVoucherCode = strtoupper(trim((string)$voucherCode));
    if ($cleanVoucherCode !== '') {
      $validatedVoucher = $this->validateVoucher($cleanVoucherCode, $subtotal);
      if (empty($validatedVoucher['is_valid'])) {
        return ['ok' => false, 'message' => $validatedVoucher['message'] ?? 'Mã giảm giá không hợp lệ.'];
      }
      $voucher = $validatedVoucher;
    }

    $totals = $this->calculateTotals($userId, $voucher);

    if ($totals['total'] <= 0 && $totals['subtotal'] <= 0) {
      return ['ok' => false, 'message' => 'Giỏ hàng không hợp lệ để thanh toán.'];
    }

    $orderCode = $this->generateOrderCode();

    try {
      $this->conn->beginTransaction();

      $orderStmt = $this->conn->prepare("\n        INSERT INTO orders (\n          user_id, order_code, total_amount, voucher_id, discount_amount, payment_method_id, status_id, shipping_address, phone, note\n        ) VALUES (\n          :user_id, :order_code, :total_amount, :voucher_id, :discount_amount, :payment_method_id, :status_id, :shipping_address, :phone, :note\n        )\n      ");

      $orderStmt->execute([
        'user_id' => (int)$userId,
        'order_code' => $orderCode,
        'total_amount' => (float)$totals['total'],
        'voucher_id' => $voucher['voucher_id'] ?? null,
        'discount_amount' => (float)$totals['discount'],
        'payment_method_id' => 1,
        'status_id' => 1,
        'shipping_address' => $checkoutData['shipping_address'],
        'phone' => $checkoutData['phone'],
        'note' => $checkoutData['note'] ?? null,
      ]);

      $orderId = (int)$this->conn->lastInsertId();

      $itemStmt = $this->conn->prepare("\n        INSERT INTO order_items (order_id, book_id, quantity, price, subtotal)\n        VALUES (:order_id, :book_id, :quantity, :price, :subtotal)\n      ");

      $stockStmt = $this->conn->prepare("\n        UPDATE books\n        SET stock = stock - :quantity\n        WHERE book_id = :book_id AND stock >= :quantity AND status = 1\n      ");

      foreach ($validItems as $item) {
        $quantity = (int)$item['quantity'];
        $unitPrice = (float)$item['unit_price'];
        $lineTotal = (float)$item['line_total'];

        $itemStmt->execute([
          'order_id' => $orderId,
          'book_id' => (int)$item['book_id'],
          'quantity' => $quantity,
          'price' => $unitPrice,
          'subtotal' => $lineTotal,
        ]);

        $stockStmt->execute([
          'quantity' => $quantity,
          'book_id' => (int)$item['book_id'],
        ]);

        if ($stockStmt->rowCount() === 0) {
          throw new Exception('Không thể cập nhật tồn kho, vui lòng thử lại.');
        }
      }

      if (!empty($voucher['voucher_id'])) {
        $voucherUsageStmt = $this->conn->prepare("\n          INSERT INTO voucher_usages (voucher_id, user_id, order_id)\n          VALUES (:voucher_id, :user_id, :order_id)\n        ");
        $voucherUsageStmt->execute([
          'voucher_id' => (int)$voucher['voucher_id'],
          'user_id' => (int)$userId,
          'order_id' => $orderId,
        ]);
      }

      $clearCartStmt = $this->conn->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id");
      $clearCartStmt->execute(['cart_id' => $cartId]);

      $this->conn->commit();

      return [
        'ok' => true,
        'order_id' => $orderId,
        'order_code' => $orderCode,
        'total' => (float)$totals['total'],
      ];
    } catch (Exception $e) {
      if ($this->conn->inTransaction()) {
        $this->conn->rollBack();
      }

      return [
        'ok' => false,
        'message' => $e->getMessage() ?: 'Không thể tạo đơn hàng lúc này.',
      ];
    }
  }

  private function generateOrderCode()
  {
    return 'ORD' . date('YmdHis') . rand(100, 999);
  }

  private function getBookForCart($bookId)
  {
    $stmt = $this->conn->prepare("
      SELECT book_id, price, sale_price, stock, status
      FROM books
      WHERE book_id = :book_id
      LIMIT 1
    ");
    $stmt->execute(['book_id' => (int)$bookId]);

    $book = $stmt->fetch();
    if (!$book || (int)$book['status'] !== 1) {
      return null;
    }

    return $book;
  }

  private function findCartIdByUser($userId)
  {
    $stmt = $this->conn->prepare("SELECT cart_id FROM carts WHERE user_id = :user_id LIMIT 1");
    $stmt->execute(['user_id' => (int)$userId]);
    $cart = $stmt->fetch();

    return (int)($cart['cart_id'] ?? 0);
  }

  private function findCartItem($cartId, $bookId)
  {
    $stmt = $this->conn->prepare("SELECT quantity FROM cart_items WHERE cart_id = :cart_id AND book_id = :book_id LIMIT 1");
    $stmt->execute([
      'cart_id' => (int)$cartId,
      'book_id' => (int)$bookId,
    ]);

    return $stmt->fetch();
  }

  private function getEffectivePrice($book)
  {
    return $book['sale_price'] !== null ? (float)$book['sale_price'] : (float)$book['price'];
  }
}