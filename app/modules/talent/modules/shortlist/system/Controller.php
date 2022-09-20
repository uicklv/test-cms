<?php

class ShortlistController extends Controller
{
    use Validator;

    public function indexAction()
    {
        $id = Request::getUri(0);

        Model::import('panel/talents/shortlists');
        $this->view->list = ShortlistsModel::get($id);
        if (!$this->view->list)
            redirect(url('/'));

        // module protection
        $acess = getSession('acess');
        $protection = Model::fetch(Model::select('talent_password_protection'));
        $areas = explode('||', trim($protection->areas, '|'));

        if (in_array('shortlist', $areas) && $acess !== 'yes')
            redirectAny(url("talent/protection?url=talent/shortlist/" . $this->view->list->id));

        //ger open profiles
        if (is_array($this->view->list->opens_ids) && count($this->view->list->opens_ids) > 0) {
            Model::import('panel/talents/open_profiles');
            $this->view->open_profiles = [];
            foreach ($this->view->list->opens_ids as $item) {
                $this->view->open_profiles[] = Open_profilesModel::get($item);
            }
        }

        Request::setTitle('Short-list - ' . $this->view->list->name);
    }

}
/* End of file */