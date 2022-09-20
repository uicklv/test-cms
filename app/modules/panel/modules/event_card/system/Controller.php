<?php
class Event_cardController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = Event_cardModel::getAll();
        Request::setTitle('Events');
    }

    public function archiveAction()
    {
        $this->view->list = Event_cardModel::getArchived();
        Request::setTitle('Archived Events');
    }

    public function resumeAction()
    {
        $id = (Request::getUri(0));
        $user = Event_cardModel::get($id);

        if (!$user)
            redirect(url('panel/event_card/archive'));

        $result = Model::update('event_card', ['deleted' => 'no'], "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'restore', 'entity' => 'event_card#' . $id, 'time' => time()]);
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/event_card/archive'));

    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('name', 'Name', 'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'name'      => post('name'),
                    'time'      => time(),
                );

                $result   = Model::insert('event_card', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('redirect', false, url('panel', 'event_card', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Event');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = Event_cardModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/event_card'));

        Model::import('panel/event_card/categories');
        $this->view->sectors = CategoriesModel::getAll();

        if ($this->startValidation()) {
            $this->validatePost('name',             'Name',           'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('subtitle',         'Subtitle',       'trim|min_length[1]|max_length[255]');
            $this->validatePost('link',             'Link',           'trim|min_length[1]|max_length[100]|url');
            $this->validatePost('image',            'Image',          'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('posted',           'Posted',         'trim|min_length[1]|max_length[50]');

            // Times comparing/checking
            $intTime   = convertStringTimeToInt(post('time'));
            $checkTime = date("d/m/Y", $intTime);

            if ($checkTime != post('time')) {
                $this->addError('time', 'Wrong Date Posted');
            }

            if ($this->isValid()) {
                $published = post('published');
                if ($published !== 'yes')
                    $published = 'no';

                $data = array(
                    'name'            => post('name'),
                    'subtitle'        => post('subtitle'),
                    'link'            => post('link'),
                    'image'           => post('image'),
                    'category'        => post('category') ? arrayToString(post('category')) : '',
                    'published'       => $published,
                    'posted'          => post('posted'),
                    'time'            => $intTime
                );

                // Copy and remove image
                if ($this->view->edit->image !== $data['image']) {
                    if (File::copy('data/tmp/' . $data['image'], 'data/events/' . $data['image'])) {
                        File::remove('data/events/' . $this->view->edit->image);
                        File::remove('data/events/mini_' . $this->view->edit->image);
                        File::resize(
                            _SYSDIR_ . 'data/events/' . $data['image'],
                            _SYSDIR_ . 'data/events/mini_' . $data['image'],
                            377, 188
                        );
                    } else
                        print_data(error_get_last());
                }

                $result = Model::update('event_card', $data, "`id` = '$id'"); // Update row

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

        Request::setTitle('Edit Event');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = Event_cardModel::get($id);

        if (!$user)
            Request::returnError('Event error');

        $data['deleted'] = 'yes';
        $result = Model::update('event_card', $data, "`id` = '$id'"); // Update row

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
