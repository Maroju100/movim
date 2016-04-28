<?php

use Respect\Validation\Validator;
use Modl\PostnDAO;
use Modl\ContactDAO;

class Search extends \Movim\Widget\Base
{
    function load()
    {
        $this->addjs('search.js');
    }

    function ajaxRequest()
    {
        $view = $this->tpl();
        $view->assign('empty', $this->ajaxSearch(''));
        Dialog::fill($view->draw('_search', true));
        RPC::call('Search.init');
    }

    function ajaxSearch($key)
    {
        $view = $this->tpl();

        $validate_subject = Validator::stringType()->length(1, 15);
        if(!$validate_subject->validate($key)) {
            $view->assign('empty', true);
        } else {
            $view->assign('empty', false);

            $pd = new PostnDAO;
            $posts = $pd->search($key);
            $view->assign('posts', $posts);

            $cd = new ContactDAO;
            $contacts = $cd->search($key);
            $view->assign('contacts', $contacts);

            if(!$posts && !$contacts) $view->assign('empty', true);
        }

        RPC::call('MovimTpl.fill', '#results', $view->draw('_search_results', true));
    }

    function display()
    {
    }
}