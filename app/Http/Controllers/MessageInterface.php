<?php

namespace App\Http\Controllers;

interface MessageInterface {
    public function send(array $data);
}
