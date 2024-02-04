<?php
session_start();

if (isset($_POST['foodItemId']) && isset($_POST['newQuantity'])) {
    $foodItemId = $_POST['foodItemId'];
    $newQuantity = $_POST['newQuantity'];

    // Get the user's ID and use it to form the cart session key
    $userId = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : null;
    $cartKey = ($userId) ? 'cart_' . $userId : '';

    // Check if the user-specific cart session variable exists
    if ($cartKey && !empty($_SESSION[$cartKey])) {
        foreach ($_SESSION[$cartKey] as &$cartItem) {
            if ($cartItem['id'] == $foodItemId) {
                // Update the quantity value
                $cartItem['quantity'] = $newQuantity;

                // Recalculate total price based on the updated quantity
                $cartItem['total_price'] = $cartItem['price'] * $newQuantity;
                break;
            }
        }
    }

    // Save the updated session
    $_SESSION[$cartKey] = array_values($_SESSION[$cartKey]);

    // Return the updated total price to the client
    echo json_encode(['total_price' => number_format($cartItem['total_price'], 2)]);
}
?>
