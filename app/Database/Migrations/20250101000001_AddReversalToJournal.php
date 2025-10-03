<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReversalToJournal extends Migration
{
   public function up()
   {
      $this->forge->addColumn('journal', [
         'is_reversal' => [
            'type' => 'TINYINT',
            'constraint' => 1,
            'default' => 0,
            'after' => 'reference_id'
         ],
         'reverses_transaction_id' => [
            'type' => 'INT',
            'constraint' => 11,
            'null' => true,
            'after' => 'is_reversal'
         ]
      ]);

      // Add index for better performance
      $this->db->query('ALTER TABLE journal ADD INDEX idx_reversal (is_reversal, reverses_transaction_id)');
   }

   public function down()
   {
      $this->forge->dropColumn('journal', 'is_reversal');
      $this->forge->dropColumn('journal', 'reverses_transaction_id');
   }
}