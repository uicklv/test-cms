<?php
/**
 * Class File
 */
class File
{
    /**
     * @var array
     */
    static public $allowedFileFormats = array(
        '3gp'   => true,
        '7z'    => true,
        'amr'   => true,
        'apk'   => true,
        'avi'   => true,
        'bat'   => true,
        'bmp'   => true,
        'css'   => true,
        'djvu'  => true,
        'doc'   => true,
        'docx'  => true,
        'exe'   => true,
        'flv'   => true,
        'gif'   => true,
        'html'  => true,
        'ini'   => true,
        'ipa'   => true,
        'jar'   => true,
        'jpeg'  => true,
        'jpg'   => true,
        'js'    => true,
        'midi'  => true,
        'mp3'   => true,
        'mp4'   => true,
        'pdf'   => true,
        'php'   => true,
        'png'   => true,
        'pps'   => true,
        'ppt'   => true,
        'pptx'  => true,
        'psd'   => true,
        'rar'   => true,
        'svg'   => true,
        'sxc'   => true,
        'tar'   => true,
        'txt'   => true,
        'wav'   => true,
        'webm'  => true,
        'wma'   => true,
        'xls'   => true,
        'xlsx'  => true,
        'xml'   => true,
        'zip'   => true,
        'csv'   => true
    );

    /**
     * @var array
     */
    static public $allowedImageFormats = array(
        'gif'   => true,
        'png'   => true,
        'jpg'   => true,
        'jpeg'  => true,
        'svg'   => true,
        'webp'  => true,
    );

    /**
     * @var array
     */
    static public $allowedDocFormats = array(
        'doc'   => true,
        'docx'  => true,
        'pdf'   => true,
        'txt'   => true,
        'fotd'  => true
    );

    /**
     * @var array
     */
    static public $allowedVideoFormats = array(
        'mp4'   => true,
        'avi'   => true,
        'mkv'   => true,
    );

    /**
     * @var int
     */
    //static public $allowedFileSize = 209715200; //200M
    static public $allowedFileSize = 15728640; //15M

    /**
     * @var
     */
    static public $error;


    /**
     * @param $path
     * @return bool
     */
    static public function exist($path)
    {
        if (file_exists(_SYSDIR_ . $path))
            return true;
        else
            return false;
    }

    /**
     * @param $file
     * @return false|string
     */
    static public function fileSize($file)
    {
        if (self::exist($file)) {
            $fileSize = filesize(_SYSDIR_ . $file);
            return format_bytes($fileSize);
        }

        return false;
    }

    /**
     * @param $name
     * @return false|string
     */
    static public function format($name)
    {
        $format = mb_strtolower($name);
        return mb_substr($format, mb_strrpos($format, '.') + 1);
    }

    /**
     * @param $path
     * @param $data
     * @return int
     */
    static public function write($path, $data)
    {
        return file_put_contents($path, $data);
    }

    /**
     * @param $path
     * @return string
     */
    static public function read($path)
    {
        return file_get_contents($path);
    }

    /**
     * Copy file
     * @param $filePath
     * @param $copyPath
     * @return bool
     */
    static public function copy($filePath, $copyPath)
    {
        $copyPath = trim($copyPath, '/');
        $array = explode('/', $copyPath);
        array_pop($array); // remove file name

        self::mkdir(implode('/', $array));
        return @copy(_SYSDIR_ . $filePath, _SYSDIR_ . $copyPath);
    }

    /**
     * @param $path
     * @return bool
     */
    static public function remove($path)
    {
        return @unlink(_SYSDIR_ . $path);
    }

    /**
     * function mkdir recursive
     * @param $path
     * @param int $chmod
     * @return string
     */
    static public function mkdir($path, $chmod = 0777)
    {
        $path = trim($path, '/');
        $array = explode('/', $path);

        $fullPath = _SYSDIR_;
        foreach ($array as $value) {
            $fullPath .= $value . '/';

            if (!is_dir($fullPath))
                @mkdir($fullPath, $chmod);
            @chmod($fullPath, $chmod);
        }

        return $fullPath;
    }

