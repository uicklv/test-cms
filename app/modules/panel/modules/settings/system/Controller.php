<?php
class SettingsController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->admin_mail     = SettingsModel::get('admin_mail');
        $this->view->favicon        = SettingsModel::get('favicon');
        $this->view->cms_logo       = SettingsModel::get('cms_logo');
        $this->view->title_prefix   = SettingsModel::get('title_prefix');
        $this->view->noreply_mail   = SettingsModel::get('noreply_mail');
        $this->view->noreply_name   = SettingsModel::get('noreply_name');
        $this->view->test_mode      = SettingsModel::get('test_mode');
        $this->view->test_mode_email= SettingsModel::get('test_mode_email');

        if ($this->startValidation()) {
            $this->validatePost('admin_mail',  'Admin email',  'trim|min_length[0]|max_length[50]');
            $this->validatePost('title_prefix','Title Prefix', 'trim|min_length[0]|max_length[50]');
            $this->validatePost('favicon',     'Favicon',      'trim|min_length[0]|max_length[50]');
            $this->validatePost('noreply_mail','Noreply email','trim|min_length[0]|max_length[50]');
            $this->validatePost('noreply_name','Noreply name', 'trim|min_length[0]|max_length[50]');

            if ($this->isValid()) {
                SettingsModel::set('title_prefix',      post('title_prefix'));
                SettingsModel::set('admin_mail',        post('admin_mail'));
                SettingsModel::set('noreply_mail',      post('noreply_mail'));
                SettingsModel::set('noreply_name',      post('noreply_name'));
                SettingsModel::set('test_mode',         post('test_mode'));
                SettingsModel::set('test_mode_email',   post('test_mode_email'));
                SettingsModel::set('favicon',           post('favicon'));
                SettingsModel::set('cms_logo',          post('cms_logo'));

                // Favicon image
                if (post('favicon') && ($this->view->favicon !== post('favicon'))) {
                    if (File::copy('data/tmp/' . post('favicon'), 'data/setting/' . post('favicon'))) {
                        File::remove('data/setting/' . $this->view->favicon);
                    } else
                        print_data(error_get_last());
                }

                // Cms logo
                if (post('cms_logo') && ($this->view->cms_logo !== post('cms_logo'))) {
                    if (File::copy('data/tmp/' . post('cms_logo'), 'data/setting/' . post('cms_logo'))) {
                        File::remove('data/setting/' . $this->view->cms_logo);
                    } else
                        print_data(error_get_last());
                }

                Request::addResponse('func', 'noticeSuccess', 'Saved');
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Settings');
    }

    public function remove_setting_imgAction()
    {
        Request::ajaxPart();

        $type = post('type');

        if ($type === 'cms_logo' || $type === 'favicon') {
            //$result = Model::delete('settings', " `name` = '$type'"); // remove row
        } else
            Request::endAjax();

//        if ($result) {
            if ($type === 'cms_logo') {
                Request::addResponse('val', '#cms_logo', '');
                Request::addResponse('html', '#cms_logo_preview', '<img src="' . _SITEDIR_ . 'assets/img/logo.png" alt="">');
            } else if ($type === 'favicon') {
                Request::addResponse('val', '#favicon', '');
                Request::addResponse('html', '#preview_image', '<img src="' . _SITEDIR_ . 'assets/img/favicon.png" alt="">');
            }

            Request::addResponse('func', 'noticeSuccess', 'Removed');
//        } else {
//            Request::returnError('Database error');
//        }
    }

    public function social_networksAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('facebook', 'Facebook', 'trim|min_length[1]');
            $this->validatePost('linkedin', 'LinkedIn', 'trim|min_length[1]');
            $this->validatePost('twitter',  'Twitter',  'trim|min_length[1]');
            $this->validatePost('instagram','Instagram','trim|min_length[1]');
            $this->validatePost('youtube',  'YouTube',  'trim|min_length[1]');
            //$this->validatePost('pinterest', 'Pinterest', 'trim|min_length[1]');
            $this->validatePost('og_image', 'Image',    'trim|min_length[1]|max_length[100]');

            if ($this->isValid()) {
                if (post('og_image') && post('og_image') !== SettingsModel::get('og_image')) {
                    if (File::copy('data/tmp/' . post('og_image'), 'data/og/' . post('og_image'))) {
                        File::remove('data/og/' . SettingsModel::get('og_image'));
                    } else {
                        Request::returnError('Image copy error: ' . error_get_last()['message']);
                    }
                }

                SettingsModel::set('facebook',  post('facebook'));
                SettingsModel::set('linkedin',  post('linkedin'));
                SettingsModel::set('twitter',   post('twitter'));
                SettingsModel::set('instagram', post('instagram'));
                SettingsModel::set('youtube',   post('youtube'));
                //SettingsModel::set('pinterest', post('pinterest'));
                SettingsModel::set('og_image',  post('og_image'));

                Request::addResponse('func', 'noticeSuccess', 'Saved');
                Request::endAjax();
            } else {
                if (Request::isAjax()) {
                    Request::returnErrors($this->validationErrors);
                }
            }
        }

        $this->view->facebook   = SettingsModel::get('facebook');
        $this->view->linkedin   = SettingsModel::get('linkedin');
        $this->view->twitter    = SettingsModel::get('twitter');
        $this->view->instagram  = SettingsModel::get('instagram');
        $this->view->youtube    = SettingsModel::get('youtube');
        //$this->view->pinterest  = SettingsModel::get('pinterest');
        $this->view->og_image   = SettingsModel::get('og_image');

        Request::setTitle('Social Links');
    }

    public function googleAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('maps_api_key',     'Maps API Key',         'trim|min_length[1]');

            $this->validatePost('site_key',         'reCaptcha Site Key',   'trim|min_length[1]');
            $this->validatePost('recaptcha_key',    'reCaptcha Secret Key', 'trim|min_length[1]');

            $this->validatePost('view_id',          'View ID',              'trim|min_length[1]');
            $this->validatePost('credentials_json', 'Credentials JSON',     'trim|min_length[1]');

            if ($this->isValid()) {
                SettingsModel::set('maps_api_key',              post('maps_api_key')); // Maps API key
                SettingsModel::set('recaptcha_key',             post('recaptcha_key')); // Secret key
                SettingsModel::set('site_key',                  post('site_key')); // Site key
                SettingsModel::set('analytics_view_id',         post('view_id')); // View ID
                SettingsModel::set('analytics_credentials_json',post('credentials_json')); // Credentials JSON

                Request::addResponse('func', 'noticeSuccess', 'Saved');
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        // Values
        $this->view->maps_api_key     = SettingsModel::get('maps_api_key');

        // recaptcha
        $this->view->recaptcha_key    = SettingsModel::get('recaptcha_key');
        $this->view->site_key         = SettingsModel::get('site_key');

        // analytics
        $this->view->view_id          = SettingsModel::get('analytics_view_id');
        $this->view->credentials_json = SettingsModel::get('analytics_credentials_json');

        Request::setTitle('Google Settings');
    }

    public function robotsAction()
    {
        $file = fopen('robots.txt', 'c+');
        if ($file)
            $content = File::read(_BASEPATH_ . 'robots.txt');

        $this->view->content = $content;

        if ($this->startValidation()) {
            $this->validatePost('text', 'Content', 'trim');

            if ($this->isValid()) {
                if (file_exists('robots.txt')) { // todo: use File::exist(_BASEPATH_ . 'robots.txt');
                    $content = str_replace(['\r', '\n'], ["\r", "\n"], post('text'));
                    File::write(_BASEPATH_ . 'robots.txt', $content);

                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                    Request::endAjax();
                } else
                    Request::returnError('file does not exist');

            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Robots');
    }

    public function cookieAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('content',         'Content',        'required|trim|min_length[1]');
            $this->validatePost('bg_color',        'BG Color',       'required|trim|max_length[20]');
            $this->validatePost('text_color',      'Text Color',     'required|trim|max_length[20]');
            $this->validatePost('button_color',    'Button Color',   'required|trim|max_length[20]');

            if ($this->isValid()) {
                SettingsModel::set('cookie_content',              post('content'));
                SettingsModel::set('cookie_enable',               intval(post('enable_popup', 'int')));
                SettingsModel::set('cookie_bg_color',             post('bg_color'));
                SettingsModel::set('cookie_text_color',           post('text_color'));
                SettingsModel::set('cookie_button_color',         post('button_color'));

                Request::addResponse('func', 'noticeSuccess', 'Saved');
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        $defaultContent = '<p>Our website use cookies. By continuing, we assume your permission to deploy cookies as detailed in our Privacy Policy.</p>';
        $this->view->cookie_content = SettingsModel::get('cookie_content') ?: $defaultContent;
        $this->view->enable_cookie  = SettingsModel::get('cookie_enable');
        $this->view->bg_color       = SettingsModel::get('cookie_bg_color') ?: '#fff';
        $this->view->text_color     = SettingsModel::get('cookie_text_color') ?: '#707070';
        $this->view->button_color   = SettingsModel::get('cookie_button_color') ?: '#1e72ef';

        Request::setTitle('Cookies Popup');
    }

    public function clear_tmpAction()
    {
        Request::ajaxPart();

        //remove all files and folders in dir
        clearFolder(_SYSDIR_ . 'data/tmp');
        // clear table with images
        Model::truncate('last_uploaded_images');

        Request::addResponse('func', 'noticeSuccess', 'Tmp folder cleared');
    }

    public function clear_temp_imagesAction()
    {
        //get last 30 images
        $lastImages = Model::fetchAll(Model::select('last_uploaded_images', " 1 ORDER BY `id` DESC LIMIT 30"));

        if (is_array($lastImages) && count($lastImages) > 0) {
            //get name for last images and create str for query
            $nameImages = [];
            $nameImagesMini = [];
            foreach ($lastImages as $image) {
                $nameImages[] = $image->image;
                $nameImagesMini[] = 'mini_' . $image->image;
            }
            $namesStr = "'" . implode("', '", $nameImages) . "'";

            Model::delete('last_uploaded_images', " `image` NOT IN (" . $namesStr . ")");

            //clearing the tmp folder except for the last 30 images
            foreach (glob(_SYSDIR_ . 'data/tmp/*') as $file) {
                if (is_dir($file)) {
                    clearFolder($file);
                } else {
                    //split the path to the file and get the file name
                    $filePathArray = explode('/', $file);
                    $fileName = $filePathArray[array_key_last($filePathArray)];
                    //check if the file needs to be deleted
                    if (!in_array($fileName, $nameImages) && !in_array($fileName, $nameImagesMini)) {
                        unlink($file);
                    }
                }
            }
        }

        exit;
    }

}
/* End of file */
