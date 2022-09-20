// Links and Urls

    {URL:page/edison} - for build url
    {LINK:ed} - for use link from Route::set('edison', 'page@edison')->name('ed');
    <?= url('edison/edison') ?>


// Thumbnail for image

    File::resize(_SYSDIR_ . 'data/blog/' . $data['image'], _SYSDIR_ . 'data/blog/mini_' . $data['image'], 400, 300);


// contentElement - tag, to create editable content field in panel

    <contentElement name="client-title" type="input" maxlength="270">For clients</contentElement>
    name = unique name on this page [^a-z0-9_-]
    type = [input|textarea], default = textarea
    maxlength = number of max length


// imageElement

    <imageElement name="seeking-image" src="<?= _SITEDIR_ ?>public/images/seeking-1.jpg" height="794" width="609" alt="Test img"/>
    <imageElement name="seeking-image" src="<?= _SITEDIR_ ?>public/images/seeking-1.jpg" height="794" width="609" alt="Test img" class="test-com" data-aos=""/>


// getImageElement:

    <img src="<?= getImageElement('image-name', _SITEDIR_ . 'public/images/bl-pic.jpg', 0, 609, 794); ?>" alt="">


// getVideoElement:

    // Video by file
    
        <video width="750" height="500" controls="controls" poster="">
            <source src="<?= getVideoElement('test-video-3-3', _SITEDIR_ . 'public/videos/test.mp4', 0, 'file'); ?>">
        </video>
    
    // Video by link
    
        <iframe width="760" height="400" src="<?= getVideoElement('youtube-test', 'https://www.youtube.com/embed/lM02vNMRRB0') ?>" 
        frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>


// getFileElement:

        <a href="<?= getFileElement('test-file', _SITEDIR_ . 'public/files/test.docx') ?>" download>Download File</a>


// Image uploader

    <div class="col half_column_right">
        <label for="image">Image 590x390 px<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>

        <div class="choose-file modern">
            <input type="hidden" name="image" id="image" value="<?= post('image', false, $this->edit->image); ?>">
            <input type="file" accept="image/jpeg,image/png,image/gif" onchange="initFile(this);
            load('panel/upload_image_crop/', 'name=<?= randomHash(); ?>', 'field=#image', 'width=590', 'height=390');">
            <a class="file-fake"><i class="fas fa-folder-open"></i>Choose image</a>

            <div id="preview_image" class="preview_image">
                <img src="<?= _SITEDIR_ ?>data/learning_development/<?= post('image', false, $this->edit->image); ?>" alt="">
            </div>
        </div>
    </div>

    // 'name=<?= randomHash(); ?>' - set name of uploaded img <br>
    // 'field=#image' - return and set filename in #image <br>
    // 'width=590', 'height=390' - will turn ON aspectRatio in crop box


// Pagination normal / ajax

    <?php echo Pagination::printPagination('blogs', ['act' => 123]); ?>

    <?php echo Pagination::ajax('blogs/ajax', ['act' => 123]); ?>


// Ajax only

    Request::ajaxOnly();
    

// Add response

    Request::addResponse('html', '#popup', $this->getView());


// Model import

    Model::import('tournaments');
    TournamentsModel::getUser(1);
    
    
// Scroll to

    scrollToEl('#profile|350');  // [element] | [scroll time]


// Cache data

    $time = SettingsModel::get('statistics_time');
    if (intval($time) + 3600 > time()) {
        $data = json_decode(File::read(_SYSDIR_ . 'data/cache/statistics.json'));
    } else {
        $data = [...];

        File::write(File::mkdir('data/cache/') . 'statistics.json', json_encode($data)); // reFilter() if needed
        SettingsModel::set('statistics_time', time());
    }
    

// Email sending

    $mail = new Mail;
    $mail->initDefault('Vacancy Application', $this->getView('modules/jobs/views/email_templates/apply_now.php'));
    $mail->AddAddress($consultant->email);
    $mail->AddAttachment(_SYSDIR_ . 'data/cvs/' . $data['cv'], 'CV.' . File::format($data['cv']));
    $mail->sendEmail('apply_vacancy', $data['vacancy_id']);


// Return error now and exit;

    Request::returnError('Wrong tool!');


// Return errors in validation

    Request::addError('f_password', 'Incorrect password');

    if (Request::isError())
        Request::endAjax();


// Re-init database (for example to connect remote DB)

    Model::reInit('83.223.113.113', 'gilbertmeher_new', 'z-tDxEzpaJ?g', 'gilbertmeher_new');


// Model::insert return errno

    $resultError = ToolsModel::insert('tools', $data);
    $insertID = Model::insertID();
    
    if (!$resultError && $insertID > 0) {
        ...
    }


// Using form validation & create row in table

    if ($this->startValidation()) {
        //required|trim|min_length[1]|max_length[100]|min[1]|max[10] 
        $this->validatePost('firstname', 'First Name', 'required|trim|min_length[1]|max_length[100]');
        $this->validatePost('email',     'Email',      'required|trim|email');
    
        if ($this->isValid()) {
            $data = array(
                'firstname' => post('firstname'),
                'email'     => post('email')
            );
    
            $result   = Model::insert('users', $data); // Insert row
            $insertID = Model::insertID();
    
            if (!$result && $insertID) {
                Request::addResponse('redirect', false, url('panel', 'team', 'edit', $insertID));
            } else {
                Request::returnError('Database error');
            }
        } else {
            if (Request::isAjax())
                Request::returnErrors($this->validationErrors);
        }
    }

// Relationships between tables. 
 1 - data of the first table;
 2 - name of the first table;
 3 - name of the second table;
 4 - return fields for second table;
 5 - search ids;
 6 - custom field name for 1-to-many relationship;
 7 - relationship type (one_to_many, many_to_many); 

Many to many

    $vacancies = Model::relationship($vacancies, 'vacancies', 'locations', ['id', 'name'], $searchLocationIds)

One to many

    $vacancies = Model::relationship($vacancies, 'vacancies', 'microsites', ['id', 'ref', 'title'], false, false,'one_to_many');

One to many with custom filed name

    $vacancies = Model::relationship($vacancies, 'vacancies', 'users', ['id', 'firstname', 'lastname'], false, 'consultant_id', 'one_to_many');

