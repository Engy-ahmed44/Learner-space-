<div class="container">
    <div class="view_panel">
        <h1 class="view_header">
            Make a Donation
        </h1>
        <div class="view_content">
            <!-- Donation Form -->
            <form method="POST" action="<?php echo BASE_URL; ?>donation/donate" class="donation-form">
                <!-- Donation Amount -->
                <div class="form-group">
                    <label for="donationAmount">Donation Amount (USD):</label>
                    <input type="number" id="donationAmount" name="donationAmount" min="1" step="1" placeholder="Enter amount to donate" required>
                </div>

                <!-- Payment Method Selection -->
                <div class="form-group">
                    <label for="paymentMethod">Choose Payment Method:</label>
                    <select id="paymentMethod" name="paymentMethod" required>
                        <option value="credit">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="fawry">Fawry</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" class="submit-btn">Donate Now</button>
                </div>
            </form>

            <!-- Additional Information -->
            <div class="view_widget">
                <h2>
                    Additional Information
                </h2>
                <p>
                    Your donation helps us continue our mission and make a difference. If you have any questions, feel free to contact our support team.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    /* General form styling */
    .donation-form {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .donation-form .form-group {
        margin-bottom: 15px;
    }

    .donation-form label {
        font-size: 1.1em;
        margin-bottom: 5px;
        display: block;
    }

    .donation-form input,
    .donation-form select {
        width: 100%;
        padding: 10px;
        font-size: 1em;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .donation-form input[type="number"] {
        max-width: 250px;
    }

    .donation-form button {
        width: 100%;
        padding: 12px;
        font-size: 1.1em;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .donation-form button:hover {
        background-color: #218838;
    }

    .view_widget {
        margin-top: 30px;
    }

    .view_widget h2 {
        font-size: 1.5em;
        margin-bottom: 10px;
    }

    .view_widget p {
        font-size: 1.1em;
        color: #555;
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
        .donation-form {
            padding: 15px;
        }

        .donation-form button {
            font-size: 1em;
        }
    }
</style>