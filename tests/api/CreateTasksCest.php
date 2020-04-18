<?php 

use Codeception\Util\HttpCode;

class CreateTasksCest
{
    public function _before(ApiTester $I)
    {
    }

    public function createTaskNonJson(ApiTester $I)
    {
        $I->wantTo("Check input format is checked for create task endpoint");
        $I->sendPOST('/task', ['title' => 'Test task', 'description' => 'A description for my test task']);
        $I->seeResponseCodeIs(HttpCode::UNSUPPORTED_MEDIA_TYPE);
        $I->seeResponseIsJson();
        $I->seeResponseContains('error');
    }

    public function createTaskBadData(ApiTester $I)
    {
        $I->wantTo("Check bad input data is rejected with appropriate error message");
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/task', ['title' => 'Test task', 'description' => '']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('error');
    }

    public function createTaskMissingField(ApiTester $I)
    {
        $I->wantTo("Check missing field is rejected with appropriate error message");
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/task', ['title' => 'Test task']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('error');
        $I->seeResponseContains('Create requests require \'description\'');
    }

    public function createTaskWithoutParent(ApiTester $I)
    {
        $I->wantTo("Create a new task with no parent task");
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/task', ['title' => 'Test task', 'description' => 'A description for my test task']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeInDatabase('tasks', ['title' => 'Test task']);
        $I->seeResponseContains('id');
    }

    public function createTaskWithNonExistentParent(ApiTester $I)
    {
        $I->wantTo("Create a new task with a parent task that does not exist");
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/task', ['title' => 'Test task', 'description' => 'A description for my test task', 'parentId' => 3]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContains('error');
        $I->seeResponseContains('Specified parent task does not exist');
    }

    public function createTaskWithParent(ApiTester $I)
    {
        $I->wantTo("Create a new task with a parent task");
        // Create the parent task
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/task', ['title' => 'Test parent task', 'description' => 'A description for my test parent task']);
        $I->seeResponseCodeIs(HttpCode::OK);
        // Get the new task ID
        $returnData = json_decode($I->grabResponse(), true);
        // Attempt to create the child task
        $I->sendPOST('/task', ['title' => 'Test child task', 'description' => 'A description for my test child task', 'parentId' => $returnData['id']]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeInDatabase('tasks', ['title' => 'Test child task', 'parentId' => $returnData['id']]);
        $I->seeResponseContains('id');
    }
}
