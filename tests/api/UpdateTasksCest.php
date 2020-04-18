<?php 

use Codeception\Util\HttpCode;

class UpdateTasksCest
{
    public function _before(ApiTester $I)
    {
        $I->haveInDatabase('tasks', ['id' => 10, 'title' => 'Test task', 'description' => 'Test task description', 'parentId' => null]);
        $I->haveInDatabase('tasks', ['id' => 11, 'title' => 'Test parent task', 'description' => 'Test task for using in the change parent test', 'parentId' => null]);
    }

    public function createTaskNonJson(ApiTester $I)
    {
        $I->wantTo("Check input format is checked for update task endpoint");
        $I->sendPUT('/task/10', ['title' => 'Test task', 'description' => 'A description for my test task']);
        $I->seeResponseCodeIs(HttpCode::UNSUPPORTED_MEDIA_TYPE);
        $I->seeResponseIsJson();
        $I->seeResponseContains('error');
    }

    public function updateTaskBadData(ApiTester $I)
    {
        $I->wantTo("Check bad input data is rejected with appropriate error message");
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPUT('/task/10', ['title' => 'Test task', 'description' => '']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('error');
    }

    public function updateTask(ApiTester $I)
    {
        $I->wantTo("Update a task");
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPUT('/task/10', ['title' => 'Test update task', 'description' => 'An updated description for my test task']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->dontSeeInDatabase('tasks', ['id' => 10, 'title' => 'Test task', 'description' => 'Test task description']);
        $I->seeInDatabase('tasks', ['id' => 10, 'title' => 'Test update task', 'description' => 'An updated description for my test task']);
        $I->seeResponseContains('id');
    }

    public function updateTaskTitleOnly(ApiTester $I)
    {
        $I->wantTo("Update a task title only");
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPUT('/task/10', ['title' => 'Test update task']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->dontSeeInDatabase('tasks', ['id' => 10, 'title' => 'Test task', 'description' => 'Test task description']);
        $I->seeInDatabase('tasks', ['id' => 10, 'title' => 'Test update task', 'description' => 'Test task description']);
        $I->seeResponseContains('id');
    }

    public function updateTaskDescriptionOnly(ApiTester $I)
    {
        $I->wantTo("Update a task description only");
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPUT('/task/10', ['description' => 'An updated description for my test task']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->dontSeeInDatabase('tasks', ['id' => 10, 'title' => 'Test task', 'description' => 'Test task description']);
        $I->seeInDatabase('tasks', ['id' => 10, 'title' => 'Test task', 'description' => 'An updated description for my test task']);
        $I->seeResponseContains('id');
    }

    public function updateTaskParentOnly(ApiTester $I)
    {
        $I->wantTo("Update a task parent only");
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPUT('/task/10', ['parentId' => 11]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->dontSeeInDatabase('tasks', ['id' => 10, 'title' => 'Test task', 'description' => 'Test task description', 'parentId' => null]);
        $I->seeInDatabase('tasks', ['id' => 10, 'title' => 'Test task', 'description' => 'Test task description', 'parentId' => 11]);
        $I->seeResponseContains('id');
    }
}