    /**
     * Translit filename
     * @param $text
     * @param false $case
     * @return false|string|string[]
     */
    static public function translit($text, $case = false)
    {
        $search = array(" ", "<", ">", "\"", "$", "&", "'", "Ё","Ж","Ч","Ш","Щ","Э","Ю","Я","ё","ж","ч","ш","щ","э","ю","я","А","Б","В","Г","Д","Е","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х","Ц","Ь","Ы","а","б","в","г","д","е","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ь","ы","Ґ","ґ","Ї","ї","І","і","Є","є");
        $replace = array("_", "",  "",  "", "", "", "", "Jo","Zh","Ch","Sh","Sch","Je","Jy","Ja","jo","zh","ch","sh","sch","je","jy","ja","A","B","V","G","D","E","Z","I","J","K","L","M","N","O","P","R","S","T","U","F","H","C","","Y","a","b","v","g","d","e","z","i","j","k","l","m","n","o","p","r","s","t","u","f","h","c","","y","G","g","I","i","I","i","E","e");

        if ($case == 'lower')
            return mb_strtolower(str_replace($search, $replace, $text));
        elseif ($case == 'upper')
            return mb_strtoupper(str_replace($search, $replace, $text));
        else
            return str_replace($search, $replace, $text);
    }

    /**
     * Function resize - for creating thumbnails of images, resizing, etc.
     * @param $fromPath
     * @param $toPath
     * @param int $minWidth
     * @param int $minHeight
     * @return false|string
     */
    public static function resize($fromPath, $toPath, $minWidth = 0, $minHeight = 0)
    {
        if (!is_file($fromPath))
            return false;

        // Get current width and height of image
        list($width, $height) = getimagesize($fromPath);

        // Min resizing
        if ($minHeight == 0 && $minWidth == 0) { // set current image values
            $targetHeight = $height;
            $targetWidth = $width;
        } else if ($minHeight != 0 && $minWidth == 0) {
            $targetHeight = $minHeight;
            $hw = round($width / $height, 6);
            $targetWidth = round($hw * $minHeight,0);
        } else if ($minHeight == 0 && $minWidth != 0) {
            $targetWidth = $minWidth;
            $hw = round($height / $width, 6);
            $targetHeight = round($hw * $minWidth, 0);
        } else if ($minHeight != 0 && $minWidth != 0) { // set defined values
            $targetHeight = $minHeight;
            $targetWidth = $minWidth;
        }


        // Ratio
        //if ($width < $targetWidth || $height < $targetHeight) return "Image is too small";

        $ratio = max($targetWidth / $width, $targetHeight / $height);
        $height = $targetHeight / $ratio;
        $x = ($width - $targetWidth / $ratio) / 2; // align by center
        $width = $targetWidth / $ratio;


        // Files name and formats
        $fileFromPath = explode('/', trim($fromPath, '/'));
        $fromFilename = $fileFromPath[count($fileFromPath)-1];

        $fileToPath = explode('/', trim($toPath, '/'));
        $toFilename = $fileToPath[count($fileToPath)-1];

        $format = File::format($fromFilename);
        $newFormat = File::format($toFilename);


        // Create new image
        if ($format == 'jpg')
            $imageCreateFrom = 'imagecreatefromjpeg';
        elseif (array_key_exists($format, File::$allowedImageFormats))
            $imageCreateFrom = 'imagecreatefrom' . $format;

        $img = $imageCreateFrom($fromPath); //sys dir

        // Create new true color image
        $screen = imagecreatetruecolor($targetWidth, $targetHeight);

        // Transparency for PNG
        if ($newFormat == 'png') {
            imagealphablending($screen, false); // Disable pairing colors
            imagesavealpha($screen, true); // Including the preservation of the alpha channel
        }

        // Copy image
        imagecopyresampled($screen, $img, 0, 0, $x, 0, $targetWidth, $targetHeight, $width, $height);
        if ($newFormat == 'jpg')
            $imagePrint = 'imageJpeg';
        else
            $imagePrint = 'image' . $newFormat;

        $status = $imagePrint($screen, $toPath);
        imageDestroy($img);

        return $status;
    }

    /**
     * Function get image size
     * @param $path
     * @param string $format
     * @return array
     */
    static public function imageSize($path, $format = 'png')
    {
        $data = array();

        if ($format == 'jpg')
            $imageCreateFrom = 'ImageCreateFromJpeg';
        else
            $imageCreateFrom = 'ImageCreateFrom' . $format;

        // Create resource image
        $img = $imageCreateFrom($path);

        $data['height'] = imagesy($img);
        $data['width'] = imagesx($img);

        return $data;
    }

