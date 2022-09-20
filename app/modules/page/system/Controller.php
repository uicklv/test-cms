<?php

class PageController extends Controller
{
    use Validator;

    public function indexAction()
    {
        Model::import('panel/videos');
        $this->view->video = VideosModel::getVideoByName('home-what-we-do');
        $this->view->video_home_tech = VideosModel::getVideoByName('home-tech-community');

        Request::setCanonical('/');
        Request::setImageOG( 'app/public/images/og.jpg');
    }

    public function edisonAction()
    {
        Request::setTitle('Edison');
        Request::setCanonical('edison');
    }

    public function specialismsAction()
    {
        $this->view->list = PageModel::getSectors();
        Request::setTitle('Specialisms');
    }

    public function locationsAction()
    {
        Model::import('panel/offices');
        $this->view->list = OfficesModel::getAll();
    }

    public function postcodeAction()
    {
        Request::ajaxPart();
        $postcode = post('postcode');
        Model::import('panel/offices');
        $this->view->postcodes = OfficesModel::getPostcodes($postcode);

        if (!count($this->view->postcodes))
            Request::addResponse('html', '.suggests_result', '<div class="st-msg" onclick="closeSuggest();">Code not found</div>');
        else
            Request::addResponse('html', '.suggests_result', $this->getView());
    }

    public function c_suiteAction()
    {
        Request::setTitle('C-suite Advisory');
    }

    public function tactical_solutionsAction()
    {
        Model::import('panel/videos');
        $this->view->video = VideosModel::getVideoByName('tactical-solutions');
        Request::setTitle('Tactical solutions for building teams');
    }

    public function operational_solutionsAction()
    {
        Request::setTitle('Operational solutions for scaling businesses');
    }

    public function servicesAction()
    {
        Model::import('panel/videos');
        $this->view->video = VideosModel::getVideoByName('services-video');
        Request::setTitle('Candidate Services');
    }

    public function tech_communityAction()
    {
        Model::import('panel/event_card');
        $this->view->events = Event_cardModel::getPublished();

        Model::import('panel/videos');
        $this->view->video = VideosModel::getVideoByName('tech-community');

        Request::setTitle('Tech Community');
    }

    public function lookingAction()
    {
        Model::import('panel/videos');
        $this->view->video_home_tech = VideosModel::getVideoByName('home-tech-community');
        Request::setTitle('Looking for Talent');
    }

    public function terms_and_conditionsAction()
    {
        Request::setTitle('Terms & Conditions');
    }

    public function privacy_policyAction()
    {
        Request::setTitle('Privacy Policy');
    }

