<div class="container">
    <div class="view_panel">
        <h1 class="view_header">
            Bill #<?php echo $bill->getId(); ?>
        </h1>
        <div class="view_content">
            <!-- Bill Information -->
            <table class="bill-details">
                <tr>
                    <th>
                        Bill ID
                    </th>
                    <td>
                        <?php echo $bill->getId(); ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        Transaction ID
                    </th>
                    <td>
                        <?php echo $bill->getTransactionId(); ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        Date Created
                    </th>
                    <td>
                        <?php echo $bill->getCreatedAt(); ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        Total Amount
                    </th>
                    <td>
                        US$ <?php echo number_format($bill->getTotalAmount(), 2); ?>
                    </td>
                </tr>
            </table>

            <!-- Bundles Information -->
            <div class="view_widget">
                <h2>
                    Bundles Included in This Bill
                </h2>
                <?php if (count($bill->getBundles()) > 0): ?>
                    <ul class="bundle-list">
                        <?php foreach ($bill->getBundles() as $bundle): ?>
                            <li class="bundle-item">
                                <div class="bundle-info">
                                    <h3><?php echo $bundle->getName(); ?></h3>
                                    <p><strong>Price:</strong> US$ <?php echo number_format($bundle->getPrice(), 2); ?></p>
                                    <p><strong>Description:</strong> <?php echo $bundle->getDescription(); ?></p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No bundles associated with this bill.</p>
                <?php endif; ?>
            </div>

            <!-- Additional Information -->
            <div class="view_widget">
                <h2>
                    Additional Information
                </h2>
                <p>
                    If you have any questions about this bill, please contact our support team with the Bill ID.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Make the table fit the screen width */
    .bill-details {
        width: 100%;
        border-collapse: collapse;
    }

    .bill-details th,
    .bill-details td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ddd;
    }

    .bill-details th {
        background-color: #f4f4f4;
    }

    .bill-details td {
        background-color: #fff;
    }

    .bundle-list {
        list-style-type: none;
        padding-left: 0;
    }

    .bundle-item {
        border-bottom: 1px solid #ddd;
        padding: 10px 0;
    }

    .bundle-item:last-child {
        border-bottom: none;
    }

    .bundle-info h3 {
        margin: 0;
        font-size: 1.2em;
    }

    .bundle-info p {
        margin: 5px 0;
    }

    /* Make the container and table responsive */
    .container {
        max-width: 100%;
        padding: 20px;
    }

    @media screen and (max-width: 768px) {

        .bill-details th,
        .bill-details td {
            font-size: 14px;
            padding: 8px;
        }
    }
</style>