    /**
     * Function uploadFile
     * @param $file
     * @param $path
     * @param $name
     * @return mixed
     */
    static public function uploadFile($file, $path, $name = false, &$data = array())
    {
        if (!$name)
            $name = randomHash();

        $format = mb_strtolower($file['name']);
        $format = mb_substr($format, mb_strrpos($format, '.') + 1);

        if (is_uploaded_file($file['tmp_name'])) {
            if ($file['size'] <= self::$allowedFileSize) {
                if (self::$allowedFileFormats[$format] === true) {
                    self::mkdir($path, 0777); // Recursive mkdir

                    if (copy($file['tmp_name'], _SYSDIR_ . $path . '/' . $name . '.' . $format)) {
                        $data               = array();
                        $data['fileName']   = $file['name'];
                        $data['name']       = $name;
                        $data['format']     = $format;
                        $data['size']       = $file['size'];
                        $data['path']       = _SYSDIR_ . $path;
                        $data['url']        = _SITEDIR_ . $path . $name . '.' . $format;
                        return $data;
                    } else {
                        self::$error = "Error while moving file to site storage.";
                        return false;
                    }
                } else {
                    self::$error = "WRONG_FORMAT";
                    return false;
                }
            } else {
                self::$error = "WRONG_SIZE";
                return false;
            }
        } else {
            self::$error = $file['error'];
            return false;
        }
    }

    /**
     * Function uploadCV
     * @param $file
     * @param $path
     * @param $name
     * @return mixed
     */
    static public function uploadCV($file, $path, $name = false)
    {
        if (!$name)
            $name = randomHash();

        $format = mb_strtolower($file['name']);
        $format = mb_substr($format, mb_strrpos($format, '.') + 1);

        if (is_uploaded_file($file['tmp_name'])) {
            if ($file['size'] <= self::$allowedFileSize) {
                if (self::$allowedDocFormats[$format] === true) {
                    self::mkdir($path);

                    if (copy($file['tmp_name'], _SYSDIR_ . $path . '/' . $name . '.' . $format)) {
                        // return data
                        $data = array();
                        $data['fileName']   = $file['name'];
                        $data['name']       = $name;
                        $data['format']     = $format;
                        $data['size']       = $file['size'];
                        $data['path']       = _SYSDIR_ . $path;
                        $data['url']        = _SITEDIR_ . $path . $name . '.' . $format;
                        return $data;
                    } else {
                        self::$error = "Error while moving file to site storage.";
                        return false;
                    }
                } else {
                    self::$error = "WRONG_FORMAT";

                    Request::returnError('Wrong file format. Accepted formats:' . implode(', ', array_keys(self::$allowedDocFormats)));
                    return false;
                }
            } else {
                self::$error = "WRONG_SIZE";

                Request::returnError('Wrong file size. Maximum file size -' . format_bytes(self::$allowedFileSize) .'. Current size - ' . format_bytes($file['size']));
                return false;
            }
        } else {
            self::$error = $file['error'];
            return false;
        }
    }

    /**
     * Function uploadVideo
     * @param $file
     * @param $path
     * @param false $name
     * @return array|false
     */
    static public function uploadVideo($file, $path, $name = false)
    {
        if (!$name)
            $name = randomHash();

        $format = mb_strtolower($file['name']);
        $format = mb_substr($format, mb_strrpos($format, '.') + 1);

        if (is_uploaded_file($file['tmp_name'])) {
            if ($file['size'] <= self::$allowedFileSize) {
                if (self::$allowedVideoFormats[$format] === true) {
                    self::mkdir($path);

                    if (copy($file['tmp_name'], _SYSDIR_ . $path . '/' . $name . '.' . $format)) {
                        // return data
                        $data               = array();
                        $data['fileName']   = $file['name'];
                        $data['name']       = $name;
                        $data['format']     = $format;
                        $data['size']       = $file['size'];
                        $data['path']       = _SYSDIR_ . $path;
                        $data['url']        = _SITEDIR_ . $path . $name . '.' . $format;
                        return $data;
                    } else {
                        self::$error = "Error while moving file to site storage.";
                        return false;
                    }
                } else {
                    self::$error = "WRONG_FORMAT";
                    return false;
                }
            } else {
                self::$error = "WRONG_SIZE";
                return false;
            }
        } else {
            self::$error = $file['error'];
            return false;
        }
    }

