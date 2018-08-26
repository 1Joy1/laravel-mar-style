<?php

namespace App\Http\Controllers;

use App\Group;
use App\Message;
use \App\Exceptions\RequiredDataException;

class MainPageController extends Controller
{
    /**
     * Display main page
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws RequiredDataException
     */
    public function index()
    {
        $messages = Message::all();

        $groups = Group::all();

        if (!$groups->count()) {
            throw new RequiredDataException('For the application to function correctly, the Group table must not be empty.');
        }

        foreach ($groups as $group) {
            $portfol[$group->name] = ['img'=>$group->photo_src, 'name'=>$group->display_name];
        }


        return view('main', ['menu' => [
                                    ['id'=>'menu-main', 'href'=>'#main', 'name'=>'Главная'],
                                    ['id'=>'menu-portfol', 'href'=>'#portfolio', 'name'=>'Портфолио'],
                                    ['id'=>'menu-service', 'href'=>'#services', 'name'=>'Услуги'],
                                    ['id'=>'menu-feedback', 'href'=>'#feadback', 'name'=>'Отзывы'],
                                    ['id'=>'menu-link', 'href'=>'#link', 'name'=>'Ссылки'],
                                ],
                             'portfol' => $portfol,
                             'messages'=> $messages,
                            ]);
    }
}