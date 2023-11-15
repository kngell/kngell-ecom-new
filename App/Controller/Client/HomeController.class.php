<?php

declare(strict_types=1);
class HomeController extends Controller
{
    use HomeControllerTrait;

    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }

    /**
     * IndexPage
     * ===================================================================.
     * @param array $data
     * @return void
     */
    protected function indexPage(array $data = []) : ResponseHandler
    {
        if (! empty($data)) {
            throw new InvalidArgumentException('Désolé page invalid', 404);
        }
        $params = [
            'customQuery' => null,
            'mainTable' => [
                'name' => 'visitors',
                'selectors' => ['aaaa', 'bbbb'],
            ],
            'joinRules' => [
                0 => [

                    'table' => 'table1',
                    'selectors' => [],
                    'on' => [
                        0 => [
                            'operator' => '=',
                            'tbl1' => 'taba',
                            'tbl2' => 'tblx',
                            'type' => 'expression',
                            'link' => ' AND ',
                            'braceOpen' => '(',
                            'braceClose' => '',
                            'rule' => ['a' => 'b'],
                        ],
                        1 => [
                            'operator' => '>=',
                            'tbl1' => 'tab1',
                            'tbl2' => 'tbl2',
                            'type' => 'expression',
                            'link' => ' OR ',
                            'braceOpen' => '',
                            'braceClose' => ')',
                            'rule' => ['c' => 'd'],

                        ],
                        2 => [
                            'operator' => '<',
                            'tbl1' => 'tabq',
                            'tbl2' => '',
                            'type' => 'value',
                            'link' => '',
                            'braceOpen' => '',
                            'braceClose' => '',
                            'rule' => ['x' => 140],
                        ],
                    ],
                    'relation' => 'left',
                ],
                1 => [

                    'table' => 'table2',
                    'selectors' => [],
                    'on' => [
                        0 => [
                            'operator' => '=',
                            'tbl1' => 'taba2',
                            'tbl2' => 'tblx2',
                            'type' => 'expression',
                            'link' => ' OR ',
                            'braceOpen' => '',
                            'braceClose' => '',
                            'rule' => ['a' => 'b'],
                        ],
                        1 => [
                            'operator' => '>=',
                            'tbl1' => 'taba',
                            'tbl2' => 'tblx',
                            'type' => 'expression',
                            'link' => '',
                            'braceOpen' => '',
                            'braceClose' => '',
                            'rule' => ['c' => 'd'],
                        ],
                    ],
                    'relation' => 'right',
                ],
            ],
            'conditions' => [
                0 => [
                    'operator' => '<',
                    'tbl1' => 'tabq',
                    'tbl2' => '',
                    'type' => 'value',
                    'link' => ' OR ',
                    'braceOpen' => '(',
                    'braceClose' => '',
                    'rule' => ['x' => 140],
                ],
                1 => [
                    'operator' => '=',
                    'tbl1' => 'tabq',
                    'tbl2' => 'tbv',
                    'type' => 'expression',
                    'link' => '',
                    'braceOpen' => '',
                    'braceClose' => ')',
                    'rule' => ['p' => 'q'],
                ],
            ],
            'groupBy' => [
                0 => [
                    'tbl' => 'tbl1',
                    'fields' => ['f1', 'f2'],
                ],
                2 => [
                    'tbl' => 'tblm',
                    'fields' => ['p1', 'm2'],
                ],
            ],
            'havingConditions' => [
                0 => [
                    'operator' => '=',
                    'tbl1' => '',
                    'tbl2' => '',
                    'type' => 'value',
                    'link' => '',
                    'braceOpen' => '',
                    'braceClose' => ')',
                    'rule' => ['alias' => 1500],
                ],
            ],
            'orderBy' => [
                0 => ['AAA' => 'DESC'],
                1 => ['BBB' => 'ASC'],
            ],
            'limitAndOffet' => [
                'limit' => 50,
                'offset' => 4,
            ],
        ];
        $arrParams = new Collection($params);
        $query = (new QuerySelect($arrParams))->query();

        $this->pageTitle('Modile Phones - Best Aparels Online Store');
        $this->view()->addProperties(['name' => 'Home Page']);
        return $this->render('phones' . DS . 'index', $this->displayPhones(
            brand:2,
            cache: 'phones_products'
        ));
        return $this->response->setContent('<h1>here we are</h1>');
    }

    protected function vetementsPage(array $args = []) : void
    {
        $this->pageTitle('Clothing - Best Clothes ever seen');
        $this->view()->addProperties(['name' => 'Home Clothes Page']);
        $this->render('clothes' . DS . 'clothing', $this->displayClothes(brand: 3, cache: 'clothes_products'));
    }

    protected function boutiqueVetementsPage(array $args = []) : void
    {
        $this->pageTitle('Clothing - Shop Page');
        $this->view()->addProperties(['name' => 'Shop Page']);
        $this->render('clothes' . DS . 'shop', $this->displayClothesShop(brand: 3, cache: 'clothes_products'));
    }

    protected function todoPage(array $agrs = [])
    {
        $this->view()->addProperties(['name' => 'Todo List']);
        $this->render('todoList' . DS . 'todo');
    }

    /**
     * IndexPage
     * ===================================================================.
     * @param array $data
     * @return void
     */
    protected function libPage(array $data = []) : void
    {
        $this->setLayout('clothes');
        $this->pageTitle('Clothing - Best Aparels Online Store');
        $this->view()->addProperties(['name' => 'Home Page']);
        $this->render('home' . DS . 'lib');
    }
}