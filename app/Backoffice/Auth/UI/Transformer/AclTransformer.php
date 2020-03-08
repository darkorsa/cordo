<?php

namespace App\Backoffice\Auth\UI\Transformer;

use App\Backoffice\Auth\Application\Query\AclView;
use League\Fractal\TransformerAbstract;

class AclTransformer extends TransformerAbstract
{
    public function transform(AclView $acl)
    {
        return [
            'id'                    => $acl->id(),
            'id_user'               => $acl->userId(),
            'privileges'            => $acl->privileges(),
            'modified'              => $acl->updatedAt(),
            'links'                 => [],
        ];
    }
}
