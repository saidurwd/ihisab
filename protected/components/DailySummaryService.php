<?php

class DailySummaryService
{
    public function buildSummary($userId, $date)
    {
        $start = $date . ' 00:00:00';
        $end = date('Y-m-d', strtotime($date . ' +1 day')) . ' 00:00:00';

        $exists = Yii::app()->db->createCommand()
            ->select('COUNT(*)')
            ->from('{{transaction}}')
            ->where('user=:uid AND created>=:start AND created<:end AND status=1', array(
                ':uid' => $userId,
                ':start' => $start,
                ':end' => $end
            ))
            ->queryScalar();

        if ((int)$exists === 0) {
            return null;
        }

        $totals = Yii::app()->db->createCommand()
            ->select('
                SUM(CASE WHEN transaction_type IN(2,4) THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN transaction_type IN(1) THEN amount ELSE 0 END) as total_expense,
                COUNT(*) as total_count
            ')
            ->from('{{transaction}}')
            ->where('user=:uid AND created>=:start AND created<:end AND status=1', array(
                ':uid' => $userId,
                ':start' => $start,
                ':end' => $end
            ))
            ->queryRow();

        $expenseByTag = Yii::app()->db->createCommand()
            ->select('t2.tag_name, SUM(t.amount) as total')
            ->from('{{transaction}} t')
            ->join('{{transaction_tag}} tt', 't.id=tt.transaction')
            ->join('{{tag}} t2', 'tt.tag=t2.id')
            ->where('t.user=:uid AND t.created>=:start AND t.created<:end AND t.status=1 AND t.transaction_type IN(1)', array(
                ':uid' => $userId,
                ':start' => $start,
                ':end' => $end
            ))
            ->group('tt.tag, t2.tag_name')
            ->order('total DESC')
            ->limit(3)
            ->queryAll();

        $incomeByTag = Yii::app()->db->createCommand()
            ->select('t2.tag_name, SUM(t.amount) as total')
            ->from('{{transaction}} t')
            ->join('{{transaction_tag}} tt', 't.id=tt.transaction')
            ->join('{{tag}} t2', 'tt.tag=t2.id')
            ->where('t.user=:uid AND t.created>=:start AND t.created<:end AND t.status=1 AND t.transaction_type IN(2,4)', array(
                ':uid' => $userId,
                ':start' => $start,
                ':end' => $end
            ))
            ->group('tt.tag, t2.tag_name')
            ->order('total DESC')
            ->limit(3)
            ->queryAll();

        return array(
            'date' => $date,
            'total_count' => (int)$totals['total_count'],
            'total_income' => (float)$totals['total_income'],
            'total_expense' => (float)$totals['total_expense'],
            'net' => (float)$totals['total_income'] - (float)$totals['total_expense'],
            'expense_by_tag' => $expenseByTag,
            'income_by_tag' => $incomeByTag,
        );
    }
}
