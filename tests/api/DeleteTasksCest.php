<?php 

use Codeception\Util\HttpCode;

class DeleteTasksCest
{
    public function _before(ApiTester $I)
    {
        $I->haveInDatabase('tasks', ['id' => 10, 'title' => 'Test task', 'description' => 'Test task description', 'parentId' => null]);
        $I->haveInDatabase('tasks', ['id' => 11, 'title' => 'Test task', 'description' => 'Test task description', 'parentId' => 10]);
    }

    public function deleteTask(ApiTester $I)
    {
        $I->wantTo("Delete a task");
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendDELETE('/task/11');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseEquals('');
        $I->dontSeeInDatabase('tasks', ['id' => 11, 'parentId' => 10]);
        $I->seeInDatabase('tasks', ['id' => 11, 'parentId' => null]);
    }

    public function deleteNonExistentTask(ApiTester $I)
    {
        $I->wantTo("Delete a task that does not exist");
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendDELETE('/task/12');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseContains('error');
        $I->seeResponseContains('The task to delete does not exist');
        $I->dontSeeInDatabase('tasks', ['id' => 12]);
    }
}
