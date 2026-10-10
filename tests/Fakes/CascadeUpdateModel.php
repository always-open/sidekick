<?php

namespace AlwaysOpen\Sidekick\Tests\Fakes;

use AlwaysOpen\Sidekick\Models\Traits\CascadeUpdate;
use Illuminate\Database\Eloquent\Model;

class CascadeUpdateModel extends Model
{
    use CascadeUpdate;
}
