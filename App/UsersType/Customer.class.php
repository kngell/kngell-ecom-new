<?php

declare(strict_types=1);

class Customer extends UsersManager
{
    protected string $_table = 'customer';

    public function get() : self
    {
        /* @var TransactionsManager */
        if (AuthManager::isUserLoggedIn()) {
            $user = AuthManager::currentUser();
            if ($user->count() == 1) {
                $this->_count = $user->count();
                /** @var UsersEntity */
                $user = $user->getEntity();
                $this->assign([
                    'userId' => $user->isInitialized('userId') ? $user->getUserId() : '',
                    'order_id' => Application::diGet(TransactionsManager::class)->getUniqueId('order_id', 'ORD-', '-0XKF', 6),
                    'customerId' => $user->isInitialized('customerId') ? $user->getCustomerId() : '',
                    'firstName' => $user->isInitialized('firstName') ? $user->getFirstName() : '',
                    'lastName' => $user->isInitialized('lastName') ? $user->getLastName() : '',
                    'phone' => $user->isInitialized('phone') ? $user->getPhone() : '',
                    'email' => $user->isInitialized('email') ? $user->getEmail() : '',
                    'address' => Application::diGet(AddressBookManager::class)->getUserAddress(),
                    'userCards' => $this->userCards($user),
                    'profile_image' => $user->isInitialized('profile_image') ? $user->getProfileImage() : '',
                ]);
            }
        }
        return $this;
    }

    public function count(): int
    {
        return isset($this->_count) ? $this->_count : 0;
    }

    private function userCards(UsersEntity $user)
    {
        if ($user->isInitialized('customerId')) {
            $customerId = $user->getCustomerId();
            /** @var PaymentGatewayInterface */
            $pmg = $this->container(PaymentGatewayInterface::class, [
                'params' => ['customerId' => $customerId],
            ])->create();
            return $pmg->retriveCustomer($customerId)->getCards();
        }

        return new stdClass();
    }
}
