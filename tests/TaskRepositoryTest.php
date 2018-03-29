<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TaskRepositoryTest extends TestCase
{

    private $taskRepository;

    public function setUp() {
        parent::setUp();

        $this->taskRepository = new \App\Repositories\TaskRepository();
    }

    public function testSign(){
        
    }
}
