<?php

declare(strict_types=1);

abstract class AbstractAdminHomePage
{
    use DisplayTraits;

    protected ?UsersEntity $userEntity;
    protected CollectionInterface $paths;

    public function __construct(?UsersEntity $userEntity, AdminHomePagePaths $paths)
    {
        $this->userEntity = $userEntity;
        $this->paths = $paths->Paths();
    }
}