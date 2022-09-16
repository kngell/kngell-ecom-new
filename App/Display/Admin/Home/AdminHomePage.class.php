<?php

declare(strict_types=1);

class AdminHomePage extends AbstractAdminHomePage implements DisplayPagesInterface
{
    public function __construct(AdminHomePagePaths $paths)
    {
        if (AuthManager::isUserLoggedIn()) {
            $userEntity = AuthManager::currentUser()->getEntity();
        }
        parent::__construct($userEntity ?? null, $paths);
    }

     public function displayAll(): mixed
     {
         return [
             'admin_user' => $this->getAdminUserName(),
         ];
     }

     private function getAdminUserName() : string
     {
         $template = '';
         if ($this->userEntity !== null) {
             $template = $this->getTemplate('userNamePath');
             $template = str_replace('{{admin_user_name}}', 'Bonjour&nbsp;,' . $this->userEntity->getFirstName(), $template);
         }
         return $template;
     }
}