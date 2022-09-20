<?php
class MicrositesController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = MicrositesModel::getAll();

        Request::setTitle('Microsites');
    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('title', 'Title', 'required|trim|min_length[0]|max_length[100]');

            if ($this->isValid()) {
                $data = array(
                    'title' => post('title'),
                    'ref'   => makeSlug(post('title')),
                    'time'  => time(),
                );

                $result   = Model::insert('microsites', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('redirect', false, url('panel', 'microsites', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Model::import('panel/vacancies/sectors');
        Model::import('panel/vacancies/locations');

        $this->view->sectors   = SectorsModel::getAll();
        $this->view->locations = LocationsModel::getAll();

        Request::setTitle('Add Microsite');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = MicrositesModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/microsites'));

        if ($this->startValidation()) {
            $this->validatePost('title',        'Title',            'required|trim|min_length[0]|max_length[100]');
            $this->validatePost('ref',          'Ref',              'required|trim|min_length[0]|max_length[50]');
            $this->validatePost('logo_image',   'Logo',             'required');
            $this->validatePost('header_image', 'Landing Image',    'required');
            $this->validatePost('website',      'Website URL',      'trim|min_length[0]');
            $this->validatePost('company_size', 'Company Size',     'required|trim|min[1]');
            $this->validatePost('content',      'Overview',         'required|trim|min_length[1]');
            $this->validatePost('meta_title',   'Meta Title',       'trim|min_length[0]|max_length[200]');
            $this->validatePost('meta_keywords','Meta Keywords',    'trim|min_length[0]|max_length[200]');
            $this->validatePost('meta_desc',    'Meta Description', 'trim|min_length[0]|max_length[200]');
            //$this->validatePost('slug',         'Slug',             'required|trim|min_length[1]|max_length[100]');

            if ($this->isValid()) {
                $data = array(
                    'title'                 => post('title'),
                    'ref'                   => post('ref'),
                    'website'               => post('website'),
                    'company_size'          => post('company_size'),
                    'content'               => post('content'),
                    'meta_title'            => post('meta_title'),
                    'meta_keywords'         => post('meta_keywords'),
                    'meta_desc'             => post('meta_desc'),
                    'logo_image'            => post('logo_image'),
                    'header_image'          => post('header_image'),
                    'key_image'             => post('key_image'),
                    'overview_image'        => post('overview_image'),
                    'og_image'              => post('og_image'),
                );


                // Copy and remove logo_image
                if ($this->view->edit->logo_image !== $data['logo_image']) {
                    if (File::copy('data/tmp/' . $data['logo_image'], 'data/microsites/' . $data['logo_image'])) {
                        File::remove('data/microsites/' . $this->view->edit->logo_image);
                    } else
                        print_data(error_get_last());
                }

                // Copy and remove header_image
                if ($this->view->edit->header_image !== $data['header_image']) {
                    if (File::copy('data/tmp/' . $data['header_image'], 'data/microsites/' . $data['header_image'])) {
                        File::remove('data/microsites/' . $this->view->edit->header_image);
                    } else
                        print_data(error_get_last());
                }

                // Copy and remove key_image
                if ($this->view->edit->key_image !== $data['key_image']) {
                    if (File::copy('data/tmp/' . $data['key_image'], 'data/microsites/' . $data['key_image'])) {
                        File::remove('data/microsites/' . $this->view->edit->key_image);
                    } else
                        print_data(error_get_last());
                }

                // Copy and remove overview_image
                if ($this->view->edit->overview_image !== $data['overview_image']) {
                    if (File::copy('data/tmp/' . $data['overview_image'], 'data/microsites/' . $data['overview_image'])) {
                        File::remove('data/microsites/' . $this->view->edit->overview_image);
                    } else
                        print_data(error_get_last());
                }

                // Copy and remove og_image
                if ($this->view->edit->og_image !== $data['og_image']) {
                    if (File::copy('data/tmp/' . $data['og_image'], 'data/microsites/' . $data['og_image'])) {
                        File::remove('data/microsites/' . $this->view->edit->og_image);
                    } else
                        print_data(error_get_last());
                }


                $result = Model::update('microsites', $data, "`id` = '$id' LIMIT 1"); // Update row

                if ($result) {
                    // Remove and after insert tag sectors
                    Model::delete('microsites_tag_sectors', "`microsite_id` = '$id'");
                    if (is_array(post('tag_sector_ids')) && count(post('tag_sector_ids')) > 0) {
                        foreach (post('tag_sector_ids') as $sector_id) {
                            Model::insert('microsites_tag_sectors', array(
                                'microsite_id' => $id,
                                'sector_id' => $sector_id
                            ));
                        }
                    }

                    // Remove and after insert sectors
                    Model::delete('microsites_sectors', "`microsite_id` = '$id'");
                    if (is_array(post('sector_ids')) && count(post('sector_ids')) > 0) {
                        foreach (post('sector_ids') as $sector_id) {
                            Model::insert('microsites_sectors', array(
                                'microsite_id' => $id,
                                'sector_id' => $sector_id
                            ));
                        }
                    }

                    // Remove and after insert locations
                    Model::delete('microsites_locations', "`microsite_id` = '$id'");
                    if (is_array(post('location_ids')) && count(post('location_ids')) > 0) {
                        foreach (post('location_ids') as $location_id) {
                            Model::insert('microsites_locations', array(
                                'microsite_id' => $id,
                                'location_id' => $location_id
                            ));
                        }
                    }

                    // Remove and after insert vacancies
                    Model::delete('microsites_vacancies', "`microsite_id` = '$id'");
                    if (is_array(post('vacancy_ids')) && count(post('vacancy_ids')) > 0) {
                        foreach (post('vacancy_ids') as $vacancy_id) {
                            Model::insert('microsites_vacancies', array(
                                'microsite_id' => $id,
                                'vacancy_id' => $vacancy_id
                            ));
                        }
                    }

                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                    Request::endAjax();
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }


        Model::import('panel/microsites/tag_sectors');
        Model::import('panel/vacancies/sectors');
        Model::import('panel/vacancies/locations');
        Model::import('panel/vacancies');

        $this->view->tag_sectors = Tag_sectorsModel::getAll();
        $this->view->sectors     = SectorsModel::getAll();
        $this->view->locations   = LocationsModel::getAll();
        $this->view->vacancies   = VacanciesModel::getAll();//$id

        Request::setTitle('Edit Microsite');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $userID = intval(Request::getUri(0));
        $user = MicrositesModel::get($userID);

        if (!$user)
            Request::returnError('Vacancy error');

        $data['deleted'] = 'yes';
        $result = Model::update('microsites', $data, "`id` = '$userID'"); // Update row

        if ($result) {
            Request::addResponse('func', 'noticeSuccess', 'Removed');
            Request::addResponse('remove', '#item_' . $userID);
            Request::endAjax();
        } else {
            Request::returnError('Database error');
        }
    }

    public function archiveAction()
    {
        $this->view->list = MicrositesModel::getArchived();

        Request::setTitle('Archive Microsites');
    }

    public function resumeAction()
    {
        $id = (Request::getUri(0));
        $user = MicrositesModel::get($id);

        if (!$user)
            redirect(url('panel/microsites/archive'));

        $result = Model::update('microsites', ['deleted' => 'no'], "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'restore', 'entity' => 'microsite#' . $id, 'time' => time()]);
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/microsites/archive'));

    }

    public function vacancy_popupAction()
    {
        Request::ajaxPart();
        $this->view->microsite = MicrositesModel::get(post('microsite_id'));

        if (!$this->view->microsite)
            redirect(url('panel/microsites'));

        $this->view->jobs = Model::fetchAll(Model::select('vacancies', " `deleted` = 'no' AND `posted` = 'yes'  
        AND (`time_expire` > '" . (time() - 180) . "' OR `time_expire` = 0) AND (`microsite_id` = 0 OR `microsite_id` = '" . $this->view->microsite->id . "')"));

        Request::addResponse('html', '#popup', $this->getView());
    }

    public function update_microsite_vacancyAction()
    {
        Request::ajaxPart();

        $microsite_id = post('microsite_id');
        $jobs = post('jobs');

        $microsite = MicrositesModel::get($microsite_id);
        if (!$microsite)
            redirect(url('panel/microsites'));

        if (is_array($jobs) && count($jobs) > 0) {

            Model::update('vacancies', ['microsite_id' => 0], " `microsite_id` = $microsite_id");

            Model::update('vacancies', ['microsite_id' => $microsite_id], " `id` IN (" . implode(', ', $jobs) . ")");
        }

        Request::endAjax();

    }

    // Statistic

    public function statisticAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->microsite = MicrositesModel::get($id);

        if (!$this->view->microsite)
            redirect(url('panel/microsite'));

        $this->view->list = MicrositesModel::getViewsByDays($this->view->microsite->id);
        $this->view->referrals = MicrositesModel::getReferrersList($this->view->microsite->id);
        $this->view->views = MicrositesModel::getViews($this->view->microsite->id);

        // Last 9 days empty array
        $this->view->data = [];
        for ($i = time() - 9 * 24 * 3600; $i <= time(); $i += 24 * 3600)
            $this->view->data[date("d.m", $i)] = 0;

        // Count the number of entities every day
        foreach ($this->view->list as $value)
            $this->view->data[date("d.m", $value->time)]++;

        // Count refs
        $this->view->refArray = [];
        foreach ($this->view->views as $v)
            $this->view->refArray[$v->ref]++;

        $this->view->count = array_sum($this->view->data);

        Request::setTitle('Statistic Microsite');
    }

    public function add_refAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->microsite = MicrositesModel::get($id);

        if (!$this->view->microsite)
            redirect(url('panel/microsites'));

        if ($this->startValidation()) {
            $this->validatePost('title',            'Referrer Title',           'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'microsite_id'   => $this->view->microsite->id,
                    'title'     => makeSlug(post('title')),
                );

                $result   = Model::insert('microsites_referrers', $data); // Insert row
                $insertID = Model::insertID();


                if (!$result && $insertID) {
                    Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'add', 'entity' => 'microsite#' . $insertID, 'time' => time()]);

                    Request::addResponse('redirect', false, url('panel', 'microsites', 'statistic', $this->view->microsite->id));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Microsite Referrer');
    }

    public function delete_refAction()
    {
        $id = (Request::getUri(0));
        $ref = MicrositesModel::getReferrer($id);

        if (!$ref)
            redirect(url('panel/microsites'));

        $result = Model::delete('microsites_referrers',"`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'delete', 'entity' => 'microsite-referral#' . $id, 'time' => time()]);

        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/microsites/statistic/' . $ref->microsite_id));
    }
}
/* End of file */
