<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 4px; }
        .summary-card { background: #f9f9f9; padding: 15px; margin: 10px 0; border-radius: 4px; border-left: 4px solid #337ab7; }
        .income { color: #28a745; }
        .expense { color: #dc3545; }
        .net-positive { color: #28a745; }
        .net-negative { color: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f5f5f5; }
        .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; color: #666; font-size: 12px; }
        .header { border-bottom: 2px solid #337ab7; padding-bottom: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin: 0; color: #337ab7;">Daily Transaction Summary</h2>
            <p style="margin: 5px 0 0 0; color: #666;"><?php echo date('M d, Y', strtotime($date)); ?></p>
        </div>

        <div class="summary-card">
            <h3 style="margin-top: 0;">Overview</h3>
            <p><strong>Transactions:</strong> <?php echo $summary['total_count']; ?></p>
            <p class="income"><strong>Income:</strong> $<?php echo number_format($summary['total_income'], 2); ?></p>
            <p class="expense"><strong>Expense:</strong> $<?php echo number_format($summary['total_expense'], 2); ?></p>
            <p class="<?php echo $summary['net'] >= 0 ? 'net-positive' : 'net-negative'; ?>">
                <strong>Net Change:</strong> $<?php echo number_format($summary['net'], 2); ?>
            </p>
        </div>

        <?php if (!empty($summary['expense_by_tag'])): ?>
        <div class="summary-card">
            <h3 style="margin-top: 0;">Top Expenses by Tag</h3>
            <table>
                <tr>
                    <th>Tag</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
                <?php foreach ($summary['expense_by_tag'] as $tag): ?>
                <tr>
                    <td><?php echo CHtml::encode($tag['tag_name']); ?></td>
                    <td style="text-align: right;">$<?php echo number_format($tag['total'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>

        <?php if (!empty($summary['income_by_tag'])): ?>
        <div class="summary-card">
            <h3 style="margin-top: 0;">Income by Tag</h3>
            <table>
                <tr>
                    <th>Tag</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
                <?php foreach ($summary['income_by_tag'] as $tag): ?>
                <tr>
                    <td><?php echo CHtml::encode($tag['tag_name']); ?></td>
                    <td style="text-align: right;">$<?php echo number_format($tag['total'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>

        <p>
            <a href="<?php echo Yii::app()->createUrl('transaction/admin'); ?>" style="color: #337ab7; text-decoration: none;">View All Transactions &rarr;</a>
        </p>

        <div class="footer">
            <p>You are receiving this email because you have enabled daily summaries in your account settings.</p>
            <p>
                <a href="<?php echo Yii::app()->createUrl('user/emailPreferences'); ?>">Manage email preferences</a>
            </p>
        </div>
    </div>
</body>
</html>
