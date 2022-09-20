<?php

class MicrositeController extends Controller
{
    public function indexAction()
    {
        $ref = Request::getUri(0);
        $microsite = MicrositeModel::getMicrosite($ref);

        Model::import('panel/microsites');
        $this->view->microsite = MicrositesModel::get($microsite->id);

        if (!$this->view->microsite)
            redirect(url('/'));

        Model::import('panel/vacancies');
        $this->view->vacancies = VacanciesModel::getAll($microsite->id); // $microsite->id

        Model::import('panel/vacancies/tech_stack');
        $this->view->tech_list = Tech_stackModel::getArrayWithID();

        Model::import('panel/microsites/testimonials');
        $this->view->testimonials = TestimonialsModel::getAll($microsite->id);

        Model::import('panel/microsites/photos');
        $this->view->photos = PhotosModel::getAll($microsite->id);

        Model::import('panel/microsites/videos');
        $this->view->videos = VideosModel::getAll($microsite->id);

        Model::import('panel/microsites/offices');
        $this->view->offices = OfficesModel::getAll($microsite->id);

        // API key for maps
        $this->view->maps_api_key = SettingsModel::get('maps_api_key');

        // Set stat
        if (setViewStat('microsite_', $this->view->microsite->id, 'microsites_analytics')) {
            $data_views['views'] = '++';
            Model::update('microsites', $data_views, "`id` = '" . $this->view->microsite->id . "'");
        }

        Request::setTitle('Microsite - ' . ($this->view->microsite->meta_title ?: $this->view->microsite->title));
        Request::setKeywords($this->view->microsite->meta_keywords);
        Request::setDescription($this->view->microsite->meta_desc);
        Request::setImageOG('app/data/microsites/' . $this->view->microsite->og_image);
    }
}
/* End of file */
