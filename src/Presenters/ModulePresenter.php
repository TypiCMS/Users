<?php

namespace TypiCMS\Modules\Users\Presenters;

use TypiCMS\Modules\Core\Presenters\Presenter;

class ModulePresenter extends Presenter
{
    /**
     * check if user is activated.
     *
     * @return string translated 'yes' or 'no'
     */
    public function activated()
    {
        return $this->entity->activated ? trans('global.Yes') : trans('global.No');
    }

    /**
     * Is user superuser ?
     *
     * @return string translated 'yes' or 'no'
     */
    public function superUser()
    {
        return $this->entity->superuser ? trans('global.Yes') : trans('global.No');
    }

    /**
     * Get title by concatenating first_name and last_name.
     *
     * @return string
     */
    public function title()
    {
        return $this->entity->first_name.' '.$this->entity->last_name;
    }
}
