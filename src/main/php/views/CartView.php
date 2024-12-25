<div class="container">
    <div class="view_panel">
        <h1 class="view_header">
            Your Cart
        </h1>
        <div class="view_content">
            <?php
            if (empty($cartItems)): ?>
                <h3 class="view_widget_central">
                    Your cart is empty. Start exploring bundles!
                </h3>
            <?php else: ?>
                <div class="cart-items">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item">
                            <img
                                class="cart-item-thumbnail"
                                src="<?php echo $item->getLogo() == null
                                            ? BASE_URL . "src/main/webapp/images/default/noImage.png"
                                            : BASE_URL . "src/main/webapp/images/logos/bundles/" . $item->getLogo(); ?>" />
                            <div class="cart-item-content">
                                <div class="cart-item-header">
                                    <h2>
                                        <?php echo $item->getName(); ?>
                                    </h2>
                                </div>
                                <div class="cart-item-price">
                                    <p>
                                        US$ <?php echo number_format($item->getPrice(), 2); ?>
                                    </p>
                                </div>
                                <div class="cart-item-actions">
                                    <button
                                        class="cart-item-remove-btn"
                                        onClick="remove_item(<?php echo $item->getIdBundle(); ?>)">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h2>Cart Summary</h2>
                    <table class="cart-summary-details">
                        <tr>
                            <th>Total Items</th>
                            <td><?php echo count($cartItems); ?></td>
                        </tr>
                        <tr>
                            <th>Total Price</th>
                            <td>US$ <?php echo number_format($totalPrice, 2); ?></td>
                        </tr>
                    </table>
                    <div class="cart-summary-actions">
                        <div
                            class="cart-checkout-container">
                            <div>Checkout</div>
                        </div>
                        <div class="payment-options">
                            <button
                                onClick="checkout('fawry')"
                                class="fawry">Fawry</button>
                            <button
                                onClick="checkout('paypal')"
                                class="paypal">Paypal</button>
                            <button
                                onClick="checkout('credit')"
                                class="credit">Credit Card</button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>