<?php
class ShopsController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = ShopsModel::getAll();

        Request::setTitle('Manage Products');
    }

    public function addAction()
    {

        if ($this->startValidation()) {
            $this->validatePost('title', 'Title', 'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'title' => post('title'),
                    'slug'  => Model::createIdentifier('shop_products', makeSlug(post('title'))),
                    'time'  => time(),
                );

                $result = Model::insert('shop_products', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'add', 'entity' => 'product#' . $insertID, 'time' => time()]);

                    Request::addResponse('redirect', false, url('panel', 'shops', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Vacancy');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = ShopsModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/shops'));

        Model::import('panel/shops/brands');
        Model::import('panel/shops/types');

        $this->view->brands = BrandsModel::getAll();
        $this->view->types = TypesModel::getAll();

        if ($this->startValidation()) {
            $this->validatePost('title', 'Job Title', 'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('ref', 'Job Ref', 'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('brand_ids', 'Brands', 'is_array');
            $this->validatePost('type_ids', 'Types', 'is_array');
            $this->validatePost('price', 'Price', 'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('time', 'Date Posted', 'trim|min_length[1]|max_length[50]');
            $this->validatePost('preview_content', 'Preview Content', 'required|trim|min_length[1]');
            $this->validatePost('content', 'Content', 'required|trim|min_length[1]');
            $this->validatePost('meta_title', 'Meta Title', 'trim|min_length[0]|max_length[200]');
            $this->validatePost('meta_keywords', 'Meta Keywords', 'trim|min_length[0]|max_length[200]');
            $this->validatePost('meta_desc', 'Meta Description', 'trim|min_length[0]|max_length[200]');
            $this->validatePost('image', 'Image', 'trim|min_length[1]|max_length[100]');

            // Times comparing/checking
            $intTime = convertStringTimeToInt(post('time'));
            $checkTime = date("d/m/Y", $intTime);

            if ($checkTime != post('time')) {
                $this->addError('time', 'Wrong Date Posted');
            }

            if ($this->isValid()) {
                $data = array(
                    'title' => post('title'),
                    'ref' =>  Model::createIdentifier('shop_products', post('ref'), 'ref', $this->view->edit->id),
                    'price' => post('price'),
                    'highlight' => intval(post('highlight', 'int')),
                    'content' => post('content'),
                    'preview_content' => post('preview_content'),
                    'meta_title' => post('meta_title'),
                    'meta_keywords' => post('meta_keywords'),
                    'meta_desc' => post('meta_desc'),
                    'posted' => post('posted') == 'yes' ? 'yes' : 'no',
                    'time' => $intTime,
                    'image' => post('image'),
                    'slug' => Model::createIdentifier('shop_products', post('slug'), 'slug', $this->view->edit->id),
                );

                // Copy and remove image
                if ($data['image']) {
                    if ($this->view->edit->image !== $data['image']) {
                        if (File::copy('data/tmp/' . $data['image'], 'data/shop/' . $data['image'])) {
                            File::remove('data/shop/' . $this->view->edit->image);
                        } else
                            Request::returnError('Image copy error: ' . error_get_last()['message']);
                    }
                }


                $result = Model::update('shop_products', $data, "`id` = '$id'"); // Update row

                if ($result) {
                    // Log
                    Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'edit', 'entity' => 'product#' . $id, 'time' => time()]);

                    // Remove and after insert brands
                    ShopsModel::removeBrands($id);
                    if (is_array(post('brand_ids')) && count(post('brand_ids')) > 0) {
                        foreach (post('brand_ids') as $sector_id) {
                            Model::insert('shop_products_brands', array(
                                'shop_product_id' => $id,
                                'brand_id' => $sector_id
                            ));
                        }
                    }

                    // Remove and after insert types
                    ShopsModel::removeTypes($id);
                    if (is_array(post('type_ids')) && count(post('type_ids')) > 0) {
                        foreach (post('type_ids') as $sector_id) {
                            Model::insert('shop_products_types', array(
                                'shop_product_id' => $id,
                                'type_id' => $sector_id
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

        Request::setTitle('Edit Product');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = ShopsModel::get($id);

        if (!$user)
            Request::returnError('Vacancy error');

        $data['deleted'] = 'yes';
        $result = Model::update('shop_products', $data, "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'delete', 'entity' => 'vacancy#' . $id, 'time' => time()]);

            Request::addResponse('func', 'noticeSuccess', 'Removed');
            Request::addResponse('remove', '#item_' . $id);
            Request::endAjax();
        } else {
            Request::returnError('Database error');
        }
    }

    public function applicationsAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = ShopsModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/vacancy_applications'));

        $this->view->list = ShopsModel::getAppWhere("`vacancy_id` = '$id'");

        Request::setTitle('Job Application List');
    }

    public function application_popupAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $this->view->apply = ShopsModel::getApplication($id);

        if (!$this->view->apply)
            redirect(url('panel/vacancies/applications/' . post('vacancy_id')));

        if (!$this->view->apply->status) {
            Model::update('cv_library', ['status' => 'reviewed'], "`id` = '$id'");

            $el = applicationStatus('reviewed');
            $select = '<div class="fs-item ' . $el['class'] . '">' . $el['title'] . '</div>';
            Request::addResponse('html', '#status_text_' . $id, $select);
            Request::addResponse('html', '#popup_status', $select);
        }

        Request::addResponse('html', '#popup', $this->getView());
    }

    public function change_app_statusAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $status = $this->validatePost('status', 'status', 'required|trim|min_length[1]|max_length[15]');

            switch ($status) {
                case 'spoken':
                case 'interviewed':
                case 'shortlisted':
                case 'rejected':
                    break;

                default:
                    $status = 'reviewed';
            }

            if ($this->isValid()) {
                $id = intval(Request::getUri(0));

                $result = Model::update('cv_library', ['status' => $status], "`id` = '$id'"); // Update row

                if ($result) {
                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                    Request::addResponse('func', 'closeStatusBlock');
                    $el = applicationStatus($status);
                    $select = '<div class="fs-item ' . $el['class'] . '">' . $el['title'] . '</div>';
                    Request::addResponse('html', '#status_text_' . $id, $select);
                    Request::addResponse('html', '#popup_status', $select);
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }
    }

    public function add_candidateAction()
    {
        Request::ajaxPart();


        $vacancy_id = post('vacancy_id');
        $candidate_id = post('candidate_id');

        $check = ShopsModel::getVacancyCandidate($vacancy_id, $candidate_id);

        if ($check) {
            Request::returnError('This candidate has already been added');
        } else {

            Model::insert('vacancies_candidates', [
                'vacancy_id' => post('vacancy_id'),
                'candidate_id' => post('candidate_id')
            ]);

            Model::insert('candidates_status', [
                'vacancy_id' => post('vacancy_id'),
                'candidate_id' => post('candidate_id')
            ]);

            Model::import('panel/team');
            Model::import('panel/vacancies');
            $vacancy = ShopsModel::get($vacancy_id);
            $customer = TeamModel::getUser($vacancy->consultant_id);
            $this->view->customer = $customer;
            $this->view->vacancy = $vacancy;

            if (is_array($vacancy->customers) && count($vacancy->customers) > 0) {
                foreach ($vacancy->customers as $item) {

                    $this->view->customer = $item;
                    $this->view->vacancy = $vacancy;

                    // Mail to client/consultant
                    $mail = new Mail;
                    $mail->initDefault('New Candidate', $this->getView('modules/panel/modules/vacancies/views/email_templates/new_candidate.php'));
                    $mail->AddAddress($item->email);
                    $mail->sendEmail('new_candidate');
                }
            }

            $this->view->vacancy_candidates = ShopsModel::getVacancyCandidates($vacancy_id);
            $this->view->vacancy_id = $vacancy_id;
            Request::addResponse('html', '#candidates_box', $this->getView('modules/panel/modules/vacancies/views/get_candidates.php'));
        }
    }

    public function apply_deleteAction()
    {
        $id = (Request::getUri(0));
        $app = ShopsModel::getApplication($id);

        if (!$app)
            redirect(url('panel/vacancies/applications/' . get('id')));

        $data['deleted'] = 'yes';
        $result = Model::update('cv_library', $data, "`id` = '$id'"); // Update row

        if ($result) {
        } else
            Request::returnError('Database error');

        redirectAny(url('panel/vacancies/applications/' . get('id')));
    }

    public function delete_candidateAction()
    {
        Request::ajaxPart();

        $vacancy_id = post('vacancy_id');
        $candidate_id = post('candidate_id');

        $check = ShopsModel::getVacancyCandidate($vacancy_id, $candidate_id);

        if ($check) {
            Model::delete('vacancies_candidates', " `vacancy_id` = '" . $vacancy_id . "' AND
             `candidate_id` = '" . $candidate_id . "'");

            Model::delete('candidates_status', " `vacancy_id` = '" . $vacancy_id . "' AND
             `candidate_id` = '" . $candidate_id . "'");

            $this->view->vacancy_candidates = ShopsModel::getVacancyCandidates($vacancy_id);
            $this->view->vacancy_id = $vacancy_id;
            Request::addResponse('html', '#candidates_box', $this->getView('modules/panel/modules/vacancies/views/get_candidates.php'));
        } else {
            Request::returnError('Record does not exist');
        }
    }

    public function search_candidatesAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $this->validatePost('candidate_name', 'Candidate Name', 'trim');

            if ($this->isValid()) {

                $candidate = post('candidate_name');

                Model::import('panel/candidates_portal');
                $this->view->candidates = Candidates_portalModel::search($candidate);
                $this->view->vacancy_id = post('vacancy_id');
                Request::addResponse('html', '#result_box', $this->getView());
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }
    }

    public function duplicateAction()
    {
        Request::ajaxPart();
        $id = intval(Request::getUri(0));
        $vacancy = ShopsModel::get($id);

        if (!$vacancy)
            redirect(url('panel/shops'));

        $data = [
            'title' => $vacancy->title,
            'price' => $vacancy->price,
            'highlight' => $vacancy->highlight,
            'content' => $vacancy->content,
            'preview_content' => $vacancy->preview_content,
            'meta_title' => $vacancy->meta_title,
            'meta_keywords' => $vacancy->meta_keywords,
            'meta_desc' => $vacancy->meta_desc,
            'time' => $vacancy->time,
            'image' => $vacancy->image,
            'slug' => Model::createIdentifier('shop_products', makeSlug($vacancy->title)),
            'ref' => Model::createIdentifier('shop_products', $vacancy->ref, 'ref'),
        ];


        $result = Model::insert('shop_products', $data); // Insert row
        $insertID = Model::insertID();

        if (!$result && $insertID) {

            // Remove and after insert sectors
            $products = ShopsModel::getProductTypes($id);
            if ($products) {
                foreach ($products as $product) {
                    Model::insert('shop_products_types', array(
                        'shop_product_id' => $insertID,
                        'type_id' => $product->type_id
                    ));
                }
            }

            // Remove and after insert locations
            $brands = ShopsModel::getProductBrands($id);
            if ($brands) {
                foreach ($brands as $brand) {
                    Model::insert('shop_products_brands', array(
                        'shop_product_id' => $insertID,
                        'brand_id' => $brand->brand_id
                    ));
                }
            }

            Request::addResponse('redirect', false, url('panel', 'shops', 'edit', $insertID));

        } else {
            Request::returnError('Database Error');
        }

    }

    public function archiveAction()
    {
        $id = intval(Request::getUri(0));

        $this->view->list = ShopsModel::getAll(false, false, 'yes');

        Request::setTitle('Archive Vacancies');
    }

    public function resumeAction()
    {
        $id = (Request::getUri(0));
        $user = ShopsModel::get($id);

        if (!$user)
            redirect(url('panel/shops/archive'));

        $result = Model::update('shop_products', ['deleted' => 'no'], "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'restore', 'entity' => 'shop-product#' . $id, 'time' => time()]);
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/shops/archive'));
    }

    public function expireAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = ShopsModel::get($id);

        if (!$id)
            redirect(url('panel/vacancies'));

        if ($this->startValidation()) {
            $this->validatePost('reason', 'Reason', 'required|trim|min_length[1]|max_length[255]');


            if ($this->isValid()) {
                $data = array(
                    'expire_reason' => post('reason'),
                    'time_expire' => time() - 180 * 24 * 3600,
                );

                $result = Model::update('vacancies', $data, "`id` = '$id'"); // Update row

                if ($result) {
                    Request::addResponse('redirect', false, url('panel', 'vacancies'));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Expire Vacancy');
    }

    public function download_csvAction()
    {
        Request::ajaxPart();

        $vacancies = ShopsModel::getAll();

        if (is_array($vacancies) && count($vacancies) > 0) {
            //prepare data
            $dataToCsv = [];
            foreach ($vacancies as $k => $vacancy) {
                $fullname = ($value->consultant->firstname ?? '') . ' ' . ($value->consultant->lastname ?? '');

                $dataToCsv[$k]['id'] = $vacancy->id;
                $dataToCsv[$k]['title'] = $vacancy->title;
                $dataToCsv[$k]['ref'] = $vacancy->ref;
                $dataToCsv[$k]['locations'] = implode(", ", array_map(function ($location) {
                    return $location->location_name;
                }, $vacancy->locations));
                $dataToCsv[$k]['sectors'] = implode(", ", array_map(function ($sector) {
                    return $sector->sector_name;
                }, $vacancy->sectors));
                $dataToCsv[$k]['contract_type'] = $vacancy->contract_type;
                $dataToCsv[$k]['salary'] = $vacancy->salary_value;
                $dataToCsv[$k]['date_created'] = date('m.d.Y', $vacancy->time);
                $dataToCsv[$k]['date_expire'] = date('m.d.Y', $vacancy->time_expire);
                $dataToCsv[$k]['description'] = reFilter($vacancy->content);
                $dataToCsv[$k]['views'] = $vacancy->views;
                $dataToCsv[$k]['applications'] = $vacancy->applications;
                $dataToCsv[$k]['consultant'] = $fullname;
                $dataToCsv[$k]['slug'] = $vacancy->slug;
            }

            $df = fopen("app/data/tmp/vacancies.csv", 'w');
            fputcsv($df, array_keys(reset($dataToCsv)), ';');
            foreach ($dataToCsv as $row) {
                fputcsv($df, $row, ';');
            }
            fclose($df);

            Request::addResponse('func', 'downloadFile', _SITEDIR_ . 'data/tmp/vacancies.csv');
            Request::addResponse('func', 'removeLoader');
            Request::endAjax();
        } else {
            Request::addResponse('func', 'removeLoader');
            Request::addResponse('func', 'alert', 'No Vacancies');
        }

    }

    public function download_xmlAction()
    {
        header("Content-type: xml");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-cache, must-relative");
        //get all jobs
        $jobs = ShopsModel::getAll();

        //create xml and set options
        $dom = new DOMDocument();
        $dom->encoding = 'utf-8';
        $dom->xmlVersion = '1.0';
        $dom->formatOutput = true;
        $xml_file_name = 'jobs.xml';

        //create file content
        $root = $dom->createElement('jobs');
        foreach ($jobs as $value) {
            $fullname = ($value->consultant->firstname ?? '') . ' ' . ($value->consultant->lastname ?? '');

            $job = $root->appendChild($dom->createElement('job'));
            $job->appendChild($dom->createElement('id', $value->id));
            $job->appendChild($dom->createElement('ref', $value->ref));
            $job->appendChild($dom->createElement('title', $value->title));
            $job->appendChild($dom->createElement('locations', implode(", ", array_map(function ($location) {
                return $location->location_name;
            }, $value->locations))));
            $job->appendChild($dom->createElement('sectors', implode(", ", array_map(function ($sector) {
                return $sector->sector_name;
            }, $value->sectors))));
            $job->appendChild($dom->createElement('contract_type', $value->contract_type));
            $job->appendChild($dom->createElement('salary', $value->salary_value));
            $job->appendChild($dom->createElement('description', reFilter($value->content)));
            $job->appendChild($dom->createElement('views', $value->views));
            $job->appendChild($dom->createElement('applications', $value->applications));
            $job->appendChild($dom->createElement('consultant', $fullname));
            $job->appendChild($dom->createElement('date_created', date('m.d.Y', $value->time)));
            $job->appendChild($dom->createElement('date_expire', date('m.d.Y', $value->time_expire)));
            $job->appendChild($dom->createElement('slug', $value->slug));
            $job->appendChild($dom->createElement('link', SITE_URL . 'job/' . $value->slug));
        }

        $dom->appendChild($root);
        $dom->save($xml_file_name);
        echo $dom->saveXML($dom->documentElement);

        exit;
    }

    public function export_cvsAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        if (!$id)
            redirect('panel/vacancies');

        Model::import('panel/vacancy_applications');
        $this->view->list = Vacancy_applicationsModel::getWhere("`vacancy_id` = '$id'");

        if (count($this->view->list) > 0) {
            $files = [];
            foreach ($this->view->list as $k => $obj) {
                if (file_exists(_SYSDIR_ . 'data/cvs/' . $obj->cv)) {
                    $files['cv-' . $obj->email . '-' . $obj->time . $k . '.' . File::format($obj->cv)] = _SYSDIR_ . 'data/cvs/' . $obj->cv;
                }
            }

            $result = $this->createZip($files);

            if (!$result)
                Request::returnError('Download Error');
            else
                Request::addResponse('func', 'downloadFile', _SITEDIR_ . 'data/export/cvs.zip');

        } else
            Request::returnError('No CV\'s for this vacancy');

        Request::addResponse('func', 'removeLoader');
        Request::endAjax();
    }

    private function createZip($files)
    {
        if (!file_exists(_SYSDIR_ . 'data/export')) {
            mkdir(_SYSDIR_ . 'data/export', 0777, true);
        }

        $zip = new ZipArchive;

        if ($zip->open(_SYSDIR_ . 'data/export/cvs.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
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

    public function mediaAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = ShopsModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/shops'));

        $media = Model::fetchAll(Model::select('shop_products_media', "`product_id` = {$this->view->edit->id}"));
        $this->view->videos = [];
        $this->view->images = [];

        foreach ($media as $item) {
            if ($item->type == 'video') {
                $this->view->videos[] = $item;
            } else {
                $this->view->images[] = $item;
            }
        }

        if ($this->startValidation()) {
//            $this->validatePost('title', 'Job Title', 'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $images = post('images') ?: [];
                //get all images from DB and check
                $dbImages = Model::fetchAll(Model::select('shop_products_media', "`product_id` = {$this->view->edit->id} AND `type` = 'image'"));
                foreach ($dbImages as $image) {
                    if (!in_array($image->image, $images)) {
                        File::remove('data/shop/' . $image->image);
                    }
                }

                Model::delete('shop_products_media', " `product_id` = {$this->view->edit->id} AND `type` = 'image'");
                if ($images) {
                    foreach ($images as $image) {

                        if (!File::copy('data/tmp/' . $image, 'data/shop/' . $image))
                            Request::returnError('Image copy error: ' . error_get_last()['message']);

                        Model::insert('shop_products_media', [
                            'product_id' => $this->view->edit->id,
                            'media' => $image,
                            'type' => 'image'
                        ]);
                    }
                }


                Model::delete('shop_products_media', " `product_id` = {$this->view->edit->id} AND `type` = 'video'");
                if ($videos = post('videos')) {
                    foreach ($videos as $video) {
                        Model::insert('shop_products_media', [
                            'product_id' => $this->view->edit->id,
                            'media' => $video,
                            'type' => 'video'
                        ]);
                    }
                }


                Request::addResponse('func', 'noticeSuccess', 'Saved');
                Request::endAjax();

            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Product Media');
    }
}


/* End of file */
