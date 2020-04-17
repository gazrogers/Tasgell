<?php 

use Codeception\Util\HttpCode;

class UpdateTasksCest
{
    public function _before(ApiTester $I)
    {
        $I->haveInDatabase('tasks', ['id' => 10, 'title' => 'Test task', 'description' => 'Test task description', 'parentId' => null]);
    }

    public function createTaskNonJson(ApiTester $I)
    {
        $I->wantTo("Check input format is checked for update task endpoint");
        $I->sendPOST('/task/update', ['id' => 10, 'title' => 'Test task', 'description' => 'A description for my test task']);
        $I->seeResponseCodeIs(HttpCode::UNSUPPORTED_MEDIA_TYPE);
        $I->seeResponseIsJson();
        $I->seeResponseContains('error');
    }

    public function updateTaskBadData(ApiTester $I)
    {
        $I->wantTo("Check bad input data is rejected with appropriate error message");
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/task/update', ['id' => 10, 'title' => 'Test task', 'description' => '']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('error');
    }

    public function updateTaskMissingField(ApiTester $I)
    {
        $I->wantTo("Check missing field is rejected with appropriate error message");
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/task/update', ['title' => 'Test update task', 'description' => 'Test update description']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('error');
        $I->seeResponseContains('Update requests require \'id\'');
    }

    public function updateTask(ApiTester $I)
    {
        $I->wantTo("Update a task");
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/task/update', ['id' => 10, 'title' => 'Test update task', 'description' => 'An updated description for my test task']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->dontSeeInDatabase('tasks', ['id' => 10, 'title' => 'Test task', 'description' => 'Test task description']);
        $I->seeInDatabase('tasks', ['id' => 10, 'title' => 'Test update task', 'description' => 'An updated description for my test task']);
        $I->seeResponseContains('id');
    }
}
