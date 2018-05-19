<?php

namespace Moxl\Xec\Action\Roster;

use Moxl\Xec\Action;
use Moxl\Stanza\Roster;
use App\Roster as DBRoster;
use App\User as DBUser;

class GetList extends Action
{
    private $_from;

    public function request()
    {
        $this->store();
        Roster::get();
    }

    public function handle($stanza, $parent = false)
    {
        $rosters = [];

        foreach($stanza->query->item as $item) {
            $roster = new DBRoster;
            $roster->set($item);
            array_push($rosters, $roster->toArray());
        }

        DBRoster::where('session_id', SESSION_ID)->delete();
        DBRoster::saveMany($rosters);

        $this->deliver();
    }

    public function load($key) {}
    public function save() {}
}
