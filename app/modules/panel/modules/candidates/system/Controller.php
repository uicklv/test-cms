<?php
class CandidatesController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->team = CandidatesModel::getAllUsers();

        Request::setTitle('Candidates');
    }

    public function archiveAction()
    {
        $this->view->team = CandidatesModel::getArchived();

        Request::setTitle('Archived Candidates');
    }

    public function resumeAction()
    {
        $id = (Request::getUri(0));
        $user = CandidatesModel::getUser($id);

        if (!$user)
            redirect(url('panel/candidates/archive'));

        $result = Model::update('candidates', ['deleted' => 'no'], "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'restore', 'entity' => 'candidate#' . $id, 'time' => time()]);
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/candidates/archive'));
    }

    public function editAction()
    {
        $userID = intval(Request::getUri(0));
        $this->view->user = CandidatesModel::getUser($userID);

        if (!$this->view->user)
            redirect(url('panel/candidates'));

        if ($this->startValidation()) {
            $this->validatePost('firstname',    'First Name',       'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('lastname',     'Last Name',        'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('email',        'Email',            'required|trim|email');
            $this->validatePost('tel',          'Telephone Number', 'trim|min_length[0]|max_length[100]');
            $this->validatePost('password',     'Password',         'trim|password');
            $this->validatePost('title',        'Title',            'trim|min_length[0]|max_length[150]');
            $this->validatePost('description',  'Description',      'trim|min_length[0]');
            $this->validatePost('linkedin',     'LinkedIn URL',     'trim|min_length[0]|max_length[100]');
            $this->validatePost('twitter',      'Twitter URL',      'trim|min_length[0]|max_length[100]');
            $this->validatePost('skype',        'Skype',            'trim|min_length[0]|max_length[100]');
            $this->validatePost('slug',         'Slug',             'required|trim|min_length[1]|max_length[100]');

            if ($this->isValid()) {
                $data = array(
                    'firstname'     => post('firstname'),
                    'lastname'      => post('lastname'),
                    'email'         => post('email'),
                    'tel'           => post('tel'),
                    'title'         => post('title'),
                    'description'   => post('description'),
                    'linkedin'      => post('linkedin'),
                    'twitter'       => post('twitter'),
                    'skype'         => post('skype'),
                    'image'         => post('image'),
                    'slug'          => post('slug'),
                );

                if (post('password'))
                    $data['password'] = md5(post('password'));

                // Copy and remove image
                if ($this->view->user->image !== $data['image']) {
                    if (File::copy('data/tmp/' . $data['image'], 'data/candidates/' . $data['image'])) {
                        File::remove('data/candidates/' . $this->view->user->image);
                    } else
                        print_data(error_get_last());
                }

                $result = Model::update('candidates', $data, "`id` = '$userID'"); // Update row

                if ($result) {
//                    Request::addResponse('redirect', false, url('panel', 'candidates', 'edit', $userID));
                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Edit Candidate');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        Model::import('panel/team');
        $user = CandidatesModel::getUser($id);

        if (!$user)
            Request::returnError('Candidate error');

        $data['deleted'] = 'yes';
        $result = Model::update('candidates', $data, "`id` = '$id'"); // Update row

        if ($result) {
            Request::addResponse('func', 'noticeSuccess', 'Removed');
            Request::addResponse('remove', '#item_' . $id);
            Request::endAjax();
        } else {
            Request::returnError('Database error');
        }
    }

    // Applications

    public function applicationsAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->user = CandidatesModel::getUser($id);

        if (!$this->view->user)
            redirect(url('panel/candidates'));

        Model::import('panel/vacancies');

        $email = $this->view->user->email;

//        $this->view->list = VacanciesModel::getVacanciesCandidate(false, false, " AND cl.`candidate_id` = '" . $id . "'");
        $this->view->list = CandidatesModel::getAllVacanciesWhere(" `candidate_id` = '" . $id . "' AND `email` = '" . $email . "'");

        Request::setTitle('List of candidate applications');
    }

    public function uploadAction()
    {
        Request::ajaxPart(); // if not Ajax part load

        $name = post('name'); // image name, if not set - will be randomly
        $path = post('path', true, 'tmp'); // path where image will be saved, default: 'tmp'
        $preview = post('preview', true, '#preview_image'); // field where to put image name after uploading

        if (!$name) $name = randomHash();

        $data['path'] = 'data/' . $path . '/';
        $data['new_name'] = $name;
        //$data['new_format'] = 'png';
        $data['mkdir'] = true;

        $imgInfo = null;
        foreach ($_FILES as $file) {
            $imgInfo = File::uploadImage($file, $data);
            break;
        }

        $imageName = $imgInfo['new_name'] . '.' . $imgInfo['new_format']; // new name & format


        $data = array(
            'user_id' => post('user'),
            'image'   => $imageName,
        );
        $result   = Model::insert('user_images', $data); // Insert row
        $insertID = Model::insertID();

        Request::addResponse('append', $preview, '<img src="' . _SITEDIR_ . 'data/' . $path . '/' . $imageName . '?t=' . time() . '" alt="" style="max-height: 150px; max-width: 150px;">');
    }

    public function remove_funAction()
    {
        Request::ajaxPart(); // if not Ajax part load
        $id = post('id');

        Model::import('panel/team');
        $image = TeamModel::getUserImage($id);

        Model::delete('user_images', "`id` = '$id'");
        File::remove('data/fun/' . $image->image);
        Request::addResponse('remove', '#ft_' . $id, false);
    }

    public function export_applied_jobAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $this->validatePost('type', 'Type', 'required');

            if ($this->isValid()) {

                $data = Model::fetchAll(Model::select('cv_library', "`deleted` = 'no' AND  `candidate_id` = '2' AND `email` = 'test@gmail.com'", '*, (SELECT `vacancies`.`title` FROM `vacancies` WHERE `id` = `cv_library`.`vacancy_id`) as vacancy_title'));

                if (is_array($data) && count($data) > 0) {

                    //prepare data
                    $dataToCsv = [];
                    $i = 0;
                    foreach ($data as $item) {
                        $dataToCsv[$i]['id'] = $item->id;
                        $dataToCsv[$i]['name'] = $item->name;
                        $dataToCsv[$i]['vacancy'] = $item->vacancy_title;
                        $dataToCsv[$i]['email'] = $item->email;
                        $dataToCsv[$i]['date submitted'] = date('m.d.Y', $item->time);
                        $dataToCsv[$i]['cv link'] = SITE_NAME . _SITEDIR_ . 'data/cvs/' . $item->cv ;

                        $i++;
                    }

                    $df = fopen("app/data/tmp/export.csv", 'w');
                    fputcsv($df, array_keys(reset($dataToCsv)), ';');
                    foreach ($dataToCsv as $row) {
                        fputcsv($df, $row, ';');
                    }
                    fclose($df);

                    Request::addResponse('func', 'downloadFile', _SITEDIR_ . 'data/tmp/export.csv');
                    Request::endAjax();
                } else {
                    Request::returnError('No Data');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

    }

    private function createZip($files)
    {
        if (!file_exists(_SYSDIR_ . 'data/export')) {
            mkdir(_SYSDIR_ . 'data/export', 0777, true);
        }

        $zip = new ZipArchive;

        if ($zip->open(_SYSDIR_ . 'data/export/cvs.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE)
        {
            foreach ($files as $k => $file) {
                $relativeNameInZipFile = basename($file);
                $zip->addFile($file, $relativeNameInZipFile);
                $zip->renameName($relativeNameInZipFile, $k);
            }

            // All files are added, so close the zip file.
            $zip->close();
        }

        if (file_exists(_SYSDIR_ . 'data/export/cvs.zip'))
            return true;
        else
            return false;
    }
}
/* End of file */