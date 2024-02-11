<?php

namespace GB\HomeTask\Http\Actions;

use GB\HomeTask\Http\Request;
use GB\HomeTask\Http\Response;

interface ActionInterface
{

    public function handle(Request $request): Response;

}
