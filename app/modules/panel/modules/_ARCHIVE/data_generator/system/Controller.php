<?php
class Data_generatorController extends Controller
{
    protected $layout = 'layout_panel';
    protected $sectorsName = ['Creative arts and design', 'Healthcare', 'Marketing', 'Sales', 'Web development'];
    protected $contractType = ['permanent', 'contract', 'temporary'];

    use Validator;

    public function indexAction()
    {
        $this->view->list = Data_generatorModel::getAll();
        Request::setTitle('Data generator');
    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('table',          'Table',          'required|trim|min_length[1]|max_length[50]');
            $this->validatePost('number',         'Number of Rows', 'required|trim|');
            $this->validatePost('type',           'Types',          'min_count[1]');
            $this->validatePost('column',         'User Image',     'min_count[1]');

            if ($this->isValid()) {

                $result = $this->insertData(post('table'), post('number'), post('column'), post('type'), post('image'), post('existing_table'));

                if ($result) {
                    Model::insert('fake_data', $result);
                    Request::addResponse('redirect', false, url('panel', 'data_generator'));
                } else {
                    Request::returnError('Database error');
                }

            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

    }

    public function executeAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $data = Data_generatorModel::get($id);

        if (!$data)
            Request::returnError('Invalid data id');

        $result = $this->insertData($data->table, $data->number, $data->column, explode(',', $data->type), $data->image, $data->existing_table, true);

        if ($result) {
            Request::returnError('Data inserted');
        } else {
            Request::returnError('Database error');
        }

    }


     protected function insertData($table, $number, $column, $type, $image, $existing_table, $explode = false)
     {

         require_once(_SYSDIR_.'system/lib/faker/src/autoload.php');
         $faker = Faker\Factory::create();
         Model::import('panel');


         if ($explode === true)
         {
                $image = explode(',', $image);
                $existing_table = explode(',', $existing_table);
         }

         $columnIn = is_array($column) ? implode(',', $column) : [$column];
         $typeIn   = is_array($type) ? implode(',', $type) : [$type];


         $data = [
             'table'          => $table,
             'number'         => $number,
             'column'         => $columnIn,
             'type'           => $typeIn,
             'time'           => time(),
         ];

         if ($image)
             $data['image'] = implode(',', $image);
         if ($existing_table)
             $data['existing_table'] = implode(',', $existing_table);


         $allValues = [];
         if (is_array($existing_table) && count($existing_table) > 0)
         {
             foreach ($existing_table as $value){

                 $parts = explode('/', $value);

                 $allValues[] = PanelModel::getValues($parts[0], $parts[1]);
             }
         }

         $insertData = [];

         for ($i = 0; $i < $number; $i++)
         {

             $count = 0;
             $countImage = 0;

             foreach ($type as $item)
             {
                 switch ($item) {
                     case 'blog_title':
                         $insertData[$i][] = $faker->sentence(2);
                         break;
                     case 'content_short':
                         $insertData[$i][] = $faker->sentence(15);
                         break;
                     case 'content':
                         $insertData[$i][] = $faker->sentence(40);
                         break;
                     case 'job_title':
                         $insertData[$i][] = $faker->jobTitle;
                         break;
                     case 'company':
                         $insertData[$i][] = $faker->company;
                         break;
                     case 'email':
                         $insertData[$i][] = $faker->email;
                         break;
                     case 'telephone':
                         $insertData[$i][] = $faker->phoneNumber;
                         break;
                     case 'url':
                         $insertData[$i][] = $faker->url;
                         break;
                     case 'first_name':
                         $insertData[$i][] = $faker->firstName;
                         break;
                     case 'last_name':
                         $insertData[$i][] = $faker->lastName;
                         break;
                     case 'full_name':
                         $insertData[$i][] = $faker->name;
                         break;
                     case 'time':
                         $insertData[$i][] = time();
                         break;
                     case 'time_expire':
                         $insertData[$i][] = time() + 24 * 3600 * 180;
                         break;
                     case 'password':
                         $insertData[$i][] = md5('password');
                         break;
                     case 'city':
                         $insertData[$i][] = $faker->city;
                         break;
                     case 'country':
                         $insertData[$i][] = $faker->unique()->country;
                         break;
                     case 'image':

                         if (!File::copy('data/tmp/' . $image[0], 'data/' . $data['table'] . '/' . $image[0]))
                             print_data(error_get_last());
                         $insertData[$i][] = $image[$countImage];
                         $countImage++;
                         break;
                     case 'slug':
                         $insertData[$i][] = $faker->slug;
                         break;
                     case 'consultant':
                         $insertData[$i][] = 'moder';
                         break;
                     case 'sector':
                         $insertData[$i][] = $faker->unique()->randomElement($this->sectorsName);
                         break;
                     case 'salary_value':
                         $insertData[$i][] = $faker->numberBetween(10000, 90000);
                         break;
                     case 'contract_type':
                         $insertData[$i][] = $faker->randomElement($this->contractType);
                         break;
                     case 'existing_table':
                         if ($allValues) {
                             $insertData[$i][] = $allValues[$count][array_rand($allValues[$count])];
                         }
                         $count++;
                         break;
                     case 'uniq_job_id':
                         Model::import('panel/vacancies');
                         $jobs = VacanciesModel::getAll();
                         $insertData[$i][] = $jobs[$i]->id;
                         break;
                 }
             }

         }

         $result = PanelModel::dataInsert($table, $column, $insertData);
         $insertID = PanelModel::insertID();

         if (!$result && $insertID) {
             return $data;
         } else {
            return false;
         }

     }


}
/* End of file */