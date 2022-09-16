<?php

declare(strict_types=1);

abstract class AbstractCheckout
{
    use DisplayTraits;
    use DisplayFormElementTrait;
    use CheckoutTrait;
    use CheckoutFromTrait;

    protected ?CollectionInterface $userCart;
    protected ?CollectionInterface $shippingClass;
    protected ?CollectionInterface $paths;
    protected ?FormBuilder $frm;
    protected ?CollectionInterface $pmtMode;
    protected ?CustomerEntity $customerEntity;
    protected ?ButtonsGroup $btns;
    protected ?CartSummary $cartSummary;
    protected ?ResponseHandler $response;
    protected ?AddressBookPage $addressBook;
    protected MoneyManager $money;
    protected ?CreditCardPage $creditCard;
    protected ?Modals $modals;
    protected string $stripKey = STRIPE_KEY_PUBLIC;

    public function __construct(?CollectionInterface $userCart, ?CollectionInterface $shippingClass, ?CollectionInterface $pmtMode, ?FormBuilder $frm, ?CollectionInterface $paths, ?CreditCardPage $creditCard = null, ?Modals $modals = null, ?customerEntity $customerEntity = null)
    {
        $this->customerEntity = $customerEntity;
        $this->userCart = $userCart;
        $this->shippingClass = $shippingClass;
        $this->pmtMode = $pmtMode;
        $this->frm = $frm;
        $this->paths = $paths;
        $this->btns = new ButtonsGroup($this->frm);
        $this->addressBook = Container::getInstance()->make(AddressBookPage::class, [
            'customerEntity' => $this->customerEntity,
        ]);
        $this->money = MoneyManager::getInstance();
        $this->response = Container::getInstance()->make(ResponseHandler::class);
        $this->cartSummary = (new CartSummary($userCart, $this->shippingClass, $this->money, $this->paths));
        $this->creditCard = $creditCard;
        $this->modals = $modals;
    }

    protected function creditCardContent() : string
    {
        $template = $this->creditCard->creditCard(); //$this->getTemplate('creditCardTemplatePath');
        $template = str_replace('{{stripeKey}}', $this->stripKey, $template);
        $template = str_replace('{{cc_image}}', ImageManager::asset_img('visa.png'), $template);
        $template = str_replace('{{cardHolder}}', $this->frm->input([
            TextType::class => ['name' => 'card_holder', 'class' => ['card_holder']],
        ])->id('card_holder')->Label('Card Holder:')->placeholder(' ')->html(), $template);

        return $template;
    }

    protected function params() : array
    {
        return [
            'userCart' => $this->userCart,
            'shippingClass' => $this->shippingClass,
            'pmtMode' => $this->pmtMode,
            'frm' => $this->frm,
            'paths' => $this->paths,
            'customerEntity' => $this->customerEntity,
            'btns' => $this->btns,
            'summary' => $this->cartSummary,
            'addressBook' => $this->addressBook,
            'money' => $this->money,
            'response' => $this->response,
        ];
    }

    protected function addressBookContent(string $frmID = '') : string
    {
        list($htmlChk, $htmlModal, $text) = $this->addressBook->setCustomer($this->customerEntity)->all($frmID);

        return $htmlModal;
    }

    protected function userCheckoutSesstion() : void
    {
        if (AuthManager::isUserLoggedIn()) {
            /** @var SessionInterface */
            $session = Container::getInstance()->make(SessionInterface::class);
            $this->customerEntity->setBillTo($this->getCustomerAddress($this->customerEntity, 'bill'));
            $this->customerEntity->setShipTo($this->getCustomerAddress($this->customerEntity, 'ship'));
            $this->customerEntity->setShippingMethod($this->shippingMethod($this->shippingClass));
            $this->customerEntity->setCartSummary($this->sessionCart($this->cartSummary));
            $this->customerEntity->setPromo('');
            $session->set(CHECKOUT_PROCESS_NAME, serialize($this->customerEntity));
        }
    }

    private function customerEntity() : ?CustomerEntity
    {
        $session = Container::getInstance()->make(SessionInterface::class);

        return !$session->exists(CHECKOUT_PROCESS_NAME) ? $this->customerEntity : unserialize($session->get(CHECKOUT_PROCESS_NAME));
    }

    private function getCustomerAddress(?CustomerEntity $en = null, ?string $type = null) : string
    {
        if (!is_null($en) && $en->isInitialized('address')) {
            /** @var CollectionInterface */
            $addresses = $en->{$en->getGetters('address')}();
            $customerAddress = $addresses->filter(function ($addr) use ($type) {
                if ($type != null) {
                    if ($type == 'ship') {
                        $addr->principale == 'Y' ? $addr->billing_addr = 'Y' : '';

                        return $addr->principale == 'Y';
                    }
                    if ($type == 'bill') {
                        return $addr->billing_addr == 'Y';
                    }
                } else {
                    throw new BaseException('Addresse type not spécified!');
                }
            });
            if ($customerAddress->count() === 1) {
                $get = $type == 'ship' ? 'delivery' : 'billing';
                list($htmlFrm, $htmlModal, $text) = $this->addressBook->$get();

                return $text;
            }
            if ($customerAddress->count() === 0 && $type == 'bill') {
                return $this->getCustomerAddress($en, 'ship');
            }
        }

        return '';
    }
}