    /**
     * Function uploadImage
     * @param $file
     * @param array $data
     * @return array
     */
    static public function uploadImage($file, $data = array())
    {
        ini_set('memory_limit', '-1');

        /*
        "+" - (нужно указывать)
        "!" - (необязательно указывать)
        "-" - (не нужно указывать)

        $file - файл(+) / $_FILES['name']
        $data['path'] - путь загрузки(+) / 'app/public/'

        $data['new_name'] - новое имя(!) / 'name' else random md5 hash
        $data['new_format'] - новый формат загружаймого файла(! по умолчанию jpg) / 'png'
        $data['mkdir'] - создание пути(!) / true, false
        $data['check_transparency'] - проверка на transparency(!) / true, false

        $data['new_width'] - новая ширина(!)
        $data['new_height'] - новая высота(!)

        $data['format'] - формат загружаймого файла(-)
        $data['tmp_name'] - хранение tmp(-)
        $data['size'] - размер файла(-)
        $data['type'] - тип файла(-)
        $data['name'] - имя файла(-)
        $data['width'] - ширина картинки(-)
        $data['height'] - высота картинки(-)
        $data['error'] - код ошибки(-)
        */

        $data['error'] = 0;
        $data['format'] = self::format($file['name']);
        $data['tmp_name'] = $file['tmp_name'];
        $data['size'] = $file['size'];
        $data['type'] = $file['type'];
        $data['name'] = $file['name'];
        $data['originalPath'] = trim($data['path'], '/') . '/';
        $data['path'] = _SYSDIR_ . trim($data['path'], '/') . '/';

        $data['new_name'] = $data['new_name'] ?: randomHash();
        $data['new_format'] = $data['new_format'] ?: 'jpg';
        $data['mkdir'] = !($data['mkdir'] === false);

        if ($data['mkdir'] === true)
            self::mkdir($data['originalPath'], 0777); // Recursive mkdir

        if (self::$allowedImageFormats[$data['format']] !== true)
            $data['error'] = 10;

        if ($data['error'] != 0)
            return $data;


        // Create resource image
        $imageCreateFrom = 'imagecreatefrom' . $data['format'];
        if ($data['format'] == 'jpg')
            $imageCreateFrom = 'imagecreatefromjpeg';

        $image = $imageCreateFrom($data['tmp_name']);

        // Check transparency
        if ($data['check_transparency'] == true)
            $data['is_transparency'] = hasAlpha($image);


        $data['width']  = imagesx($image);
        $data['height'] = imagesy($image);
        $data['new_width'] = $data['width'];
        $data['new_height'] = $data['height'];


        // Resize if image bigger than 2880×1800
        $maxW = 2880;
        $maxH = 1800;
        if ($data['new_width'] > $maxW || $data['new_height'] > $maxH) {
            $ratio = min($maxW / $data['width'], $maxH / $data['height']);
            $data['new_width'] = round($data['width'] * $ratio, 0);
            $data['new_height'] = round($data['height'] * $ratio, 0);

            // Increase to requested size
            $cropW = post('width', 'int');
            $cropH = post('height', 'int');

            if ($cropW > $data['new_width']) {
                $rat = $data['new_width'] / $cropW;
                $data['new_width'] = round($data['new_width'] / $rat, 0);
                $data['new_height'] = round($data['new_height'] / $rat, 0);
            }
            if ($cropH > $data['new_height']) {
                $rat = $data['new_height'] / $cropH;
                $data['new_width'] = round($data['new_width'] / $rat, 0);
                $data['new_height'] = round($data['new_height'] / $rat, 0);
            }
        }


        $screen = imageCreateTrueColor($data['new_width'], $data['new_height']);

        // Alpha channel
        if ($data['format'] == 'png' OR $data['format'] == 'gif') {
            // integer representation of the color black (rgb: 0,0,0)
            $background = imagecolorallocate($screen , 0, 0, 0);
            // removing the black from the placeholder
            imagecolortransparent($screen, $background);

            // turning off alpha blending (to ensure alpha channel information
            // is preserved, rather than removed (blending with the rest of the image in the form of black))
            imagealphablending($screen, false);

            // turning on alpha channel information saving (to ensure the full range of transparency is preserved)
            imagesavealpha($screen, true);
        }

        imageCopyResampled($screen, $image, 0, 0, 0, 0, $data['new_width'], $data['new_height'], $data['width'], $data['height']);

        $imagePrint = 'image' . $data['new_format'];
        if ($data['new_format'] == 'jpg')
            $imagePrint = 'imageJpeg';

        $imagePrint($screen, $data['path'] . $data['new_name'] . '.' . $data['new_format']);
        imageDestroy($image);

        //$info = getImageSize($data['path'] . $data['new_name'] . '.' . $data['format']);
        //print_data($info);
        return $data;
    }


