<?php

namespace verbb\formie\migrations;

use verbb\formie\helpers\Table;

use craft\db\Migration;
use craft\db\Query;

class m260916_000000_payment_submission_finalized extends Migration
{
    public function safeUp(): bool
    {
        if (!$this->db->columnExists(Table::FORMIE_PAYMENTS, 'submissionFinalized')) {
            $this->addColumn(Table::FORMIE_PAYMENTS, 'submissionFinalized', $this->boolean()->notNull()->defaultValue(false)->after('redirectUrl'));

            $completedSubmissionIds = (new Query())
                ->select(['id'])
                ->from(Table::FORMIE_SUBMISSIONS)
                ->where(['isIncomplete' => false]);

            $this->update(Table::FORMIE_PAYMENTS, [
                'submissionFinalized' => true,
            ], [
                'in',
                'submissionId',
                $completedSubmissionIds,
            ]);
        }

        return true;
    }

    public function safeDown(): bool
    {
        echo "m260916_000000_payment_submission_finalized cannot be reverted.\n";
        return false;
    }
}
