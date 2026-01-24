<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repository\ExampleRepository;

class ExampleController extends Controller
{

    private ExampleRepository $chat;

    public function __construct(ExampleRepository $chat)
    {
        $this->chat = $chat;
    }

    public function getMessages($userId)
    {
        return 200;
    }

 
} 