    /**
     * Function LoadImg
     * @param $file
     * @param array $data
     * @return array
     */
    static public function uploadImageOpt($file, $data = array())
    {
        ini_set('memory_limit', '-1');

        /*
        "+" - (нужно указывать)
        "!" - (необязательно указывать)
        "-" - (не нужно указывать)

        $file - файл(+) / $_FILES['name']
        $data['path'] - путь загрузки(+) / 'app/public/'

        $data['new_name'] - новое имя(!) / 'name' else random md5 hash
        $data['new_format'] - новый формат загружаймого файла(! по умолчанию jpg) / 'png'
        $data['resize'] - resize картинки(! по умолчанию 0) / 0 - no resize(сжать), 1 - обрезать не изменяя размеров, 2 - обрезать симетрически уменьшив
        $data['allowed_formats'] - разрешаемые форматы(!) / array('jpg' => true, 'gif' => false)
        $data['mkdir'] - создание пути(!) / true, false
        $data['check_transparency'] - проверка на transparency(!) / true, false
        $data['min_size'] - min размер(!)
        $data['max_size'] - max размер(!)
        $data['new_width'] - новая ширина(!)
        $data['new_height'] - новая высота(!)
        $data['min_width'] - min ширина(!)
        $data['min_height'] - min высота(!)
        $data['max_width'] - max ширина(!)
        $data['max_height'] - max высота(!)
        $data['ratio'] - коэффициент(!)

        $data['format'] - формат загружаймого файла(-)
        $data['tmp_name'] - хранение tmp(-)
        $data['size'] - размер файла(-)
        $data['type'] - тип файла(-)
        $data['name'] - имя файла(-)
        $data['width'] - ширина картинки(-)
        $data['height'] - высота картинки(-)
        $data['error'] - код ошибки(-)
        */

        $data['error'] = 0;
        $data['format'] = mb_strtolower(mb_substr($file['name'], mb_strrpos($file['name'], '.') + 1));
        $data['tmp_name'] = $file['tmp_name'];
        $data['size'] = $file['size'];
        $data['type'] = $file['type'];
        $data['name'] = $file['name'];
        $data['originalPath'] = trim($data['path'], '/') . '/';
        $data['path'] = _SYSDIR_ . trim($data['path'], '/') . '/';

        if (!$data['new_name'])
            $data['new_name'] = randomHash();
        if (!$data['new_format'])
            $data['new_format'] = 'jpg';
        if (!$data['resize'])
            $data['resize'] = 0;
        if (!$data['allowed_formats'])
            $data['allowed_formats'] = self::$allowedImageFormats;
        if (!$data['mkdir'])
            $data['mkdir'] = true;
        if ($data['mkdir'] === true)
            self::mkdir($data['originalPath'], 0777); // Recursive mkdir

        if ($data['allowed_formats'][$data['format']] !== true)
            $data['error'] = 10;

        if ($data['min_size'] && intval($data['min_size']) < $data['size'])
            $data['error'] = 20;

        if ($data['max_size'] && intval($data['max_size']) > $data['size'])
            $data['error'] = 30;

        // exit if incorrect format;
        if ($data['error'] == 10)
            return $data;

        // Image
        if ($data['format'] == 'jpg')
            $imageCreateFrom = 'imagecreatefromjpeg';
        else
            $imageCreateFrom = 'imagecreatefrom' . $data['format'];

        // Create resource image
        $image = $imageCreateFrom($data['tmp_name']);

        // Check transparency
        if ($data['check_transparency'] == true)
            $data['is_transparency'] = hasAlpha($image);

        $data['width']  = imagesx($image);
        $data['height'] = imagesy($image);

        // Min/Max resizing
        $minWidth = 0;
        $minHeight = 0;
        $maxWidth = 0;
        $maxHeight = 0;

        if ($data['min_width']) {
            if ($data['min_width'] <= $data['width'])
                $minWidth = $data['width'];
            else
                $data['error'] = 40;
        }

        if ($data['min_height']) {
            if ($data['min_height'] <= $data['height'])
                $minHeight = $data['height'];
            else
                $data['error'] = 50;
        }

        if ($data['max_width']) {
            if ($data['max_width'] > $data['width'])
                $maxWidth = $data['width'];
            else
                $maxWidth = $data['max_width'];
        }

        if ($data['max_height']) {
            if ($data['max_height'] > $data['height'])
                $maxHeight = $data['height'];
            else
                $maxHeight = $data['max_height'];
        }

        // Приоритеты
        if (!$data['new_width']) {
            if ($maxWidth)
                $data['new_width'] = $maxWidth;
            else
                $data['new_width'] = $data['width'];
        }

        if (!$data['new_height']) {
            if ($maxHeight)
                $data['new_height'] = $maxHeight;
            else
                $data['new_height'] = $data['height'];
        }

        // Resizing
        if ($data['new_width'] == 0 && $data['new_height'] == 0) {
            $data['new_width'] = $data['width'];
            $data['new_height'] = $data['height'];
        } else if ($data['new_width'] != 0 && $data['new_height'] == 0) {
            $hw = round($data['height'] / $data['width'], 6);
            $data['new_height'] = round($hw * $data['new_width'], 0);
        } else if ($data['new_width'] == 0 && $data['new_height'] != 0) {
            $hw = round($data['width'] / $data['height'], 6);
            $data['new_width'] = round($hw * $data['new_height'], 0);
        } else if ($data['new_width'] != 0 && $data['new_height'] != 0) {

        }

        if ($data['resize'] == 1) {
            $data['height'] = $data['new_height'];
            $data['width'] = $data['new_width'];
        }

        if ($data['resize'] == 2) {
            if ($data['new_width'] > $data['new_height']) {
                $hw = round($data['new_height'] / $data['new_width'], 6);
                $data['height'] = round($hw * $data['width'], 0);
            } elseif ($data['new_width'] < $data['new_height']) {
                $hw = round($data['new_width'] / $data['new_height'], 6);
                $data['width'] = round($hw * $data['height'], 0);
            } else {
                if ($data['width'] > $data['height']) {
                    $data['width'] = $data['height'];
                } else {
                    $data['height'] = $data['width'];
                }
            }
        }

        if ($data['error'] != 0)
            return $data;


        if (!$data['cropPosX'] OR $data['cropPosX'] <= 0)
            $data['cropPosX'] = 0;

        if (!$data['cropPosY'] OR $data['cropPosY'] <= 0)
            $data['cropPosY'] = 0;

        if (!$data['crop_width'] OR $data['crop_width'] <= 0) {
            $imageWidth = $data['width'];
        } else {
            $imageWidth = $data['crop_width'] - 1;
            if ($data['make_bigger_quality'] == true) {
                if ($imageWidth > $data['new_width'] && $imageWidth <= 500)
                    $data['new_width'] = $imageWidth;
                elseif ($imageWidth > $data['new_width'] && $imageWidth > 500)
                    $data['new_width'] = 500;
            }
        }

        if (!$data['crop_height'] OR $data['crop_height'] <= 0) {
            $imageHeight = $data['height'];
        } else {
            $imageHeight = $data['crop_height'] - 1;
            if ($data['make_bigger_quality'] == true) {
                if ($imageHeight > $data['new_height'] && $imageHeight <= 500)
                    $data['new_height'] = $imageHeight;
                elseif ($imageHeight > $data['new_height'] && $imageHeight > 500)
                    $data['new_height'] = 500;
            }
        }


//        // Resize if image bigger than 2880×1800
//        $maxW = 2880;
//        $maxH = 1800;
//        if ($data['new_width'] > $maxW || $data['new_height'] > $maxH) {
//            $ratio = min($maxW / $data['width'], $maxH / $data['height']);
//            $data['new_width'] = $data['width'] * $ratio;
//            $data['new_height'] = $data['height'] * $ratio;
//        }


        $screen = imageCreateTrueColor($data['new_width'], $data['new_height']);

        switch ($data['format']) {
            case 'gif':
            case 'png':
                // integer representation of the color black (rgb: 0,0,0)
                $background = imagecolorallocate($screen , 0, 0, 0);
                // removing the black from the placeholder
                imagecolortransparent($screen, $background);

                // turning off alpha blending (to ensure alpha channel information
                // is preserved, rather than removed (blending with the rest of the image in the form of black))
                imagealphablending($screen, false);

                // turning on alpha channel information saving (to ensure the full range of transparency is preserved)
                imagesavealpha($screen, true);
                break;

            default:
                break;
        }

        imageCopyResampled($screen, $image, 0, 0, $data['cropPosX'], $data['cropPosY'], $data['new_width'], $data['new_height'], $imageWidth, $imageHeight);

        if ($data['new_format'] == 'jpg')
            $imagePrint = 'imageJpeg';
        else
            $imagePrint = 'image' . $data['new_format'];
        $imagePrint($screen, $data['path'].$data['new_name'].'.'.$data['new_format']);
        imageDestroy($image);

        //$info = getImageSize($data['path'] . $data['new_name'] . '.' . $data['format']);
        //print_data($info);
        return $data;
    }

}

