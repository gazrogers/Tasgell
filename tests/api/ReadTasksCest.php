<?php 

use Codeception\Util\HttpCode;

class ReadTasksCest
{
    public function _before(ApiTester $I)
    {
        $I->haveInDatabase('tasks', ['id' => 11, 'title' => 'Test task 1', 'description' => 'Test task description 1', 'parentId' => null]);
        $I->haveInDatabase('tasks', ['id' => 13, 'title' => 'Test task 2', 'description' => 'Test task description 2', 'parentId' => null]);
        $I->haveInDatabase('tasks', ['id' => 15, 'title' => 'Test task 3', 'description' => 'Test task description 3', 'parentId' => null]);
        $I->haveInDatabase('tasks', ['id' => 16, 'title' => 'Test task 4', 'description' => 'Test task description 4', 'parentId' => null]);
        $I->haveInDatabase('tasks', ['id' => 17, 'title' => 'Test task 5', 'description' => 'Test task description 5', 'parentId' => null]);
    }

    public function getTask(ApiTester $I)
    {
        $I->wantTo("Fetch task information");
        $I->sendGET('/task/11/13/15/17/19');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"id":"11"');
        $I->seeResponseContains('"id":"13"');
        $I->seeResponseContains('"id":"15"');
        $I->seeResponseContains('"id":"17"');
        $I->dontSeeResponseContains('"id":"19"');
    }

    public function getTaskNoMatch(ApiTester $I)
    {
        $I->wantTo("Fetch task information");
        $I->sendGET('/task/21/23/25/27/29');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseEquals('[]');
    }

    public function getTaskIgnoreNonNumeric(ApiTester $I)
    {
        $I->wantTo("Fetch task information");
        $I->sendGET('/task/11/invalid/17/19');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"id":"11"');
        $I->seeResponseContains('"id":"17"');
        $I->dontSeeResponseContains('"id":"13"');
        $I->dontSeeResponseContains('"id":"15"');
        $I->dontSeeResponseContains('"id":"19"');
    }
}
