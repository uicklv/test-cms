<?php

class PanelController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $time = SettingsModel::get('statistics_time');
        if (intval($time) + 3600 > time()) {
            $this->view->statistics = json_decode(File::read(_SYSDIR_ . 'data/cache/statistics.json'));
        } else {
            Model::import('panel/settings/dashboard');
            $this->view->statistics = DashboardModel::getAll('active', 12, SettingsModel::get('dashboard_sort'));

            foreach ($this->view->statistics as $item) {
                // preparing an array with days as keys
                $item->data = [];
                for ($i = time() - 9 * 24 * 3600; $i <= time(); $i += 24 * 3600)
                    $item->data[date("d.m", $i)] = 0;

                // get data for statistic item
                $where = reFilter($item->where) ? reFilter($item->where) . " AND " : null;
                $field = '`time`';
                if ($item->table == 'users' OR $item->table == 'candidates')
                    $field = '`reg_time`';
                $where .= " $field > " . (time() - 10 * 24 * 3600);
                $data = Model::fetchAll(Model::select($item->table, $where));

                // count the number of entities every day
                foreach ($data as $value) {
                    $time = $value->time;
                    if ($item->table == 'users' OR $item->table == 'candidates')
                        $time = $value->reg_time;
                    $item->data[date("d.m", $time)]++;
                }
            }

            File::write(File::mkdir('data/cache/') . 'statistics.json', (json_encode($this->view->statistics))); // reFilter
            SettingsModel::set('statistics_time', time());
        }


        // Latest vacancies
        Model::import('panel/vacancies');
        $this->view->vacancies = VacanciesModel::getLatest(12);


        // Cache charts
        $cacheTime = 3*3600;
        $time = SettingsModel::get('ga_new_users_time');
        if (intval($time) + $cacheTime > time())
            $this->view->ga1 = File::read(_SYSDIR_ . 'data/cache/ga_new_users.json');
        $time = SettingsModel::get('ga_devices_time');
        if (intval($time) + $cacheTime > time())
            $this->view->ga2 = File::read(_SYSDIR_ . 'data/cache/ga_devices.json');
        $time = SettingsModel::get('ga_country_time');
        if (intval($time) + $cacheTime > time())
            $this->view->ga3 = File::read(_SYSDIR_ . 'data/cache/ga_country.json');
        $time = SettingsModel::get('ga_sources_time');
        if (intval($time) + $cacheTime > time())
            $this->view->ga4 = File::read(_SYSDIR_ . 'data/cache/ga_sources.json');
        $time = SettingsModel::get('ga_top_time');
        if (intval($time) + $cacheTime > time())
            $this->view->ga5 = File::read(_SYSDIR_ . 'data/cache/ga_top.json');
        $time = SettingsModel::get('ga_bounceRate_time');
        if (intval($time) + $cacheTime > time())
            $this->view->ga6 = File::read(_SYSDIR_ . 'data/cache/ga_bounceRate.json');
        $time = SettingsModel::get('ga_avgSessionDuration_time');
        if (intval($time) + $cacheTime > time())
            $this->view->ga7 = File::read(_SYSDIR_ . 'data/cache/ga_avgSessionDuration.json');

        Request::setTitle('Dashboard');
    }

    /* --- Login --- */

    public function loginAction()
    {
        if ($this->startValidation() || $this->startValidation('get')) {
            $email = $this->validatePost('email',       'Email',    'required|trim|email');
            $pass  = $this->validatePost('password',    'Password', 'required|trim|min_length[6]|max_length[32]');

            // Auto-login
            if (get('e') && get('p')) {
                $email = get('e');
                $pass = get('p');
            }

            $user = PageModel::getUserByEmail($email);

            if ($user && $user->deleted == 'yes') {
                $this->addError('password', 'User was deleted');
            } else if ($user && ($user->password == md5($pass) OR $user->password == $pass) && ($user->role == 'admin' OR $user->role == 'moder')) { // Check password

                $token = PageModel::createSession($user->id); // Generate session and add to `users_session`
                User::setTokenCookie($token); // Set session in cookies(token)

                redirectAny(get('url') ?: url('panel'));
            } else
                $this->addError('password', 'Invalid email and/or password. Please check your data and try again');
        }

        if ($this->isErrors())
            $this->view->errors = $this->getErrors();

        $this->setLayout('layout_panel_login');
        Request::setTitle('Login');
    }

    public function logoutAction()
    {
        PageModel::closeSession(User::getTokenCookie());
        User::set(null);
        User::setTokenCookie(null);

        redirectAny(url('/panel'));
    }

    public function restore_passwordAction()
    {
        if ($this->startValidation()) {
            Request::ajaxPart();
            $this->validatePost('email',   'Email',    'required|trim|email');

            if ($this->isValid()) {
                $user = PageModel::getUserByEmail(post('email'));
                if (!$user)
                    Request::returnError('This email does not exist');

                //generate hash and update user row
                $this->view->email = $user->email;
                $this->view->hash = md5($user->email . time());
                Model::update('users', ['restore_token' => $this->view->hash], " `id` = $user->id");

                // Send email to admin
                require_once(_SYSDIR_.'system/lib/phpmailer/class.phpmailer.php');
                $mail = new PHPMailer;

                // Mail to client/consultant
                $mail->IsHTML(true);
                $mail->SetFrom(Request::getParam('noreply_mail'), Request::getParam('noreply_name'));
                $mail->AddAddress($user->email);


                $mail->Subject = 'Restore Password';
                $mail->Body = $this->getView('modules/panel/views/email_templates/restore_password.php');
                $mail->AltBody = 'Note: Our emails are a lot nicer with HTML enabled!';
                $mail->Send();

                Request::addResponse('html', '#restore_form', '<h3 class="title"><span>An email has been sent to the address you provided. Please check your inbox and junk mail folder.</span></h3>');
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        if ($this->isErrors())
            $this->view->errors = $this->getErrors();


        $this->setLayout('layout_panel_login');
        Request::setTitle('Restore Password');
    }

    public function restore_processAction()
    {
        $email = get('email');
        $hash = get('hash');
        $user = PageModel::getUserByEmail($email);

        //check hash
        if ($user->restore_token !== $hash)
            $this->view->errors = 'Hash is invalid';

        if ($this->startValidation()) {
            Request::ajaxPart();
            $this->validatePost('password',      'Password',          'required|trim|password');
            $this->validatePost('password2',     'Confirm Password',  'required|trim|password');

            if (post('password') !== post('password2'))
                $this->addError('password', 'Passwords should match');

            if ($this->view->errors)
                $this->addError('hash', 'Hash is invalid');

            if ($this->isValid()) {
                Model::update('users', ['password' => md5(post('password')), 'restore_token' => ''], "`email` = '$user->email'"); // Update row

                Request::addResponse('html', '#restore_form', '<h3 class="title"><span>Password updated successfully</span></h3>');
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        $this->setLayout('layout_panel_login');
        Request::setTitle('Restore Password');
    }

    /**
     * Upload image for admin panel
     */

    public function select_imageAction()
    {
        Request::ajaxPart();

        $this->view->path     = post('path',      true, 'tmp'); // path where image will be saved, default: 'tmp'
        $this->view->field    = post('field',     true, '#image'); // field where to put image name after uploading
        $this->view->preview  = post('preview',   true, '#preview_image'); // field where to put image name after uploading
        $this->view->width    = post('width');
        $this->view->height   = post('height');
        $this->view->multiple = post('multiple');
        $this->view->images   = Model::fetchAll(Model::select('last_uploaded_images', " 1 ORDER BY `id` DESC LIMIT 30"));

        Request::addResponse('html', '#popup', $this->getView());
    }

    public function remove_select_imageAction()
    {
        Request::ajaxPart();
        $id = intval(Request::getUri(0));
        $image = Model::fetch(Model::select("last_uploaded_images", " `id` = $id"));

        if (!$image) return;

        Model::delete('last_uploaded_images', " `id` = $id");

        $this->view->path    = post('path',     true, 'tmp'); // path where image will be saved, default: 'tmp'
        $this->view->field   = post('field',    true, '#image'); // field where to put image name after uploading
        $this->view->preview = post('preview',  true, '#preview_image'); // field where to put image name after uploading
        $this->view->width   = post('width');
        $this->view->height  = post('height');
        $this->view->images  = Model::fetchAll(Model::select('last_uploaded_images', " 1 ORDER BY `id` DESC LIMIT 10"));

        Request::addResponse('html', '#popup', $this->getView('modules/panel/views/select_image.php'));
    }

    public function upload_image_cropAction()
    {
        Request::ajaxPart();

        $name = post('name'); // image name, if not set - will be randomly
        $this->view->path    = $path    = post('path',     true, 'tmp'); // path where image will be saved, default: 'tmp'
        $this->view->field   = $field   = post('field',    true, '#image'); // field where to put image name after uploading
        $this->view->preview = $preview = post('preview',  true, '#preview_image'); // field where to put image name after uploading
        $this->view->multiple = $multiple = post('multiple', true, 0);

        if (!$name) $name = randomHash();

        // todo:
        // display preview image in admin panel - minified, not full image

        if (!empty($_FILES) && post('type') !== 'image_from_tmp') {
            $data['new_name'] = $name;
            $data['path'] = 'data/' . $path . '/';

            $imgInfo = null;
            foreach ($_FILES as $file) {
                $data['new_format'] = File::format($file['name']);
                if ($data['new_format'] == 'png')
                    $data['check_transparency'] = true;

                $imgInfo = File::uploadImage($file, $data);

                if ($imgInfo['error'] == 8) {
                    Request::returnError('Too big file (max size 8mb)');
                } else if ($imgInfo['error'] == 10) {
                    $formatString = implode(', ', array_keys(File::$allowedImageFormats));
                    Request::returnError('Incorrect image format. Image files must be ' . $formatString . ' format');
                }
                break;
            }

            $this->view->format = 'jpg';
            if ($imgInfo['is_transparency'] === true)
                $this->view->format = 'png';

            $this->view->imagename = $newImageName = $data['new_name'] . '.' . $imgInfo['new_format']; // randomized name

            // Thumbnail of uploaded image
            if (post('select_image') == 'true') {
                Model::insert('last_uploaded_images', ['image' => $newImageName]);
                File::resize(_SYSDIR_ . 'data/tmp/' . $newImageName, _SYSDIR_ . 'data/tmp/mini_' . $newImageName, 86, 60);
            }
        } else {
            // If images selected from early uploaded
            $newImageName = $_POST['name'];
            $this->view->imagename = $name;
        }


        // Get image sizes
        list($imgWidth, $imgHeight) = getimagesize(_SYSDIR_ . 'data/' . $path . '/' . $this->view->imagename);

        // Image min size control
        if (post('width', 'int', 0) > $imgWidth || post('height', 'int', 0) > $imgHeight)
            Request::returnError('Image is too small (min width = ' . post('width') . ', min height = ' . post('height') . '; current width = ' . $imgWidth . ', current height = ' . $imgHeight . ')');

        // Size of crop img block
        list($CROP_BLOCK_W, $CROP_BLOCK_H) = [400, 400];
        // Crop Const(min resizing)
        list($CROP_BLOCK_W, $CROP_BLOCK_H) = cropImageRatio($CROP_BLOCK_W, $CROP_BLOCK_H, $imgWidth, $imgHeight);


        // Coefficient of image to crop box
        $hh = $imgHeight / $CROP_BLOCK_H;
        $ww = $imgWidth / $CROP_BLOCK_W;

        // Start x,y
        $this->view->default_x = 0;
        $this->view->default_y = 0;

        if (post('width') && post('height')) {
            $width = post('width'); // / $ww
            $height = post('height') ; // / $hh
            $this->view->ratio = $width / $height;
        } else {
            $this->view->ratio = false; // Turn off aspectRatio

            $width = $CROP_BLOCK_W;
            $height = $CROP_BLOCK_H;

            if ($width < $CROP_BLOCK_W)
                $this->view->default_x = (($imgWidth / $ww - $width) / 2);
            if ($height < $CROP_BLOCK_H)
                $this->view->default_y = (($imgHeight / $hh - $height) / 2);

            if ($width > $CROP_BLOCK_W)
                $this->view->default_x = (($imgWidth  - $width ) / 2);
            if ($height > $CROP_BLOCK_H)
                $this->view->default_y = (($imgHeight  - $height ) / 2);
        }

        $this->view->width = $width;
        $this->view->height = $height;
        $this->view->CROP_BLOCK_W = $CROP_BLOCK_W;
        $this->view->CROP_BLOCK_H = $CROP_BLOCK_H;

        if ($multiple) {
            $type = post('type_html');
            $this->view->image_id = time();
            $returnHtml = $this->prepare_image($field, $newImageName, $this->view->image_id, $type ?: 'panel');
            Request::addResponse('append', $preview, $returnHtml);

            if ($type === 'front')
                Request::endAjax();

        } else {
            Request::addResponse('val', $field, $newImageName);
            Request::addResponse('html', $preview, '<img src="' . _SITEDIR_ . 'data/tmp/' . $newImageName . '?t=' . time() . '" alt="">');
        }
        Request::addResponse('html', '#popup', $this->getView());

//        $testData = 'IMG width = ' . $imgWidth . '<br>'
//            . 'IMG height = ' . $imgHeight . '<br>'
//            . 'crop box width = ' . $width . '<br>'
//            . 'crop box height = ' . $height . '<br>'
//            . '$ww = ' . $ww . '<br>'
//            . '$hh = ' . $hh . '<br>'
//
//            . 'ratio = ' . $this->view->ratio . '<br>'
//
//            . '$CROP_BLOCK_W = ' . $CROP_BLOCK_W . '<br>'
//            . '$CROP_BLOCK_H = ' . $CROP_BLOCK_H . '<br>'
//
//            . 'default_x = ' . $this->view->default_x . '<br>'
//            . 'default_y = ' . $this->view->default_y . '<br>';
//        Request::addResponse('html', '.solo_test', $testData);
    }

    public function cropAction()
    {
        ini_set('memory_limit', '-1');

        Request::ajaxPart();

        $name      = post('name');
        $path      = post('path');
        $field     = post('field'); // field
        $preview   = post('preview'); // preview
        $quality   = post('quality');
        $newFormat = post('format');
        $multiple  = post('multiple');
        $image_id  = post('image_id');


        // imagejpeg // 0 - 100 (worst - best)
        // imagewebp // 0 - 100 (worst - best)
        // imagepng // 0 - 9 (no compress - worst)
        if ($quality > 100 || $quality < 0)
            $quality = 60;

        if ($newFormat == 'png') {
            $quality = abs($quality / 10 - 10);

            if ($quality > 9 || $quality < 0)
                $quality = 4;
        }


        // Get image sizes
        list($imgWidth, $imgHeight) = getimagesize(_SYSDIR_ . 'data/' . $path . '/' . $name);
        // Size of crop img block
        list($CROP_BLOCK_W, $CROP_BLOCK_H) = [400, 400];
        // Crop Const(min resizing)
        list($CROP_BLOCK_W, $CROP_BLOCK_H) = cropImageRatio($CROP_BLOCK_W, $CROP_BLOCK_H, $imgWidth, $imgHeight);


        // Coefficient of image to crop box
        $hh = $imgHeight / $CROP_BLOCK_H;
        $ww = $imgWidth / $CROP_BLOCK_W;

        $x1 = $_POST['x1'] * $ww; // X coordinate of left top corner
        $y1 = $_POST['y1'] * $hh; // Y coordinate of left top corner
        $w  = $_POST['w'] * $ww; // Width of cropped part
        $h  = $_POST['h'] * $hh; // Height of cropped part


        // Create a new image from file
        $format = File::format($name);

        if ($format == 'jpg')
            $imageCreateFrom = 'imagecreatefromjpeg';
        else if (array_key_exists($format, File::$allowedImageFormats))
            $imageCreateFrom = 'imagecreatefrom' . $format;

        $current_image = $imageCreateFrom(_SYSDIR_ . 'data/' . $path . '/' . $name); //sys dir


        // Create new image
        $new = imageCreateTrueColor($w, $h);

        // Alpha channel
        if ($format == 'png' OR $format == 'gif') {
            // integer representation of the color black (rgb: 0,0,0)
            $background = imagecolorallocate($new , 0, 0, 0);
            // removing the black from the placeholder
            imagecolortransparent($new, $background);

            // turning off alpha blending (to ensure alpha channel information
            // is preserved, rather than removed (blending with the rest of the image in the form of black))
            imagealphablending($new, false);

            // turning on alpha channel information saving (to ensure the full range of transparency is preserved)
            imagesavealpha($new, true);
        }

        // Copy and resize part of an image with resampling
        imagecopyresampled($new, $current_image, 0, 0, $x1, $y1, $w, $h, $w, $h);


        $newFilename = 'crop_' . randomHash() . '.' . $newFormat; // Cropped image name

        $imagePrint = 'image' . $newFormat;
        if ($newFormat == 'jpg')
            $imagePrint = 'imageJpeg';

        $imagePrint($new, _SYSDIR_ . 'data/tmp/' . $newFilename, $quality); // Output image to file
        imageDestroy($current_image); // Destroy an image

        if ($multiple) {
            $type = post('type_html');
            $returnHtml = $this->prepare_image($field, $newFilename, $image_id, $type ?: 'panel');
            Request::addResponse('delete', '#image_block_' . $image_id);
            Request::addResponse('append', $preview, $returnHtml);
        } else {
            Request::addResponse('val', $field, $newFilename);
            Request::addResponse('html', $preview, '<img src="' . _SITEDIR_ . 'data/tmp/' . $newFilename . '?t=' . time() . '" alt="">');
        }

        Request::addResponse('func', 'closePopup', false);
    }

    private function prepare_image($field, $newImageName, $image_id, $type = 'panel')
    {
        if ($type === 'panel') {
            $image = '<div id="image_block_' . $image_id . '">';
            $image .= '<img id="' . $image_id . '" src="' . _SITEDIR_ . 'data/tmp/' . $newImageName . '?t=' . time() . '" alt="" height="50px" class="ml-2">';
            $image .= '<input type="hidden" id="hidden_' . $image_id . '" name="' . $field . '" value="' . $newImageName . '">';
            $image .= '<span class="img_del" onclick="removeFieldImage(\'' . $image_id . '\')"><span class="fa fa-times-circle-o"></span></span>';
            $image .= '</div>';
        } else {
            $image = '<div class="uploaded-file" id="image_block_' . $image_id . '">';
            $image .= '<img id="' . $image_id . '" src="' . _SITEDIR_ . 'data/tmp/' . $newImageName . '?t=' . time() . '" alt="">';
            $image .= '<input type="hidden" id="hidden_' . $image_id . '" name="' . $field . '" value="' . $newImageName . '">';
            $image .= '<span class="btn-remove" onclick="removeFieldImage(\'' . $image_id . '\')"></span>';
            $image .= '</div>';
        }

        return $image;
    }

    // To upload image without crop
    public function upload_imageAction()
    {
        ini_set('memory_limit', '-1');
        Request::ajaxPart(); // if not Ajax part load

        $name = post('name'); // image name, if not set - will be randomly
        $path = post('path', true, 'tmp'); // path where image will be saved, default: 'tmp'
        $field = post('field', true, '#image'); // field where to put image name after uploading
        $preview = post('preview', true, '#preview_image'); // field where to put image name after uploading

        if (!$name) $name = randomHash();

        $data['path'] = 'data/' . $path . '/';
        $data['new_name'] = $name;
        $data['new_format'] = 'png';

        $imgInfo = null;
        foreach ($_FILES as $file) {
            $imgInfo = File::uploadImage($file, $data);

            if ($imgInfo['error'] == 8)
                Request::returnError('Too big file (max size 8mb)');
            else if ($imgInfo['error'] == 10) {
                $formatString = implode(', ',array_keys(File::$allowedImageFormats));
                Request::returnError('Incorrect image format. Image files must be ' . $formatString . ' format');
            }
            break;
        }

        $imageName = $imgInfo['new_name'] . '.' . $imgInfo['new_format']; // new name & format

        Request::addResponse('val', $field, $imageName);
        Request::addResponse('html', $preview, '<img src="' . _SITEDIR_ . 'data/tmp/' . $imageName . '?t=' . time() . '" alt="">');
    }

    /**
     * Upload video
     */
    public function uploadVideoAction()
    {
        Request::ajaxPart(); // if not Ajax part load

        $name    = post('name', true); // file name, if not set - will be randomly
        $path    = post('path', true, 'tmp'); // path where file will be saved, default: 'tmp'
        $field   = post('field', true, '#image'); // field where to put file name after uploading
        $preview = post('preview', true, '#preview_file'); // field where to put file name after uploading

        $path = 'data/' . $path . '/';

        $result = null;
        foreach ($_FILES as $file) {
            $result = File::uploadVideo($file, $path, $name);

            if (File::$error == 'WRONG_SIZE')
                Request::returnError('File size is too big, maximum size:' . format_bytes(File::$allowedFileSize, 0));
            if (File::$error == 'WRONG_FORMAT')
                Request::returnError('Wrong format. Video files must be ' .  implode(', ', array_keys(File::$allowedVideoFormats)) . ' format.');

            break;
        }

        $newFileName = $result['name'] . '.' . $result['format']; // randomized name

        Request::addResponse('val', $field, $newFileName);
        Request::addResponse('html', $preview, $result['fileName']);
    }

    /**
     * Upload file
     */
    public function uploadAction()
    {
        Request::ajaxPart(); // if not Ajax part load

        $name    = post('name', true); // file name, if not set - will be randomly
        $path    = post('path', true, 'tmp'); // path where file will be saved, default: 'tmp'
        $field   = post('field', true, '#image'); // field where to put file name after uploading
        $preview = post('preview', true, '#preview_file'); // field where to put file name after uploading

        $path = 'data/' . $path . '/';

        $result = null;
        foreach ($_FILES as $file) {
            $result = File::uploadFile($file, $path, $name);
            break;
        }

        $newFileName = $result['name'] . '.' . $result['format']; // randomized name

        Request::addResponse('val', $field, $newFileName);
        Request::addResponse('html', $preview, $result['fileName']);
    }

    /* --- Logs --- */

    public function logsAction()
    {
        if ($this->startValidation()) {
            $act = Request::getUri(0);

            switch ($act) {
                case 'email_logs':
                    Model::delete('email_logs', "`id` > 0");
                    break;

                case 'user_logs':
                    Model::delete('actions_logs', "`id` > 0");
                    break;

                default:
                    Model::delete('logs', "`id` > 0");
            }

            redirectAny(url('panel/logs'));
        }

        $this->view->list = Model::select('logs', "1 ORDER BY `id` DESC LIMIT 40");
        $this->view->list_email = Model::select('email_logs', "1 ORDER BY `id` DESC LIMIT 40");
        $this->view->list_user = PanelModel::getUserLogs();
        Request::setTitle('Logs');
    }

    /* --- Export DB --- */

    public function dbAction()
    {
        $queryTables = Model::query('SHOW TABLES');

        while ($row = Model::fetch($queryTables, 'row'))
            $tablesArray[] = $row[0];

        // remove big tables
        if ($rk = array_search('postcodelatlng', $tablesArray))
            unset($tablesArray[$rk]);

        foreach ($tablesArray as $table) {
            $result = Model::query('SELECT * FROM ' . $table);
            $fieldsAmount = $result->field_count;
            $rows_num = Model::affected_rows();
            $tableCode = Model::fetch(Model::query('SHOW CREATE TABLE ' . $table), 'row');

            $content = (!isset($content) ? '' : $content) . "\n " . $tableCode[1] . ";\n\n";

            for ($i = 0, $st_counter = 0; $i < $fieldsAmount; $i++, $st_counter = 0) {
                while ($row = Model::fetch($result, 'row')) { // when started (and every after 100 command cycle):
                    if ($st_counter % 100 == 0 || $st_counter == 0)
                        $content .= "\nINSERT INTO `" . $table . "` VALUES";

                    $content .= "\n(";
                    for ($j = 0; $j < $fieldsAmount; $j++) {
//                        $row[$j] = str_replace("\n", "\\n", addslashes($row[$j]));
//                        $row[$j] = filter($row[$j]);
                        if (is_null($row[$j]))
                            $content .= 'NULL';
                        else if (is_numeric($row[$j]))
                            $content .= $row[$j];
                        else if (isset($row[$j]))
                            $content .= "'" . filter($row[$j]) . "'";
                        else
                            $content .= "''";

                        if ($j < ($fieldsAmount - 1))
                            $content .= ', ';
                    }
                    $content .= ")";

                    // close every 100 lines or at the end
                    if ((($st_counter + 1) % 100 == 0 && $st_counter != 0) || $st_counter + 1 == $rows_num)
                        $content .= ";";
                    else
                        $content .= ",";

                    $st_counter = $st_counter + 1;
                }
            }
            $content .= "\n\n";
        }

        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: Binary');
        header(sprintf('Content-disposition: attachment; filename="%s.sql"', DB_NAME));
        echo $content;
        exit;
    }

    /* --- Export Data --- */


    private function createDataZip() {

        $rootPath = realpath(_SYSDIR_ . 'data/');

        $filename = "data.zip";
        $zipFilepath = _SYSDIR_ . "data/_dump/$filename";
        $zipFilepathRelative = _SITEDIR_ . "data/_dump/$filename";

        $zip = new ZipArchive();

        if (!file_exists(_SYSDIR_ . 'data/_dump'))
            mkdir( _SYSDIR_ . 'data/_dump');

        if ($zip->open("$zipFilepath", ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE)
            Request::returnError('Zip Error');

        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $file)
        {
            if (!$file->isDir())
            {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                if (!preg_match('/^_dump/', $relativePath) && !preg_match('/^tmp/', $relativePath))
                    $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
        return $zipFilepathRelative;
    }

    public function dataAction()
    {
        Request::ajaxPart();
        $filepath = $this->createDataZip();

        Request::addResponse('func', 'downloadFile', $filepath);
        Request::endAjax();
    }

    /* --- Modules --- */

    public function modulesAction()
    {
        $this->view->list = Model::select('modules', "1 ORDER BY `id` DESC");
        Request::setTitle('Modules');
    }

    public function modules_editAction()
    {
        Request::ajaxPart();

        $id = Request::getUri(0);
        $this->view->edit = Model::fetch(Model::select("modules", " `id` = '$id' LIMIT 1"));

        if (!$this->view->edit)
            redirect(url('panel/modules'));

        if ($this->startValidation()) {
            $this->validatePost('version', 'Version', 'trim|min[0]');

            if ($this->isValid()) {
                $result = Model::update(
                    'modules',
                    ['version' => intval(post('version', 'int')), 'visible' => post('visible')],
                    "`id` = '$id'"
                ); // Update row

                if ($result) {
                    Request::addResponse('func', 'closePopup();');
                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::addResponse('html', '#popup', $this->getView());
    }

    public function sort_vacanciesAction()
    {
        Request::ajaxPart();

        $field = post('field');

        Model::import('panel/vacancies');
        $this->view->vacancies = VacanciesModel::getSortedByField($field);

        Request::addResponse('html', '#vacancies_result', $this->getView('modules/panel/views/vacancies_list.php'));

    }

}
/* End of file */
