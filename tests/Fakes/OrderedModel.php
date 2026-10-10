<?php

namespace AlwaysOpen\Sidekick\Tests\Fakes;

use AlwaysOpen\Sidekick\Models\Traits\Ordered;
use Illuminate\Database\Eloquent\Model;

class OrderedModel extends Model
{
    use Ordered;
}
