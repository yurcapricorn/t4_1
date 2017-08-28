<?php

namespace App\Migrations;

use T4\Orm\Migration;

class m_1503600693_m_0000000001_CreateWebApp extends Migration
{
    public function up()
    {
        if (!$this->existsTable('__users')) {
            $this->createTable('__users', [
                'email' => ['type' => 'string'],
                'password' => ['type' => 'string'],
                'name' => ['type' => 'string'],
                'surname' => ['type' => 'string'],
                'registration_date' => ['type' => 'date'],
                'token' => ['type' => 'string']
            ], [
                ['type' => 'unique', 'columns' => ['email']],
                ['type' => 'unique', 'columns' => ['token']],
            ]);
            $this->createTable('__user_roles', [
                'name' => ['type' => 'string'],
                'title' => ['type' => 'string'],
            ], [
                ['type' => 'unique', 'columns' => ['name']]
            ]);
            $this->createTable('__user_roles_to___users', [
                '__user_id' => ['type' => 'link'],
                '__role_id' => ['type' => 'link'],
            ]);
            $this->createTable('__user_groups', [
                'name' => ['type' => 'string'],
            ], [
                ['type' => 'unique', 'columns' => ['name']]
            ]);
            $this->createTable('__user_groups_to___users', [
                '__user_id' => ['type' => 'link'],
                '__group_id' => ['type' => 'link'],
                'add_datetime' => ['type' => 'datetime', 'default' => 'current_timestamp'],
                'expired_datetime' => ['type' => 'datetime']
            ]);
            $roleAdminId = $this->insert('__user_roles', [
                'name' => 'admin',
                'title' => 'Администратор',
            ]);
            $roleAdminId = $this->insert('__user_roles', [
                'name' => 'user',
                'title' => 'Пользователь',
            ]);
            $userAdminId = $this->insert('__users', [
                'email' => 'admin@t4.org',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
            ]);
            $this->insert('__user_roles_to___users', [
                '__user_id' => $userAdminId,
                '__role_id' => $roleAdminId,
            ]);
            $this->createTable('__user_sessions', [
                'hash' => ['type' => 'string'],
                '__user_id' => ['type' => 'link'],
                'userAgentHash' => ['type' => 'string'],
            ], [
                'hash' => ['columns' => ['hash']],
                'user' => ['columns' => ['__user_id']],
                'ua' => ['columns' => ['userAgentHash']],
            ]);

            $conn = $this->db;
//            $conn->execute('ALTER TABLE __user_groups_to___users MODIFY COLUMN add_datetime datetime DEFAULT CURRENT_TIMESTAMP');
//            $conn->execute('ALTER TABLE __user_groups_to___users MODIFY COLUMN expired_datetime datetime DEFAULT (CURRENT_TIMESTAMP + 100)');
//            $conn->execute('CREATE TRIGGER `test` AFTER INSERT ON `__user_groups_to___users` FOR EACH ROW BEGIN UPDATE __user_groups_to___users SET expired_datetime = CURRENT_TIMESTAMP; END');



//            var_dump($sth);
        }
    }

    public function down()
    {
        //$this->dropTable('__user_sessions');
        $this->dropTable('__user_roles_to___users');
        $this->dropTable('__user_roles');
        $this->dropTable('__users');
        //$this->dropTable('__user_groups_to___users');
        $this->dropTable('__user_groups');
    }
}