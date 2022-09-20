<?php
class LandingsController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = LandingsModel::getAllSimple();

        Request::setTitle('Page Builder');
    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('title',        'Title',            'required|trim|min_length[0]|max_length[100]');

            if ($this->isValid()) {
                $data = array(
                    'title'            => post('title'),
                    'ref'              => makeSlug(post('title')),
                    'time'             => time(),
                );

                $result   = Model::insert('landings', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    redirectAny(url('panel', 'landings', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Page');
    }

    public function editAction()
    {

        $id = intval(Request::getUri(0));
        $this->view->edit = LandingsModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/landings'));

        if ($this->startValidation()) {
            $this->validatePost('title',        'Title',            'required|trim|min_length[0]|max_length[100]');
            $this->validatePost('ref',          'Ref',              'required|trim|min_length[0]|max_length[50]');
            $this->validatePost('meta_title',   'Meta Title',       'trim|min_length[0]|max_length[200]');
            $this->validatePost('meta_keywords','Meta Keywords',    'trim|min_length[0]|max_length[200]');
            $this->validatePost('meta_desc',    'Meta Description', 'trim|min_length[0]|max_length[200]');


            $hide = post('hide') ? post('hide') : 'no';

            if ($this->isValid()) {

                $data = array(
                    'title'                 => post('title'),
                    'ref'                   => post('ref'),
                    'section_row'           => post('section_row'),
                    'meta_title'            => post('meta_title'),
                    'meta_keywords'         => post('meta_keywords'),
                    'meta_desc'             => post('meta_desc'),
                    'og_image'              => post('og_image'),
//                    'color_dropdown'        => post('color_dropdown'),
                );

                // Copy and remove og_image
                if ($data['og_image'] && ($this->view->edit->og_image !== $data['og_image'])) {
                    if (File::copy('data/tmp/' . $data['og_image'], 'data/landings/' . $data['og_image'])) {
                        File::remove('data/landings/' . $this->view->edit->og_image);
                    } else
                        print_data(error_get_last());
                }

                $result = Model::update('landings', $data, "`id` = '$id'"); // Update row

                if ($result)
                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                else
                    Request::returnError('Database error');
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        $this->view->maps_api = SettingsModel::get('maps_api_key');
        $this->view->sections = LandingsModel::getSections($id);

        Request::setTitle('Edit Page');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $userID = intval(Request::getUri(0));
        $user = LandingsModel::get($userID);

        if (!$user)
            Request::returnError('Page error');

        $data['deleted'] = 'yes';
        $result = Model::update('landings', $data, "`id` = '$userID'"); // Update row

        if ($result) {
            Request::addResponse('func', 'noticeSuccess', 'Removed');
            Request::addResponse('remove', '#item_' . $userID);
            Request::endAjax();
        } else {
            Request::returnError('Database error');
        }
    }

    public function add_sectionAction()
    {
        Request::ajaxPart();
        $mid = $this->view->mid = Request::getUri(0);

        if ($this->startValidation()) {
            $this->validatePost('name', 'Section name', 'required|trim|min_length[0]|max_length[100]');
            $this->validatePost('type', 'Type',         'required|trim|min_length[0]|max_length[100]');

            if ($this->isValid()) {
                $data = array(
                    'landing_id' => $mid,
                    'name' => post('name'),
                    'type' => post('type'),
                );

                if ($data['type'] == 'map') {
                    $check = Model::fetch(Model::select('landings_sections', " `landing_id` = '$mid' AND `type` = 'map' AND `deleted` = 'no'"));

                    if ($check)
                        Request::returnError("the map section has already been created!");
                }

                $result   = Model::insert('landings_sections', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Model::query("UPDATE `landings` SET `section_row` = CONCAT(`section_row`, '|$insertID|') WHERE `id` = '$mid'");

                    redirectAny(url('panel', 'landings', 'edit', $mid));
                } else {
                    Request::returnError('Database error');
                }
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::addResponse('html', '#popup', $this->getView());
    }

    public function edit_sectionAction()
    {
        Request::ajaxPart();
        $sid = Request::getUri(0);

        if ($this->startValidation()) {

            if ($this->isValid()) {

                $data = array(
//                    'name'     => post('name'),
                    'content1' => post('content1'),
                    'content2' => post('content2'),
                    'content3' => post('content3'),
                    'content4' => post('content4'),
                    'content5' => post('content5'),
                    'content6' => post('content6'),
                    'options'  => post('options'),
                );

                $edit = LandingsModel::getSection($sid);

                // Copy and remove image
                if ($edit->type == 'home' || $edit->type == 'picture_text' || $edit->type == 'video' || $edit->type == 'video_text') {
                    if ($edit->content3 !== $data['content3']) {
                        if (File::copy('data/tmp/' . $data['content3'], 'data/landings/' . $data['content3'])) {
                            File::remove('data/content3/' . $edit->content3);
                        } else
                            print_data(error_get_last());
                    }
                }

                // Copy and remove image
                if ($edit->type == '2_blocks') {
                    if ($edit->content3 !== $data['content3']) {
                        if (File::copy('data/tmp/' . $data['content3'], 'data/landings/' . $data['content3'])) {
                            File::remove('data/content3/' . $edit->content3);
                        } else
                            print_data(error_get_last());
                    }
                    if ($edit->content4 !== $data['content4']) {
                        if (File::copy('data/tmp/' . $data['content4'], 'data/landings/' . $data['content4'])) {
                            File::remove('data/content4/' . $edit->content4);
                        } else
                            print_data(error_get_last());
                    }

                }

                // Copy and remove image
                if ($edit->type == '3_blocks' || $edit->type == 'how_it_work') {
                    if ($edit->content4 !== $data['content4']) {
                        if (File::copy('data/tmp/' . $data['content4'], 'data/landings/' . $data['content4'])) {
                            File::remove('data/content4/' . $edit->content4);
                        } else
                            print_data(error_get_last());
                    }
                    if ($edit->content5 !== $data['content5']) {
                        if (File::copy('data/tmp/' . $data['content5'], 'data/landings/' . $data['content5'])) {
                            File::remove('data/content5/' . $edit->content5);
                        } else
                            print_data(error_get_last());
                    }
                    if ($edit->content6 !== $data['content6']) {
                        if (File::copy('data/tmp/' . $data['content6'], 'data/landings/' . $data['content6'])) {
                            File::remove('data/content6/' . $edit->content6);
                        } else
                            print_data(error_get_last());
                    }

                }

                $result = Model::update('landings_sections', $data, "`id` = '$sid'");

                if ($result) {
                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                } else {
                    Request::returnError('Database error');
                }
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::addResponse('html', '#popup', $this->getView());
    }


    public function edit_section_processAction()
    {
        Request::ajaxPart();

        $sid = Request::getUri(0);

        if ($sid) {
            $data = array(
                //                    'name'     => post('name'),
                'content1' => post('content1'),
                'content2' => post('content2'),
                'content3' => post('content3'),
                'content4' => post('content4'),
                'content5' => post('content5'),
                'content6' => post('content6'),
                'options' => post('options'),
            );

            $edit = LandingsModel::getSection($sid);

            // Copy and remove image
            if ($edit->type == 'home' || $edit->type == 'picture_text' || $edit->type == 'video' || $edit->type == 'video_text') {
                if ($edit->content3 !== $data['content3']) {
                    if (File::copy('data/tmp/' . $data['content3'], 'data/landings/' . $data['content3'])) {
                        File::remove('data/content3/' . $edit->content3);
                    } else
                        print_data(error_get_last());
                }
            }

            // Copy and remove image
            if ($edit->type == '2_blocks') {
                if ($edit->content3 !== $data['content3']) {
                    if (File::copy('data/tmp/' . $data['content3'], 'data/landings/' . $data['content3'])) {
                        File::remove('data/content3/' . $edit->content3);
                    } else
                        print_data(error_get_last());
                }
                if ($edit->content4 !== $data['content4']) {
                    if (File::copy('data/tmp/' . $data['content4'], 'data/landings/' . $data['content4'])) {
                        File::remove('data/content4/' . $edit->content4);
                    } else
                        print_data(error_get_last());
                }

            }

            // Copy and remove image
            if ($edit->type == '3_blocks' || $edit->type == 'how_it_work') {
                if ($edit->content4 !== $data['content4']) {
                    if (File::copy('data/tmp/' . $data['content4'], 'data/landings/' . $data['content4'])) {
                        File::remove('data/content4/' . $edit->content4);
                    } else
                        print_data(error_get_last());
                }
                if ($edit->content5 !== $data['content5']) {
                    if (File::copy('data/tmp/' . $data['content5'], 'data/landings/' . $data['content5'])) {
                        File::remove('data/content5/' . $edit->content5);
                    } else
                        print_data(error_get_last());
                }
                if ($edit->content6 !== $data['content6']) {
                    if (File::copy('data/tmp/' . $data['content6'], 'data/landings/' . $data['content6'])) {
                        File::remove('data/content6/' . $edit->content6);
                    } else
                        print_data(error_get_last());
                }

            }

            $result = Model::update('landings_sections', $data, "`id` = '$sid'");
        }
    }

    public function section_deleteAction()
    {
        $section_id = (Request::getUri(0));
        $section = LandingsModel::getSection($section_id);

        if (!$section)
            redirect(url('panel/landings/edit/' . $section->landing_id ));

        $landing = LandingsModel::get($section->landing_id);

        $sectionIdsArray = explode('||', trim($landing->section_row, '|'));

        foreach ($sectionIdsArray as $k => $sectionId) {
            if ($sectionId == $section_id) unset($sectionIdsArray[$k]);
        }

        $newSectionRow = '|' . implode('||', $sectionIdsArray) . '|';
        Model::update('landings', ['section_row' => $newSectionRow], " `id` =" . $landing->id);

        $data['deleted'] = 'yes';
        $result = Model::update('landings_sections', $data, "`id` = '$section_id'"); // Update row

        if ($result) {
//            $this->session->set_flashdata('success', 'User created successfully.');
//            Request::addResponse('redirect', false, url('panel', 'microsites', 'edit', $insertID));
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/landings/edit/' . $section->landing_id ));
    }
}
/* End of file */
