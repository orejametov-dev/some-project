<?php

namespace App\Console\Commands\OneOff;

use App\Modules\Merchants\Models\Store;
use Illuminate\Console\Command;

class SetResponsiblePerson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:responsible_person';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $datas = array(
            0 => array('id' => '2', 'name' => 'Арена Марказ Компас', 'phone' => '998171637', 'imya' => 'Oybek'),
            1 => array('id' => '7', 'name' => 'T-Store Artel Premium Qibray', 'phone' => '993072020', 'imya' => 'Ziyodulla'),
            2 => array('id' => '13', 'name' => 'Crediton', 'phone' => '903518430', 'imya' => 'Ravshan'),
            3 => array('id' => '16', 'name' => 'Ultrashop', 'phone' => '935055872', 'imya' => 'Файзуллох'),
            4 => array('id' => '22', 'name' => 'Techno Shop - Online №1', 'phone' => '901245355', 'imya' => 'Хусан'),
            5 => array('id' => '25', 'name' => 'MNO Technoshop', 'phone' => '901245355', 'imya' => 'Хусан'),
            6 => array('id' => '33', 'name' => 'Texnolux', 'phone' => '949390033', 'imya' => 'Алие'),
            7 => array('id' => '34', 'name' => 'Best Trade', 'phone' => '949393060', 'imya' => 'Бек'),
            8 => array('id' => '37', 'name' => 'STS', 'phone' => '909731599', 'imya' => 'Ойбек'),
            9 => array('id' => '48', 'name' => 'Credit Express - Farhadskiy', 'phone' => '943555000', 'imya' => 'Эркин'),
            10 => array('id' => '50', 'name' => 'MNO IDEA', 'phone' => '901 193 338', 'imya' => 'Boymirza'),
            11 => array('id' => '54', 'name' => 'New Life', 'phone' => '977 226 632', 'imya' => 'Farruh'),
            12 => array('id' => '65', 'name' => 'Mediamag', 'phone' => '901857681', 'imya' => 'Эмиль'),
            13 => array('id' => '66', 'name' => 'Easymarket', 'phone' => '997 910 809', 'imya' => 'Жавохир'),
            17 => array('id' => '77', 'name' => 'Around Group Profit', 'phone' => '903 926 969', 'imya' => 'Nariman'),
            18 => array('id' => '78', 'name' => 'workers  trade group', 'phone' => '998 921 626', 'imya' => 'Uchqun aka'),
            20 => array('id' => '81', 'name' => 'Interbrands', 'phone' => '980 005 030', 'imya' => 'Mirqobil aka'),
            21 => array('id' => '82', 'name' => 'Mobilezone (online)', 'phone' => '90-994-08-00', 'imya' => 'Шахзода'),
            23 => array('id' => '88', 'name' => 'Phoenix', 'phone' => '90 015 44 55', 'imya' => 'Zilolaxon'),
            26 => array('id' => '92', 'name' => 'MNO -ultrashop', 'phone' => '93 390-00-66', 'imya' => 'Fayzulloh'),
            27 => array('id' => '93', 'name' => 'mobi zone uz', 'phone' => '33-252-14-88', 'imya' => 'Elizavetta'),
            29 => array('id' => '95', 'name' => 'Bean bag', 'phone' => '97-773-44-33', 'imya' => 'Salokhiddin'),
            30 => array('id' => '101', 'name' => 'Home market', 'phone' => '99-831-44-24', 'imya' => 'Shohjahon'),
            32 => array('id' => '112', 'name' => 'Beshr', 'phone' => '90-348-08-08', 'imya' => 'Afruza'),
            33 => array('id' => '113', 'name' => 'Tezz.uz', 'phone' => '97-741-65-35', 'imya' => 'Sanjarali'),
            36 => array('id' => '116', 'name' => 'Техно Home Макро', 'phone' => '99-591-23-30', 'imya' => 'Jahongir'),
            38 => array('id' => '119', 'name' => 'Texnologika', 'phone' => '901 193 338', 'imya' => 'Boymirza'),
            39 => array('id' => '120', 'name' => 'thompson mobile', 'phone' => '99-377-46-05', 'imya' => 'Bobur'),
            40 => array('id' => '122', 'name' => 'Zarrin Plus', 'phone' => '93-329-53-18', 'imya' => 'Akmal'),
            41 => array('id' => '123', 'name' => 'Credit yangiyo\'l', 'phone' => '99-033-90-45', 'imya' => 'Malika'),
            42 => array('id' => '128', 'name' => 'IDEA Magnit', 'phone' => '901 193 338', 'imya' => 'Boymirza'),
            43 => array('id' => '130', 'name' => 'BRANDSTORE.UZ (online)', 'phone' => '99 780-81-09', 'imya' => 'Elyor'),
            44 => array('id' => '132', 'name' => 'Smart компас', 'phone' => '998 171 637', 'imya' => ''),
            45 => array('id' => '134', 'name' => 'LUX COMFORT CREDIT', 'phone' => '94-402-05-76', 'imya' => 'Iskandar'),
            47 => array('id' => '137', 'name' => 'Prizma', 'phone' => '903 530 099', 'imya' => 'Jahongir aka'),
            49 => array('id' => '139', 'name' => 'Ideal Legenda', 'phone' => '931849200', 'imya' => 'Imom'),
            50 => array('id' => '140', 'name' => 'samsung mobile', 'phone' => '90 103-57-57', 'imya' => 'Ilhom'),
            51 => array('id' => '141', 'name' => 'paragraf (Navoiy)', 'phone' => '94-224-44-44', 'imya' => 'Farhod aka'),
            52 => array('id' => '143', 'name' => 'Techno plaza (Urgench)', 'phone' => '900 906 767', 'imya' => 'Ibrat aka'),
            53 => array('id' => '145', 'name' => 'Istiqlol', 'phone' => '90-190-86-86', 'imya' => 'Sherzod'),
            54 => array('id' => '146', 'name' => 'Diamond Lux', 'phone' => '99-402-35-45', 'imya' => 'Dilshod'),
            57 => array('id' => '156', 'name' => 'Royal Urgench', 'phone' => '91-570-87-87', 'imya' => 'Nodir'),
            58 => array('id' => '158', 'name' => 'Era Apex Angren', 'phone' => '88 544-01-77', 'imya' => 'Shoxrux'),
            60 => array('id' => '160', 'name' => 'Zar Kredit Savdo Namangan', 'phone' => '97-253-96-96', 'imya' => 'Doston'),
            61 => array('id' => '162', 'name' => 'bts-retail', 'phone' => '90-850-55-50', 'imya' => 'Shohruh'),
            62 => array('id' => '163', 'name' => 'TEMA - Kompyuter bozori (Urgench)', 'phone' => '99-560-81-31', 'imya' => 'Umar'),
            63 => array('id' => '164', 'name' => 'Mediamag (online)', 'phone' => '90-185-86-73', 'imya' => 'Ekaterina'),
            64 => array('id' => '165', 'name' => 'Webmall Navoiy', 'phone' => '94253-44-94', 'imya' => 'Sherzod'),
            65 => array('id' => '175', 'name' => 'Connect Nano Fergana Gastronom', 'phone' => '33 126-22-46', 'imya' => 'Otabek'),
            66 => array('id' => '182', 'name' => 'parkent avto olami', 'phone' => '946 893 949', 'imya' => 'Sarvar aka'),
            67 => array('id' => '183', 'name' => 'AIKO WOODS', 'phone' => '99-854-11-88', 'imya' => 'Evgeniy'),
            68 => array('id' => '184', 'name' => 'Mobile Zone (offline)', 'phone' => '97-412-12-41', 'imya' => 'Nodir'),
            69 => array('id' => '185', 'name' => 'ТЕХНО-ЛИДЕР (Чирчик)', 'phone' => '97-345-54-00', 'imya' => 'Alina'),
            71 => array('id' => '188', 'name' => 'Ardy Shop Qorasuv', 'phone' => '97 470 00 69', 'imya' => 'Shohruh aka'),
            73 => array('id' => '248', 'name' => 'Azbo.uz', 'phone' => '99-981-88-81', 'imya' => 'Bobur'),
            74 => array('id' => '249', 'name' => 'smartmarket', 'phone' => '90-921-44-42', 'imya' => 'Abdumalik'),
            75 => array('id' => '252', 'name' => 'Premier', 'phone' => '91 537-78-87', 'imya' => 'Shalola'),
            76 => array('id' => '253', 'name' => 'kredit', 'phone' => '90 120 03 04', 'imya' => 'Dilrabo'),
            77 => array('id' => '272', 'name' => 'Beshariq', 'phone' => '90-850-55-50', 'imya' => 'Shohruh'),
            78 => array('id' => '299', 'name' => 'Smart-Kassa', 'phone' => '97-512-77-87', 'imya' => 'Azamat'),
            80 => array('id' => '305', 'name' => 'Grand Maishiy Texnika', 'phone' => '90-998-04-16', 'imya' => 'Maxsud'),
            83 => array('id' => '319', 'name' => 'zma mahroj', 'phone' => '99 044 01 10', 'imya' => 'Abdulhamid'),
            84 => array('id' => '323', 'name' => 'Ривьера', 'phone' => '998 171 637', 'imya' => ''),
            85 => array('id' => '326', 'name' => 'Perfect Goods', 'phone' => '936776118', 'imya' => 'Jahongir'),
            86 => array('id' => '338', 'name' => 'Matnazar Gulchehra', 'phone' => 'not working', 'imya' => 'Ilyosbek'),
            87 => array('id' => '339', 'name' => 'Techno Amilko 21', 'phone' => '943 224 900', 'imya' => 'Alojon'),
            88 => array('id' => '340', 'name' => 'Azizbek Musobek Burxon', 'phone' => '914 250 606', 'imya' => 'Izzatbek'),
            89 => array('id' => '347', 'name' => 'Navoiy-Elektrolux', 'phone' => '903 158 544', 'imya' => 'Nilufar'),
            90 => array('id' => '350', 'name' => 'Xiva Qalbinur', 'phone' => '914 250 777', 'imya' => 'Bunyod'),
            91 => array('id' => '351', 'name' => 'Most Goal Group', 'phone' => '935 226 333', 'imya' => 'Tulqin'),
            92 => array('id' => '355', 'name' => 'Infinite Zero', 'phone' => '90 467-31-97', 'imya' => 'Umida'),
            93 => array('id' => '357', 'name' => 'Smart Techno', 'phone' => '885 109 900', 'imya' => 'Xudoyberdi'),
            94 => array('id' => '358', 'name' => 'Maxam', 'phone' => '88 018 04 18', 'imya' => 'Muslimbek'),
            95 => array('id' => '364', 'name' => 'Dostonjon Shohruhxon Fayz Servis', 'phone' => '932 270 000', 'imya' => 'Doston'),
            96 => array('id' => '371', 'name' => 'Mobile zone Navoiy', 'phone' => '91 335 60 06', 'imya' => 'Zafarjon'),
            98 => array('id' => '377', 'name' => 'Xalq Qudrat Yuksalish Sari', 'phone' => '972 991 495', 'imya' => 'Jahongir'),
            99 => array('id' => '381', 'name' => 'Sher Texno Trade', 'phone' => '975 129 544', 'imya' => 'Shoxruh'),
            100 => array('id' => '384', 'name' => 'Prizma (online)', 'phone' => '', 'imya' => 'Jahongir'),
            104 => array('id' => '395', 'name' => 'Sirius-Gold', 'phone' => '907 378 088', 'imya' => 'Islom'),
            106 => array('id' => '402', 'name' => 'Telefonlar Markazi', 'phone' => '97 510 79 99', 'imya' => 'Xushnud'),
            107 => array('id' => '403', 'name' => 'Xushnudbek Mobile', 'phone' => '972 210 101', 'imya' => 'Xushnudbek'),
            108 => array('id' => '406', 'name' => 'Narpay Mirjalol', 'phone' => '942 210 058', 'imya' => 'Ikrom'),
            109 => array('id' => '407', 'name' => 'Sapayev Holding', 'phone' => '975 177 776', 'imya' => 'Kudrat'),
            111 => array('id' => '431', 'name' => 'Bitcom-Credit', 'phone' => '99-182-79-12', 'imya' => 'Sarvinor'),
            113 => array('id' => '439', 'name' => 'Best Golden Prize', 'phone' => '994 010 920', 'imya' => 'Qayyum'),
            115 => array('id' => '481', 'name' => 'Lufran Plyus', 'phone' => '944 041 448', 'imya' => 'Akmaljon'),
            116 => array('id' => '484', 'name' => 'Mobi zone Artur', 'phone' => '97 133 33 31', 'imya' => 'Farruh'),
            117 => array('id' => '485', 'name' => 'Uchquduq Sahro Baraka Invest', 'phone' => '934 609 959', 'imya' => 'Zamira'),
            118 => array('id' => '505', 'name' => 'Galaxy Techno', 'phone' => '93 748-88-88', 'imya' => 'Rasulbek'),
            119 => array('id' => '507', 'name' => 'UniShop', 'phone' => '974 506 898', 'imya' => 'Bexruz'),
            120 => array('id' => '514', 'name' => 'Ibrohim-Denov Fayz', 'phone' => '88-808-49-49', 'imya' => 'Anvar'),
            121 => array('id' => '519', 'name' => 'Mega Texno Centre', 'phone' => '913310010', 'imya' => 'Muhriddin'),
            122 => array('id' => '520', 'name' => 'Triomobile', 'phone' => '944112020', 'imya' => 'Nodirbek'),
            123 => array('id' => '522', 'name' => 'Texno-Trade Centre', 'phone' => '883770004', 'imya' => 'Dadahon'),
            124 => array('id' => '544', 'name' => 'Mobile Techno Busines', 'phone' => '978814494', 'imya' => 'Sardor'),
            125 => array('id' => '545', 'name' => 'Ro\'ziyeva Oliya', 'phone' => '97 487-87-17', 'imya' => 'Nigora'),
            126 => array('id' => '553', 'name' => 'Bexruz-Avaz-Madina', 'phone' => '942 011 000', 'imya' => 'Bexruz'),
            127 => array('id' => '554', 'name' => 'Nasaf-Naqshijahon', 'phone' => '995 077 227', 'imya' => 'Dadahon'),
            128 => array('id' => '557', 'name' => 'Orginal Mayishiy Elektron', 'phone' => '97 780-00-23', 'imya' => 'Muhriddin'),
            129 => array('id' => '562', 'name' => 'Obid Baraka Biznes', 'phone' => '97 449-17-27', 'imya' => 'Rasul'),
            131 => array('id' => '578', 'name' => 'Parkent Biznes Olami', 'phone' => '936156006', 'imya' => 'Qosimjon'),
            132 => array('id' => '579', 'name' => 'Advanced Latest Tech', 'phone' => '99-322-22-23', 'imya' => 'Baxtiyor'),
            133 => array('id' => '588', 'name' => 'Smart Credit Techno', 'phone' => '88-900-99-42', 'imya' => 'Umid'),
            134 => array('id' => '590', 'name' => 'Newstar', 'phone' => '912 041 114', 'imya' => 'Sherzod'),
            135 => array('id' => '591', 'name' => 'Favorit Ishonch', 'phone' => '933 750 300', 'imya' => 'Kamoliddin'),
            136 => array('id' => '592', 'name' => 'Smart-Technics', 'phone' => '977 752 232', 'imya' => 'Sardor'),
            138 => array('id' => '597', 'name' => 'Mir Rich Young Businessman', 'phone' => '977 752 232', 'imya' => 'Abbos'),
            141 => array('id' => '635', 'name' => 'New Art HF', 'phone' => '994 996 860', 'imya' => 'Fazliddin'),
            142 => array('id' => '638', 'name' => 'Flowers City', 'phone' => '995 008 197', 'imya' => 'Dilshod'),
        );


        foreach ($datas as $data) {
            $res = str_replace(' ', '', $data['phone']);
            $res = str_replace('-', '', $res);
            $data['phone'] = $res;


            $store = Store::query()->find($data['id']);
            if(!$store) continue;
            if(is_null($data['imya']) and is_null($data['phone'])) continue;
            $store->responsible_person = $data['imya'];
            $store->responsible_person_phone = $res;
            $store->save();
        }

//
//
//
//
//        $stores = Store::chunkById(100, function ($stores) {
//            foreach ($stores as $store) {
//                if (!$store->responsible_person) {
//                    $merchant = $store->merchant;
//                    $main_store = $merchant->stores()->main()->first();
//                    $merchant->stores()->whereNull('responsible_person')->update([
//                        'responsible_person' => $main_store->responsible_person,
//                        'responsible_person_phone' => $main_store->responsible_person_phone
//                    ]);
//                }
//            }
//        });
    }
}