    public function arrange_callAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $this->validatePost('name',             'Name',                 'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('company',          'Company',              'trim|min_length[1]|max_length[100]');
            $this->validatePost('email',            'Email',                'trim|email');
            $this->validatePost('tel',              'Contact Number',       'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('check',            'Checkbox',             'trim|min_length[1]');

            if ($this->isValid()) {
                $this->view->data = array(
                    'name'      => post('name'),
                    'company'   => post('company'),
                    'email'     => post('email'),
                    'tel'       => post('tel'),
                    'time'      => time()
                );

                // Mail to client/consultant
                $mail = new Mail;
                $mail->initDefault('Website call request', $this->getView('modules/page/views/email_templates/book_call.php'));
                $mail->AddAddress(Request::getParam('admin_mail'));
                $mail->sendEmail('booking_form');

                Request::addResponse('html', '.callback_form', '<h3 class="title"><span>Thank you!</span></h3>');
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::addResponse('html', '#popup', $this->getView());
    }

    public function refer_friendAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $this->validatePost('your_name',    'Your name',                    'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('your_email',   'Your email',                   'required|trim|min_length[1]|max_length[60]|email');
            $this->validatePost('your_tel',     'Your telephone number',        'required|trim|min_length[1]|max_length[20]');
            $this->validatePost('friend_name',  'Your friend\'s name',            'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('friend_email', 'Your friend\'s email',            'required|required|trim|min_length[1]|max_length[60]|email');
            $this->validatePost('friend_tel',   'Your friend\'s telephone number', 'required|trim|min_length[1]|max_length[20]');
            $this->validatePost('check',        'Privacy Policy',               'required|trim');

            if ($this->isValid()) {
                $this->view->data = array(
                    'your_name'    => post('your_name'),
                    'your_email'   => post('your_email'),
                    'your_tel'     => post('your_tel'),
                    'friend_name'  => post('friend_name'),
                    'friend_email' => post('friend_email'),
                    'friend_tel'   => post('friend_tel')
                );

                // Mail to client/consultant
                $mail = new Mail;
                $mail->initDefault('Refer a friend submission', $this->getView('modules/page/views/email_templates/refer_friend.php'));
                $mail->AddAddress(Request::getParam('admin_mail'));
                $mail->sendEmail('refer_friend_form');

                // todo: add email sender
                Request::addResponse('html', '.refer_form', '<h3 class="title"><span>Thank you!</span></h3>');
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::addResponse('html', '#popup', $this->getView());
    }

    public function upload_jobAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $this->validatePost('name',     'Your name',     'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('email',    'Your email',    'required|trim|min_length[1]|max_length[60]|email');
            $this->validatePost('company',  'Company',       'required|trim|min_length[1]|max_length[150]');
            $this->validatePost('tel',      'Telephone',     'required|trim|min_length[1]|max_length[20]');
            $this->validatePost('cv_field', 'CV',            'required|trim');
            $this->validatePost('check',    'Privacy Policy','required|trim');

            if ($this->isValid()) {
                $this->view->data = array(
                    'name'     => post('name'),
                    'email'    => post('email'),
                    'company'  => post('company'),
                    'tel'      => post('tel'),
                    'cv_field' => post('cv_field')
                );

                // Mail to client/consultant
                $mail = new Mail;
                $mail->initDefault('New Uploaded Vacancy', $this->getView('modules/page/views/email_templates/upload_job.php'));
                $mail->AddAddress(Request::getParam('admin_mail'));
                $mail->sendEmail('upload_vacancy_form');

                Request::addResponse('html', '.refer_job', '<h3 class="title"><span>Thank you!</span></h3>');
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::addResponse('html', '#popup', $this->getView());
    }

    public function get_connectedAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $this->validatePost('email', 'Email', 'required|trim|email');

            if ($this->isValid()) {
                $data = array(
                    'email' => post('email'),
                    'time'  => time()
                );

                $result   = Model::insert('subscribers', $data); // Insert row
                $insertID = Model::insertID();

                Request::addResponse('html', '.subs_res', '<h3 class="title"><span>Thank you!</span></h3>');
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }
    }

    public function search_locationsAction()
    {
        Request::ajaxPart();

        $name = post('name');

        Model::import('panel/offices');
        $this->view->list = OfficesModel::search($name);

        usort($this->view->list, function ($a, $b) {
            return $a->distance - $b->distance;
        });

        Request::addResponse('html', '#search_results_box', $this->getView());
    }

    public function get_locationAction()
    {
        Request::ajaxPart();

        $postcode = post('postcode');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.mapbox.com/geocoding/v5/mapbox.places/" . $postcode .".json?access_token=pk.eyJ1IjoidWlja2x2IiwiYSI6ImNrcXRldWc1cDFxaWoyeXFoMDR1NXQxZWkifQ.tUC3RHLi-2QVIRWwnSXoDg");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $office = curl_exec($ch);

        curl_close($ch);

        Request::addResponse('func', 'findRestaurant', $office);
        Request::endAjax();
    }

    public function get_office_by_idAction()
    {
        Request::ajaxPart();

        $id = post('id');

        Model::import('panel/offices');
        $office = OfficesModel::get($id);

        Request::addResponse('func', 'zoomOffice', json_encode($office));
        Request::endAjax();
    }

    public function resourcesAction()
    {
        Model::import('panel/resources');
        $this->view->list = ResourcesModel::getAllPublic(false, false, 6);
        $this->view->count = count(ResourcesModel::getAllPublic());

    }

    public function download_resourceAction()
    {
        Request::ajaxPart();

        $id = Request::getUri(0);
        Model::import('panel/resources');
        $resource = ResourcesModel::get($id);
        if (!$resource)
            redirectAny(url('resource-center'));

        $this->view->resource = $resource;

        if ($this->startValidation()) {
            $this->validatePost('firstname',       'First Name',           'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('lastname',        'Last Name',            'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('email',            'Email',                'required|trim|email');

            if ($this->isValid()) {

                $data = array(
                    'firstname'  => post('firstname'),
                    'lastname'   => post('lastname'),
                    'email'       => post('email'),
                    'resource_id' => $id,
                    'time'        => time()
                );

                $result  = Model::insert('resource_downloads', $data); // Insert row
                $insertId = Model::insertID();

                if (!$result && $insertId) {


                    // Mail to client/consultant
                    $mail = new Mail;
                    $mail->initDefault('New resource download', $this->getView('modules/page/views/email_templates/resource.php'));
                    $mail->AddAddress('hello@gmail.com');
                    $mail->sendEmail('resource download');


                    Request::addResponse('func', 'downloadFileNew', _SITEDIR_ .'data/resources/' .
                        $this->view->resource->file . '||' . makeSlug($this->view->resource->title) . '.' . File::format($this->view->resource->file));

                    setMyCookie('resource_' .  $this->view->resource->id, md5('diversify' . $this->view->resource->id), time() + 3600*24*30);

                    Request::addResponse('html', '#resource_form', '<h3 class="title-small">Thank you!</h3>');
                    Request::endAjax();
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }
        //check cookie, if cookie exist and correct  then download file else open popup
        $cookie = getCookie('resource_' . $this->view->resource->id);

        if ($cookie == md5('diversify' . $this->view->resource->id)) {
            Request::addResponse('func', 'downloadFileNew', _SITEDIR_ .'data/resources/' .
                $this->view->resource->file . '||' . makeSlug($this->view->resource->title) . '.' . File::format($this->view->resource->file));
        } else {
            Request::addResponse('html', '#popup', $this->getView('modules/page/views/download_resource.php'));
            Request::addResponse('attr', '#button-popup', "load('page/download_resource/" . $this->view->resource->id . "', 'form:#resource_form'); return false;", 'onclick');
        }
        Request::endAjax();



    }

    /**
     * access to file(CV) download (do not remove)
     */
    public function file_downloadAction()
    {
        if (User::getRole() !== 'admin')
            error404();

        $path_to_file = _SYSDIR_ . 'data/cvs/' . Request::getUri(0);

        if (file_exists($path_to_file)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);

            header('Content-Type: ' . finfo_file($finfo, $path_to_file));

            $finfo = finfo_open(FILEINFO_MIME_ENCODING);
            header('Content-Transfer-Encoding: ' . finfo_file($finfo, $path_to_file));
            header('Content-disposition: attachment; filename="' . basename($path_to_file) . '"');
            readfile($path_to_file);
        } else {
            error404();
        }
        exit;
    }

    /**
     * Email status (do not remove)
     * @return void
     */
    public function email_statusAction()
    {
        $token = get('token');

        Model::update('email_logs', ['status' => 'read'], "`token` = '$token'");

        $image = imagecreatefrompng('images/panel/px.png');
        header("Content-type: " . image_type_to_mime_type(IMAGETYPE_PNG));
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-cache, must-relative");
        imagegif($image);
        imagedestroy($image);
        exit;
    }

    public function uploadAction()
    {
        Request::ajaxPart(); // if not Ajax part load

        $name    = post('name', true); // file name, if not set - will be randomly
        $path    = post('path', true, 'tmp'); // path where file will be saved, default: 'tmp'
        $field   = post('field', true, '#image'); // field where to put file name after uploading
        $preview = post('preview', true, '#preview_file'); // field where to put file name after uploading
        $real_name = post('file_real_name', true); // field where to put file name after uploading

        $path = 'data/' . $path . '/';

        $result = null;
        foreach ($_FILES as $file) {
            $result = File::uploadCV($file, $path, $name);
            break;
        }

        $newFileName = $result['name'] . '.' . $result['format']; // randomized name

        Request::addResponse('val', $field, $newFileName);
        Request::addResponse('val', $real_name, str_replace(' ', '_', $result['fileName']));
        Request::addResponse('html', $preview, $result['fileName']);
    }

    /**
     * Upload image function for Editor.js
     */
    public function upload_imageAction()
    {
        $path = 'tmp';

        $name = randomHash();

        $data['path'] = 'data/' . $path . '/';
        $data['new_name'] = $name;
        $data['mkdir'] = true;

        $imgInfo = null;

        foreach ($_FILES as $file) {
            $imgInfo = File::uploadImage($file, $data);
            break;
        }

        $imageName = $imgInfo['new_name'] . '.' . $imgInfo['new_format'];

        $response = [
            'success' => 1,
            'file' => [
                'url' => _SITEDIR_ . $data['path'] . $imageName,
            ],
        ];

        echo json_encode($response);
        exit();
    }

    public function url_infoAction()
    {
        libxml_use_internal_errors(true);

        $url = get('url');

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $html = curl_exec($ch);
        curl_close($ch);

        $doc = new DomDocument();
        $doc->loadHTML($html);

        $xpath = new DOMXPath($doc);

        $query = '//*/meta[starts-with(@property, \'og:\')]';
        $metas = $xpath->query($query);

        $siteDetails = [];

        foreach ($metas as $meta) {
            $property = $meta->getAttribute('property');
            $content = $meta->getAttribute('content');

            $siteDetails[$property] = $content;
        }

        $response = [
            'success' => 1,
            'link' => $url,
            'meta' => [
                'title' => $siteDetails['og:title'],
                'site_name' => $siteDetails['og:site_name'],
                'description' => $siteDetails['og:description'],
                'image' => [
                    'url' => $siteDetails['og:image']
                ]
            ]
        ];

        echo json_encode($response);
        exit();
    }
}
/* End of file */
