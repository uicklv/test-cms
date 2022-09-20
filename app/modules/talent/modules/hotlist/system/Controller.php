<?php

class HotlistController extends Controller
{
    use Validator;

    public function indexAction()
    {
        $id = Request::getUri(0);

        Model::import('panel/talents/hotlists');
        $this->view->list = HotlistsModel::get($id);
        if (!$this->view->list)
            redirect(url('/'));

        // module protection
        $acess = getSession('acess');
        $protection = Model::fetch(Model::select('talent_password_protection'));
        $areas = explode('||', trim($protection->areas, '|'));

        if (in_array('hotlists', $areas) && $acess !== 'yes')
            redirectAny(url("talent/protection?url=talent/hotlist/" . $this->view->list->id));

        //ger open profiles
        if (is_array($this->view->list->opens_ids) && count($this->view->list->opens_ids) > 0) {
            Model::import('panel/talents/open_profiles');
            $this->view->open_profiles = [];
            foreach ($this->view->list->opens_ids as $item) {
                $this->view->open_profiles[] = Open_profilesModel::get($item);
            }
        }

        //get anonymous profiles
        if (is_array($this->view->list->anonymous_ids) && count($this->view->list->anonymous_ids) > 0) {
            Model::import('panel/talents/anonymous_profiles');
            $this->view->anonymous_profiles = [];
            foreach ($this->view->list->anonymous_ids as $item) {
                $this->view->anonymous_profiles[] = Anonymous_profilesModel::get($item);
            }
        }

        Request::setTitle('Hotlist - ' . $this->view->list->name);
    }

}
/* End of file */