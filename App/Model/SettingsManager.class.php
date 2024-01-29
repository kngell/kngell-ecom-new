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
        if (! isset($this->queryParams)) {
            $this->queryParams = Application::diGet(QueryParamsInterface::class);
        }
        $q = $this->query()->select('visitors|v', ['v|aaaa', 'v|bbbb'], 'v|uuuu')
            ->where('abc|parti', 'ppaci|ppat1507')
            ->where('key1', 'val1|exp', ['key2!aph' => 'syntec'], ['key3', 012])
            ->orWhere(function ($q) {
                $q->where('chat_msg_from', '=', '$to_id')
                    ->where('chat_msg_to', '=', 'auth()->user()->id')
                    ->orwhere(function ($q) {
                        $q->whereNotIn('next', ['Kalala', '00123pp'])
                            ->where(['beef' => 'soja']);
                    });
            })
            ->orWhere('keyx', 1500, ['keyexxtra', 'valueextra'])
            ->leftJoin('table1|t1')
            ->on('v|jont1', 1850, ['t1|join3', 't2|join4'])
            ->join('table2|t2', 't2|HSF')
            ->on('v|jont6', 't1|join7', ['t1|join3', 't2|join4'])
            ->groupBy('tbl1|f1', 'tbl1|f2', 'vvvv', ['tblm1' => 'p1'], ['tblm2' => 'p2'])
            ->having(function ($q) {
                $q->having('tb1|field0', 150, )
                    ->havingNotIn('feildppp', ['qsdf', 'azer', '250']);
            })
            ->orderBy('AAA', 'DESC', ['tbl|BBB', 'ASC'])
            ->limit(50)
            ->offset(4)
            ->return('object');
        // $this->query()->select()->from()->table();
        // return $this->query()->select();
        return $q;
    }

    public function testEntityManager()
    {
        $em = $this->getEntityManager();
        return $em->find($this->entity->getId());
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
        return $this->query()->insert(
            null,
            ['azer' => 142, 'azezez' => 523, 'dfsfsdf' => 'pml', 'pmko' => 'pou'],
            ['azer' => 4, 'azezez' => 5, 'dfsfsdf' => 'ppp', 'pmko' => 'mmm']
        )->exec();
    }

    // protected function query()
    // {
    //     $this->qParams->setBaseOptions($this->_table);
    //     return $this->qParams->query();
    // }
}
