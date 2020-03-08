<?php

namespace App\Backoffice\Users\UI\Transformer;

use League\Fractal\TransformerAbstract;
use App\Backoffice\Users\Application\Query\UserView;

class UserTransformer extends TransformerAbstract
{
    public function transform(UserView $user)
    {
        return [
            'id'                    => $user->id(),
            'email'                 => $user->email(),
            'modified'              => $user->updatedAt(),
            'links'                 => [],
        ];
    }
}