// Calculate crop block ratio
function cropImageRatio($cropBlockW, $cropBlockH, $imgW, $imgH) {
    if ($imgH <= $cropBlockH && $imgW <= $cropBlockW) {
        $targetHeight = $imgH;
        $targetWidth = $imgW;

    } else if ($imgH > $cropBlockH && $imgW <= $cropBlockW) {
        $targetHeight = $cropBlockH;
        $hw = round($imgW / $imgH, 6);
        $targetWidth = round($hw * $cropBlockH,0);

    } else if ($imgH <= $cropBlockH && $imgW > $cropBlockW) {
        $targetWidth = $cropBlockW;
        $hw = round($imgH / $imgW, 6);
        $targetHeight = round($hw * $cropBlockW, 0);

    } else if ($imgH > $cropBlockH && $imgW > $cropBlockW) {
        if ($imgH > $imgW) {
            $targetHeight = $cropBlockH;
            $hw = round($imgW / $imgH, 6);
            $targetWidth = round($hw * $cropBlockH,0);
        } else {
            $targetWidth = $cropBlockW;
            $hw = round($imgH / $imgW, 6);
            $targetHeight = round($hw * $cropBlockW, 0);
        }
    }

    return [$targetWidth, $targetHeight];
}

function hasAlpha($imgdata)
{
    $w = imagesx($imgdata);
    $h = imagesy($imgdata);

    if ($w > 50 || $h > 50) { // resize the image to save processing if larger than 50px:
        $thumb = imagecreatetruecolor(50, 50);
        imagealphablending($thumb, FALSE);
        imagecopyresized($thumb, $imgdata, 0, 0, 0, 0, 50, 50, $w, $h);
        //imagePng($thumb, _SYSDIR_ . 'data/tmp/' . 'AlphaIMG.png'); // save file

        $imgdata = $thumb;
        $w = imagesx($imgdata);
        $h = imagesy($imgdata);
    }

    // run through pixels until transparent pixel is found:
    $k = 0;
    for ($i = 0; $i < $w; $i++) {
        for ($j = 0; $j < $h; $j++) {
            $rgba = imagecolorat($imgdata, $i, $j);
            if (($rgba & 0x7F000000) >> 24) $k++;
            if ($k >= 100) return true;
        }
    }
    return false;
}

/**
 * https://stackoverflow.com/questions/13076480/php-get-actual-maximum-upload-size
 * @return int
 */
function file_upload_max_size()
{
    static $max_size = -1;

    if ($max_size < 0) {
        // Start with post_max_size.
        $post_max_size = parse_ini_size(ini_get('post_max_size'));
        if ($post_max_size > 0) {
            $max_size = $post_max_size;
        }

        // If upload_max_size is less, then reduce. Except if upload_max_size is zero, which indicates no limit.
        $upload_max = parse_ini_size(ini_get('upload_max_filesize'));
        if ($upload_max > 0 && $upload_max < $max_size) {
            $max_size = $upload_max;
        }
    }
    return $max_size;
}

function parse_ini_size($size)
{
    $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
    $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
    if ($unit) {
        // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
        return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
    } else {
        return round($size);
    }
}

function format_bytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('b', 'Kb', 'Mb', 'Gb', 'Tb');

    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}

function file_upload_max_size_format()
{
    return format_bytes(file_upload_max_size());
}

/* End of file */
