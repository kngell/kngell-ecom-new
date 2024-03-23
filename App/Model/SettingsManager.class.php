<?php

declare(strict_types=1);
class SettingsManager extends Model
{
    protected string $_colID = 'setID';
    protected string $_table = 'settings';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function getSettings() : CollectionInterface
    {
        $this->query('select', null, ['setting_key', 'value'])->return('object');
        $settings = new Collection();
        foreach ($this->getAll()->get_results() as $setting) {
            $settings->offsetSet($setting->setting_key, $setting->value);
        }
        return $settings;
    }

    public function testNewQuery()
    {
        // if (! isset($this->queryParams)) {
        //     $this->queryParams = Application::diGet(QueryParams::class);
        // }
        // return $this->query()->delete()->from('adcb')->where('A', 'B')->go();
        // return $this->query()->delete()->where('A', 'B')->go();
        // return $this->query()->insert('r')->into('tab')->fields('aaa', 'bbb', 'ccc')->values(123, 4565, '1111', ['ert', 'azer', '111'])->go();
        // $en1 = $this->entity->assign([
        //     'setID' => 13,
        //     'settingName' => 'test',
        //     'settingKey' => '123',
        //     'settingDescr' => 'test entity',
        // ]);
        // $en2 = $this->entity->assign([
        //     'setID' => 11,
        //     'settingName' => 'test2',
        //     'settingKey' => '123test2',
        //     'settingDescr' => 'test entity test2',
        // ]);
        // $this->entity->assign([
        //     'setID' => 11,
        //     'settingName' => 'test2',
        //     'settingKey' => '123test2',
        //     'settingDescr' => 'test entity test2',
        // ]);
        // return $this->query()->delete()->where('settingKey', '123test2')->go();
        // return $this->query()->delete()->go();
        // return $this->query()->insert($en1)->go();
        // return $this->query()->insert(['itemId' => 11, 'cartId' => 12])->into('cart')->go();
        // return $this->query()->raw('Select * from settings')->go();
        // return $this->query()->updateWithCte($en1, $en2)->go();
        // return $this->query()->update()->set(['settingName' => 'values1', 'settingKey' => 'value2'])->where('azer', 12)->go();
        // return $this->query()->update(['settingName' => 'values1', 'settingKey' => 'value2'])->where('A', 'B')->go();
        // return $this->query()->update($this->entity)->where('azer', 12)->go();
        // return $this->query()->raw('SELECT * FROM TABLE')->where('azer', 12)->go();
        $q = $this->query()->select('t1|itemId', ['aaaa', 'v|bbbb'], 'v|uuuu')
            ->where('visitors|parti', 'cart|ppat1507')
            ->where('visitors|key1', 'cart|val1', ['key2!aph' => 'syntec'], ['key3', 012])
            ->orWhere(function ($qn) {
                $qn->where('chat_msg_from', '=', '$to_id')
                    ->where('chat_msg_to', '=', 'auth()->user()->id')
                    ->orwhere(function ($f) {
                        $f->whereNotIn('next', ['Kalala', '00123pp'])
                            ->where(['beef' => 'soja']);
                    });
            })
            ->orWhere('keyx', 1500, ['keyexxtra', 'valueextra'])
            ->leftJoin('cart|t1', ['feild1', 'field2'])
            ->on('cookies', '=', 'bbbb')
            ->join('visitors|t2')
            ->on('visitors|HSF', 'cart|acb')
            ->where(['cart|pout' => 50])
            ->groupBy('tbl1|f1', 'tbl1|f2', 'vvvv', ['tblm1' => 'p1'], ['tblm2' => 'p2'])
            ->having(function ($q) {
                $q->having('visitors|field0', 150, )
                    ->havingNotIn('feildppp', ['qsdf', 'azer', '250']);
            })
            ->orderBy('AAA', 'DESC', ['tbl|BBB', 'ASC'])
            ->limit(50)
            ->offset(4)
            ->return('object');
        return $q;
        // return $this->query()->select()->from()->table('address_book')->return('object');
        // return $this->query()->select()->return('object');
    }

    public function testEntityManager()
    {
        // $em = $this->getEntityManager();
        // return $em->find($this->entity->getId());
    }

    public function testInsert()
    {
        // return $this->query()
        //     ->insert()
        //     ->into($this->_table)
        //     ->fields(['azer', 'azezez', 'dfsfsdf', 'pmko'])
        //     ->values(142, 523, 'pml', 'pou')
        //     ->exec();

        // return $this->query('insert', $this->_table, ['azer' => 142, 'azezez' => 523, 'dfsfsdf' => 'pml', 'pmko' => 'pou'])->exec();
        // return $this->query()->insert(
        //     null,
        //     ['azer' => 142, 'azezez' => 523, 'dfsfsdf' => 'pml', 'pmko' => 'pou'],
        //     ['azer' => 4, 'azezez' => 5, 'dfsfsdf' => 'ppp', 'pmko' => 'mmm']
        // )->exec();
    }

    // protected function query()
    // {
    //     $this->qParams->setBaseOptions($this->_table);
    //     return $this->qParams->query();
    // }
}