<?php

namespace App\Migrations;

use T4\Orm\Migration;
//use App\Models\Role;
//use App\Models\User;

class m_1503600693_m_0000000001_CreateWebApp extends Migration
{
    public function up()
    {
//        if (!$this->existsTable('__blocks')) {
//            $this->createTable('__blocks', [
//                'section'   => ['type'=>'int'],
//                'path'      => ['type'=>'string'],
//                'template'  => ['type'=>'string'],
//                'options'   => ['type'=>'text'],
//                'order'     => ['type'=>'int'],
//            ], [
//                    ['columns'=>['section']],
//                    ['columns'=>['order']],
//                ]
//            );
//        };
        if (!$this->existsTable('__users')) {
            $this->createTable('__users', [
                'email'     => ['type'=>'string'],
                'password'  => ['type'=>'string'],
                'name' => ['type'=>'string'],
                'surname' => ['type'=>'string'],
                'registration_date' => ['type' => 'date'],
                'token' => ['type'=>'string']
            ], [
                ['columns' => ['email']],
                ['columns' => ['token']],
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
                'entry_datetime' => ['type' => 'datetime'],
                'exit_datetime' => ['type' => 'datetime']
            ]);
            $roleAdminId = $this->insert('__user_roles', [
                'name' => 'admin',
                'title' => 'Администратор',
            ]);
            $userAdminId = $this->insert('__users', [
                'email' => 'admin@t4.org',
//                'password' => \T4\Crypt\Helpers::hashPassword('123456'),
                'password' => password_hash('123456', PASSWORD_DEFAULT),
            ]);
            $this->insert('__user_roles_to___users', [
                '__user_id' => $userAdminId,
                '__role_id' => $roleAdminId,
            ]);
            $this->createTable('__user_sessions', [
                'hash'          => ['type'=>'string'],
                '__user_id'     => ['type'=>'link'],
                'userAgentHash' => ['type'=>'string'],
            ], [
                'hash'  => ['columns'=>['hash']],
                'user'  => ['columns'=>['__user_id']],
                'ua'    => ['columns'=>['userAgentHash']],
            ]);
        }
    }
    public function down()
    {
        $this->dropTable('__user_sessions');
        $this->dropTable('__user_roles_to___users');
        $this->dropTable('__user_roles');
        $this->dropTable('__users');
        $this->dropTable('__user_groups');
        $this->dropTable('__user_groups_to___users');
//        $this->dropTable('__blocks');
    }
}