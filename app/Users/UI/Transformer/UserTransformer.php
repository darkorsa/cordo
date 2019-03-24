<?php

namespace App\Users\UI\Transformer;

use League\Fractal\TransformerAbstract;
use App\Users\Application\Query\UserView;

class UserTransformer extends TransformerAbstract
{
    public function transform(UserView $user)
    {
        return [
            'id'                    => $user->id(),
            'email'                 => $user->email(),
            'modified'              => $user->updatedAt() ?: $user->createdAt(),
            'links'                 => [],
        ];
    }
}
