<?php

namespace TypiCMS\Modules\Users\Composers;

use Illuminate\Contracts\View\View;
use Maatwebsite\Sidebar\SidebarGroup;
use Maatwebsite\Sidebar\SidebarItem;
use TypiCMS\Modules\Core\Composers\BaseSidebarViewComposer;

class SidebarViewComposer extends BaseSidebarViewComposer
{
    public function compose(View $view)
    {
        $view->sidebar->group(trans('global.menus.users'), function (SidebarGroup $group) {
            $group->id = 'users';
            $group->weight = 50;
            $group->addItem(trans('users::global.name'), function (SidebarItem $item) {
                $item->icon = config('typicms.users.sidebar.icon', 'icon fa fa-fw fa-user');
                $item->weight = config('typicms.users.sidebar.weight');
                $item->route('admin.users.index');
                $item->append('admin.users.create');
                $item->authorize(
                    $this->auth->hasAccess('users.index')
                );
            });
        });
    }
}
