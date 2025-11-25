<?php
header('Content-Type: application/json');

// Dummy product data with potential discounts
$products = [
    [
        'id' => 1,
        'name' => 'Awesome T-Shirt',
        'price' => 25.00,
        'discount_type' => 'percentage',
        'discount_amount' => 0.20, // 20% off
        'discount_info' => '20% off this item!',
    ],
    [
        'id' => 2,
        'name' => 'Cool Mug',
        'price' => 15.00,
    ],
    [
        'id' => 3,
        'name' => 'Fancy Hat',
        'price' => 30.00,
        'discount_type' => 'fixed',
        'discount_amount' => 5.00, // $5 off
        'discount_info' => '$5 off on this hat!',
    ],
];

// Dummy coupon data
$coupons = [
    'SUMMER20' => ['type' => 'percentage', 'amount' => 0.20, 'message' => '20% off your order!'],
    'SAVE10' => ['type' => 'fixed', 'amount' => 10.00, 'message' => '$10 off your order!'],
];

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'getProductsWithDiscounts':
        $productsWithDiscounts = array_map(function ($product) {
            if (isset($product['discount_type']) && isset($product['discount_amount'])) {
                if ($product['discount_type'] === 'percentage') {
                    $product['discounted_price'] = $product['price'] * (1 - $product['discount_amount']);
                } elseif ($product['discount_type'] === 'fixed') {
                    $product['discounted_price'] = max(0, $product['price'] - $product['discount_amount']);
                }
            }
            return $product;
        }, $products);
        echo json_encode(['products' => $productsWithDiscounts]);
        break;

    case 'applyCoupon':
        $couponCode = $_GET['code'] ?? '';
        if (isset($coupons[$couponCode])) {
            $coupon = $coupons[$couponCode];
            // In a real scenario, you would apply this discount to the cart total
            echo json_encode(['status' => 'success', 'message' => $coupon['message'], 'discounted_amount' => $coupon['amount']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid coupon code.']);
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}
?>