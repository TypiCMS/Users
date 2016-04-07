<?php

namespace TypiCMS\Modules\Users\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Sidebar\SidebarGroup;
use Maatwebsite\Sidebar\SidebarItem;

class SidebarViewComposer
{
    public function compose(View $view)
    {
        $view->sidebar->group(trans('global.menus.users'), function (SidebarGroup $group) {
            $group->id = 'users';
            $group->weight = 50;
            $group->addItem(trans('users::global.name'), function (SidebarItem $item) {
                $item->icon = config('typicms.users.sidebar.icon', 'icon fa fa-fw fa-user');
                $item->weight = config('typicms.users.sidebar.weight');
                $item->route('admin::index-users');
                $item->append('admin::create-user');
                $item->authorize(
                    Gate::allows('index-users')
                );
            });
        });
    }
}
