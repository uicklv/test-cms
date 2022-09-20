<?php
class ResourcesController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = ResourcesModel::getAll();

        Request::setTitle('Resources');
    }

    public function archiveAction()
    {
        $this->view->list = ResourcesModel::getArchived();

        Request::setTitle('Archive Resources');
    }

    public function downloadsAction()
    {
        $this->view->list = Model::fetchAll(Model::select('resource_downloads', " 1 ORDER BY `time` DESC"));
        $this->view->recources = ResourcesModel::getAll();

        if ($this->view->list) {
            $this->view->list = Model::relationship($this->view->list, 'resource_downloads', 'resources',
                '*', false, 'resource_id', 'one_to_many');
        }
    }

    public function download_csvAction()
    {
        Request::ajaxPart();

        $vacancies = Model::fetchAll(Model::select('resource_downloads', " 1 ORDER BY `time` DESC"));

        if ($vacancies) {

            $vacancies = Model::relationship($vacancies, 'resource_downloads', 'resources',
                '*', false, false, 'one_to_many');

            //prepare data
            $dataToCsv = [];
            foreach ($vacancies as $k => $vacancy) {
                $dataToCsv[$k]['id'] = $vacancy->id;
                $dataToCsv[$k]['resource'] = $vacancy->resource->title . ' #' . $vacancy->resource->id;
                $dataToCsv[$k]['firstname'] = $vacancy->firstname;
                $dataToCsv[$k]['lastname'] = $vacancy->lastname;
                $dataToCsv[$k]['email'] = $vacancy->email;
                $dataToCsv[$k]['date'] = date('m.d.Y', $vacancy->time);
            }

            $df = fopen("app/data/tmp/resource_downloads.csv", 'w');
            fputcsv($df, array_keys(reset($dataToCsv)), ';');
            foreach ($dataToCsv as $row) {
                fputcsv($df, $row, ';');
            }
            fclose($df);

            Request::addResponse('func', 'downloadFile', _SITEDIR_ . 'data/tmp/resource_downloads.csv');
            Request::addResponse('func', 'removeLoader');
            Request::endAjax();
        } else {
            Request::addResponse('func', 'removeLoader');
            Request::addResponse('func', 'alert', 'No Data');
        }

    }

    public function resumeAction()
    {
        $id = (Request::getUri(0));
        $user = ResourcesModel::get($id);

        if (!$user)
            redirect(url('panel/resources/archive'));

        $result = Model::update('resources', ['deleted' => 'no'], "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'restore', 'entity' => 'testimonial#' . $id, 'time' => time()]);
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/resources/archive'));
    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('title',             'Title',           'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'title'     => post('title'),
                    'time'     => time(),
                );

                $result   = Model::insert('resources', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('redirect', false, url('panel', 'resources', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Testimonial');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = ResourcesModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/resources'));

        Model::import('panel/team');
        $this->view->users = TeamModel::getAllUsers();

        if ($this->startValidation()) {
            $this->validatePost('title',                     'Title',          'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('image',                     'Image',          'required|min_length[1]|max_length[60]');
            $this->validatePost('file',                      'File',           'required|min_length[1]|max_length[100]');
            $this->validatePost('content',                   'Page Content',   'trim|min_length[0]|max_length[300]');

            if ($this->isValid()) {
                $data = array(
                    'title'    => post('title'),
                    'image'    => post('image'),
                    'content'  => post('content'),
                    'file'     => post('file'),
                    'type'     => post('type'),
                    'posted'   => post('posted'),
                    'author'   => post('author'),
                );

                // Copy and remove image
                if ($data['image'] && $this->view->edit->image !== $data['image']) {
                    if (File::copy('data/tmp/' . $data['image'], 'data/resources/' . $data['image'])) {
                        File::remove('data/resources/' . $this->view->edit->image);
                        File::remove('data/resources/mini_' . $this->view->edit->image);
                        File::resize(
                            _SYSDIR_ . 'data/resources/' . $data['image'],
                            _SYSDIR_ . 'data/resources/mini_' . $data['image'],
                            490, 300
                        );
                    } else
                        print_data(error_get_last());
                }

                //Copy file
                if ($data['file'] && $this->view->edit->file !== $data['file']) {
                    if (File::copy('data/tmp/' . $data['file'], 'data/resources/' . $data['file'])) {
                        File::remove('data/resources/' . $this->view->edit->file);
                    } else
                        print_data(error_get_last());
                }

                $result = Model::update('resources', $data, "`id` = '$id'"); // Update row

                if ($result) {
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

        Request::setTitle('Edit Resource');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = ResourcesModel::get($id);

        if (!$user)
            Request::returnError('Judge error');

        $data['deleted'] = 'yes';
        $result = Model::update('resources', $data, "`id` = '$id'"); // Update row

        if ($result) {
            Request::addResponse('func', 'noticeSuccess', 'Removed');
            Request::addResponse('remove', '#item_' . $id);
            Request::endAjax();
        } else {
            Request::returnError('Database error');
        }
    }

    public function delete_downloadsAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $download = Model::fetch(Model::select('resource_downloads', "`id` = '$id'"));

        if (!$download)
            Request::returnError('Resource error');

        $result = Model::delete('resource_downloads', "`id` = '$id'");
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
