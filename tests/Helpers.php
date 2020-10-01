<?php

namespace Tests;

use Illuminate\Database\Eloquent\ModelNotFoundException;

function authorFromException(ModelNotFoundException $e)
{
    return $e->getTrace()[1]['args'][0];
}
