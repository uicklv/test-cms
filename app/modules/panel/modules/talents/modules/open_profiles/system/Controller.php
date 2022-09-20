<?php
class Open_profilesController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = Open_profilesModel::getAll();

        Request::setTitle('Open Profiles');
    }

    public function archiveAction()
    {
        $this->view->list = Open_profilesModel::getArchived();

        Request::setTitle('Open Profiles');
    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('name', 'Name', 'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'candidate_name' => post('name'),
                    'time'           => time(),
                    'consultant_id'  => User::get('id')
                );

                $result   = Model::insert('talent_open_profiles', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
//                    $this->session->set_flashdata('success', 'Location created successfully.');
                    Request::addResponse('redirect', false, url('panel', 'talents', 'open_profiles', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax()) {
//                    Request::addResponse('func', 'noticeError', Request::returnErrors($this->validationErrors, true));
                    Request::returnErrors($this->validationErrors);
                }
            }
        }

        Request::setTitle('Add Profile');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->user = Open_profilesModel::get($id);

        if (!$this->view->user)
            redirect(url('panel/talents/open_profiles'));


        $this->view->locations = [];
        if (is_array($this->view->user->locations) && count($this->view->user->locations) > 0) {
            foreach ($this->view->user->locations as $item) {
                $this->view->locations[] = $item->location_name;
            }
        }

        $this->view->languages = [];
        if (is_array($this->view->user->languages) && count($this->view->user->languages) > 0) {
            foreach ($this->view->user->languages as $item) {
                $this->view->languages[] = $item->language_name;
            }
        }


        if ($this->view->user->keywords)
            $this->view->keywords = explode(',', $this->view->user->keywords);

        if ($this->startValidation()) {
            $this->validatePost('candidate_name',     'Name',                      'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('job_title',          'Job Title',                 'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('ref',                'Reference Code',            'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('quote',              'Quote about the Candidate', 'required|trim|min_length[1]');
            $this->validatePost('postcode',           'Postcode/Zip Code',         'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('radius',             'Relocate Radius',           'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('distance_type',      'Radius Distance Type',      'required|trim|min_length[1]|max_length[5]');
            $this->validatePost('relocate',           'Relocate',                  'required|trim|min_length[1]|max_length[3]');
            $this->validatePost('contract',           'Contract',                  'required|trim|min_length[1]|max_length[10]');
            $this->validatePost('availability',       'Availability',              'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('annual_currency',    'Min Annual Salary',         'required|trim');
            $this->validatePost('daily_currency',     'Daily Currency',            'required|trim');
            $this->validatePost('resume_info',        'CV / Resume Info',          'required|trim|min_length[1]');
            $this->validatePost('keywords',           'Skills',                    'trim|min_length[0]|max_length[200]');
            $this->validatePost('education',          'Education',                 'trim|min_length[0]|max_length[200]');
            $this->validatePost('video_type',         'Video Type',                'required|min_length[0]|max_length[20]');

            if ($this->isValid()) {

                $data = array(
                    'candidate_name'      => post('candidate_name'),
                    'job_title'           => post('job_title'),
                    'ref'                 => post('ref'),
                    'quote'               => post('quote'),
                    'postcode'            => post('postcode'),
                    'radius'              => post('radius'),
                    'distance_type'       => post('distance_type'),
                    'relocate'            => post('relocate') ,
                    'contract'            => post('contract'),
                    'availability'        => post('availability'),
                    'min_annual_salary'   => intval(post('min_annual_salary')),
                    'annual_currency'     => post('annual_currency'),
                    'min_daily_salary'    => intval(post('min_daily_salary')),
                    'daily_currency'      => post('daily_currency'),
                    'min_hourly_salary'   => intval(post('min_hourly_salary')),
                    'hourly_currency'     => post('hourly_currency'),
                    'resume_info'         => post('resume_info'),
                    'consultant_id'       => User::get('id'),
                    'keywords'            => post('keywords'),
                    'education'           => post('education'),
                    'video_type'          => post('video_type'),
                    'video_link'          => post('video_link'),
                    'video_file'          => post('video_file'),
                    'cv'                  => post('file'),
                    'image'               => post('image'),
                );

                $_POST['languages'] = array_filter(explode(',', $_POST['languages']));
                $_POST['locations'] = array_filter(explode(',', $_POST['locations']));


                // Copy and remove image
                if ($data['image'] && ($this->view->user->image !== $data['image'])) {
                    if (File::copy('data/tmp/' . $data['image'], 'data/talent/open_profiles/' . $data['image'])) {
                        File::remove('data/talent/open_profiles/' . $this->view->user->image);
                    } else
                        print_data(error_get_last());
                }

                // Copy and remove file
                if ($data['cv'] && ($this->view->user->cv !== $data['cv'])) {
                    if (File::copy('data/tmp/' . $data['cv'], 'data/talent/open_profiles/' . $data['cv'])) {
                        File::remove('data/talent/open_profiles/' . $this->view->user->cv);
                    } else
                        print_data(error_get_last());
                }

                // Copy and remove file
                if ($data['video_type'] == 'file' && $data['video_file'] && $this->view->user->video_file !== $data['video_file']) {
                    if (File::copy('data/tmp/' . $data['video_file'], 'data/talent/open_profiles/' . $data['video_file'])) {
                        File::remove('data/talent/open_profiles/' . $this->view->user->video_file);
                    } else
                        print_data(error_get_last());
                }


                $result = Model::update('talent_open_profiles', $data, "`id` = '$id'"); // Update row

                if ($result) {

                    // Remove and after insert locations
                    Open_profilesModel::removeLocations($id);
                    if (is_array(post('locations')) && count(post('locations')) > 0) {
                        foreach (post('locations') as $location) {

                            $checkLoc = Model::fetch(Model::select('talent_locations', " `name` = '$location'"));
                            if ($checkLoc) {
                                Model::insert('talent_open_profiles_locations', array(
                                    'open_profile_id' => $id,
                                    'location_id' => $checkLoc->id
                                ));
                            } else {
                                Model::insert('talent_locations', array('name' => $location,));
                                $resId = Model::insertID();
                                Model::insert('talent_open_profiles_locations', array(
                                    'open_profile_id' => $id,
                                    'location_id' => $resId
                                ));
                            }
                        }
                    }

                    // Remove and after insert languages
                    Open_profilesModel::removeLanguages($id);
                    if (is_array(post('languages')) && count(post('languages')) > 0) {
                        foreach (post('languages') as $lang) {

                            $checkLang = Model::fetch(Model::select('talent_languages', " `name` = '$lang'"));
                            if ($checkLang) {
                                Model::insert('talent_open_profiles_languages', array(
                                    'open_profile_id' => $id,
                                    'language_id' => $checkLang->id
                                ));
                            } else {
                                Model::insert('talent_languages', array('name' => $lang,));
                                $resId = Model::insertID();
                                Model::insert('talent_open_profiles_languages', array(
                                    'open_profile_id' => $id,
                                    'language_id' => $resId
                                ));
                            }
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

        Request::setTitle('Edit Profile');
    }

    public function postcodeAction()
    {
        Request::ajaxPart();
        $postcode = post('postcode');
        $this->view->postcodes = Open_profilesModel::getPostcodes($postcode);

        if (!count($this->view->postcodes))
            Request::addResponse('html', '.suggests_result', '<div class="st-msg" onclick="closeSuggest();">Code not found</div>');
        else
            Request::addResponse('html', '.suggests_result', $this->getView());

        Request::endAjax();
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $skill = Open_profilesModel::get($id);

        if (!$skill)
            Request::returnError('Profile error');

        $data['deleted'] = 'yes';
        $result = Model::update('talent_open_profiles', $data, "`id` = '$id'"); // Update row

        if ($result) {
            Request::addResponse('func', 'noticeSuccess', 'Removed');
            Request::addResponse('remove', '#item_' . $id);
            Request::endAjax();
        } else {
            Request::returnError('Database error');
        }
    }
}
/* End of file */
