<?php

namespace tests\unit;

use app\models\User;

use PHPUnit\Framework\TestCase;
use Yii;

class UserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        User::deleteAll();
        Yii::$app->db->createCommand()->insert(User::tableName(), [
            'username' => 'name',
            'email' => 'name@mail.com',
        ])->execute();

    }

    public function testValidateExistedValues()
    {
        $user = new User([
            'username' => 'name',
            'email' => 'name@mail.com',
        ]);

        $this->assertFalse($user->validate(), 'model is not valid');
        $this->assertArrayHasKey('username', $user->getErrors(), 'check existed username error');
        $this->assertArrayHasKey('email', $user->getErrors(), 'check existed email error');
    }

    public function testValidateEmptyValues()
    {
        $user = new User();

        $this->assertFalse($user->validate(), 'model is not valid');
        $this->assertArrayHasKey('username', $user->getErrors(), 'check username error');
        $this->assertArrayHasKey('email', $user->getErrors(), 'check email error');
    }

    public function testValidateWrongValues()
    {
        $user = new User([
            'username' => 'Wrong % username',
            'email' => 'wrong_email',
        ]);

        $this->assertFalse($user->validate(), 'validate incorrect username and email');
        $this->assertArrayHasKey('username', $user->getErrors(), 'check incorrect username error');
        $this->assertArrayHasKey('email', $user->getErrors(), 'check incorrect email error');
    }

    public function testValidateCorrectValues()
    {
        $user = new User([
            'username' => 'TestUsername',
            'email' => 'test@email.com',
        ]);

        $this->assertTrue($user->validate(), 'Correct model is valid');
    }

    public function testSaveIntoDatabase()
    {
        $user = new User([
            'username' => 'TestUsername',
            'email' => 'test@mail.com',
        ]);

        $this->assertTrue($user->save(), 'saved to database');
    }
}