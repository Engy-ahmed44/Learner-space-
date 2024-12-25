<div class="container">
    <div class="view_panel">
        <h1 class="view_header">
            Transaction #<?php echo $transaction->getId(); ?>
        </h1>
        <div class="view_content">
            <!-- Transaction Information -->
            <table class="transaction-details">
                <tr>
                    <th>
                        Transaction ID
                    </th>
                    <td>
                        <?php echo $transaction->getId(); ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        Date
                    </th>
                    <td>
                    </td>
                </tr>
                <tr>
                    <th>
                        Status
                    </th>
                    <td class="<?php echo strtolower($transaction->getStateAsString()); ?>">
                        <?php echo strtolower($transaction->getStateAsString()); ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        Total Amount
                    </th>
                    <td>
                        US$ <?php echo number_format($transaction->getAmount(), 2); ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        Payment Method
                    </th>
                    <td>
                        <?php echo $transaction->getGateway(); ?>
                    </td>
                </tr>
            </table>

            <!-- Additional Information -->
            <div class="view_widget">
                <h2>
                    Additional Information
                </h2>
                <p>
                    If you have any questions about this transaction, please contact our support team with the Transaction ID.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Make the table fit the screen width */
    .transaction-details {
        width: 100%;
        /* Make table width 100% of its container */
        border-collapse: collapse;
        /* Collapse borders between cells */
    }

    .transaction-details th,
    .transaction-details td {
        padding: 12px;
        /* Add padding inside cells for better readability */
        text-align: left;
        /* Align text to the left for consistency */
        border: 1px solid #ddd;
        /* Add borders around cells */
    }

    .transaction-details th {
        background-color: #f4f4f4;
        /* Light background for table headers */
    }

    .transaction-details td {
        background-color: #fff;
        /* White background for data cells */
    }

    /* Make the container and table responsive */
    .container {
        max-width: 100%;
        /* Allow container to be full width */
        padding: 20px;
    }

    @media screen and (max-width: 768px) {

        .transaction-details th,
        .transaction-details td {
            font-size: 14px;
            /* Adjust font size for smaller screens */
            padding: 8px;
            /* Reduce padding on smaller screens */
        }
    }
</style>