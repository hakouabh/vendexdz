<?php

namespace App\Helpers;

class WilayaHelper
{
    protected static $wilayas = [
        1  => ['Adrar', 'أدرار', 'adrar', 'ادّرا', 'ادرار'],
        2  => ['Chlef', 'الشلف', 'chlef', 'الشّلف', 'شلّف'],
        3  => ['Laghouat', 'الأغواط', 'laghouat', 'الأغوات', 'الأغوّاط'],
        4  => ['Oum El Bouaghi', 'أم البواقي', 'oum el bouaghi', 'أم البواقى', 'أم البوقي'],
        5  => ['Batna', 'باتنة', 'batna', 'باتنّة'],
        6  => ['Béjaïa', 'بجاية', 'bejaia', 'بجايا', 'بجيهية'],
        7  => ['Biskra', 'بسكرة', 'biskra', 'بسكرا'],
        8  => ['Béchar', 'بشار', 'bechar', 'بشّار', 'بشارة'],
        9  => ['Blida', 'البليدة', 'blida', 'البليده', 'بليدة'],
        10 => ['Bouira', 'البويرة', 'bouira', 'البويره', 'بويره'],
        11 => ['Tamanrasset', 'تمنراست', 'tamanrasset', 'تمنراست', 'تمنراسط'],
        12 => ['Tébessa', 'تبسة', 'tebessa', 'تبسه'],
        13 => ['Tlemcen', 'تلمسان', 'tlemcen', 'تلمصان', 'تلمسّان'],
        14 => ['Tiaret', 'تيارت', 'tiaret', 'تياره', 'تيارت'],
        15 => ['Tizi Ouzou', 'تيزي وزو', 'tizi ouzou', 'تيزي وزو', 'تيزي وزّو'],
        16 => ['Alger', 'الجزائر', 'alger', 'الجزاير', 'الجزائر'],
        17 => ['Djelfa', 'الجلفة', 'djelfa', 'الجلفه', 'الجلفا'],
        18 => ['Jijel', 'جيجل', 'jijel', 'جيجل', 'جيجّل'],
        19 => ['Sétif', 'سطيف', 'setif', 'سطيف', 'سطّيف'],
        20 => ['Saïda', 'سعيدة', 'saida', 'سعيده', 'سعيدا'],
        21 => ['Skikda', 'سكيكدة', 'skikda', 'سكيكده', 'سكيكدا'],
        22 => ['Sidi Bel Abbès', 'سيدي بلعباس', 'sidi bel abbes', 'سيدي بلعباس', 'سيدي بلعبّاس'],
        23 => ['Annaba', 'عنابة', 'annaba', 'عنابه'],
        24 => ['Guelma', 'قالمة', 'guelma', 'قالمه'],
        25 => ['Constantine', 'قسنطينة', 'constantine', 'قسنطينه', 'قسنطينه'],
        26 => ['Médéa', 'المدية', 'medea', 'المديه', 'المدية'],
        27 => ['Mostaganem', 'مستغانم', 'mostaganem', 'مستغانم', 'مستاغنم'],
        28 => ['M\'Sila', 'المسيلة', 'm\'sila', 'المسيله', 'المسيله'],
        29 => ['Mascara', 'معسكر', 'mascara', 'معسكر', 'معسكرة'],
        30 => ['Ouargla', 'ورقلة', 'ouargla', 'ورقله', 'ورقلة'],
        31 => ['Oran', 'وهران', 'oran', 'وهران', 'وهران'],
        32 => ['El Bayadh', 'البيض', 'el bayadh', 'البيض', 'البييض'],
        33 => ['Illizi', 'إليزي', 'illizi', 'إليزي', 'اليليزي'],
        34 => ['Bordj Bou Arréridj', 'برج بوعريريج', 'bordj bou arréridj', 'برج بوعريريج', 'برج بوعريريج'],
        35 => ['Boumerdès', 'بومرداس', 'boumerdes', 'بومرداس', 'بومردّاس'],
        36 => ['El Tarf', 'الطارف', 'el tarf', 'الطارف', 'الطارف'],
        37 => ['Tindouf', 'تندوف', 'tindouf', 'تندوف', 'تندوف'],
        38 => ['Tissemsilt', 'تيسمسيلت', 'tissemsilt', 'تيسمسيلت', 'تيسمسيلت'],
        39 => ['El Oued', 'الوادي', 'el oued', 'الوادي', 'الوّادي'],
        40 => ['Khenchela', 'خنشلة', 'khenchela', 'خنشله', 'خنشله'],
        41 => ['Souk Ahras', 'سوق أهراس', 'souk ahras', 'سوق اهراس', 'سوق اهراس'],
        42 => ['Tipaza', 'تيبازة', 'tipaza', 'تيبازه', 'تيبازة'],
        43 => ['Mila', 'ميلة', 'mila', 'ميله', 'ميله'],
        44 => ['Aïn Defla', 'عين الدفلى', 'ain defla', 'عين الدفلى', 'عين الدفلا'],
        45 => ['Naâma', 'النعامة', 'naama', 'النعامة', 'النعامه'],
        46 => ['Aïn Témouchent', 'عين تموشنت', 'ain temouchent', 'عين تموشنت', 'عين تموشنت'],
        47 => ['Ghardaïa', 'غرداية', 'ghardaia', 'غردايه', 'غردايه'],
        48 => ['Relizane', 'غليزان', 'relizane', 'غليزان', 'غليزان'],
        49 => ['Timimoun', 'تيميمون', 'timimoun', 'تيميمون', 'تيميمون'],
        50 => ['Bordj Badji Mokhtar', 'برج باجي مختار', 'bordj badji mokhtar', 'برج باجي مختار'],
        51 => ['Ouled Djellal', 'أولاد جلال', 'ouled djellal', 'اولاد جلال', 'اولاد جلال'],
        52 => ['Béni Abbès', 'بني عباس', 'beni abbes', 'بني عباس', 'بني عباس'],
        53 => ['In Salah', 'عين صالح', 'in salah', 'عين صالح', 'عين صالح'],
        54 => ['In Guezzam', 'عين قزّام', 'in guezzam', 'عين قزام', 'عين قزّام'],
        55 => ['Touggourt', 'تقرت', 'touggourt', 'تقرت', 'تقرت'],
        56 => ['Djanet', 'جانت', 'djanet', 'جانت', 'جانيت'],
        57 => ['El M\'Ghair', 'المغير', 'el m\'ghair', 'المغير', 'المغير'],
        58 => ['El Menia', 'المنيعة', 'el menia', 'المنيعة', 'المنيعه'],
    ];

    /**
     * Convert wilaya name to ID
     * 
     * @param string|null $name
     * @return int|string Returns ID as int or error message in red HTML
     */
    public static function nameToId(?string $name)
    {
        if (empty($name)) {
            return self::colorRed('خطأ: اسم الولاية فارغ');
        }

        $name = mb_strtolower(trim($name));
        
        foreach (self::$wilayas as $id => $names) {
            foreach ($names as $n) {
                if (mb_strtolower($n) === $name) {
                    return $id;
                }
            }
        }
        
        return self::colorRed('خطأ: اسم الولاية غير موجود');
    }

    /**
     * Convert wilaya ID to name
     * 
     * @param int|null $id
     * @param string $lang 'fr' for French, 'ar' for Arabic
     * @return string
     */
    public static function idToName($id, string $lang = 'fr'): string
    {
        // Handle null or invalid ID
        if ($id === null || !is_numeric($id)) {
            return self::colorRed($lang === 'ar' ? 'خطأ: معرف غير صالح' : 'Erreur');
        }

        $id = (int) $id;

        // Check if wilaya exists
        if (!isset(self::$wilayas[$id])) {
            return self::colorRed($lang === 'ar' ? 'خطأ' : 'Erreur');
        }

        return $lang === 'ar' ? self::$wilayas[$id][1] : self::$wilayas[$id][0];
    }

    /**
     * Get all wilayas
     * 
     * @param string $lang 'fr' for French, 'ar' for Arabic
     * @return array
     */
    public static function all(string $lang = 'fr'): array
    {
        $result = [];
        foreach (self::$wilayas as $id => $names) {
            $result[$id] = $lang === 'ar' ? $names[1] : $names[0];
        }
        return $result;
    }

    /**
     * Check if wilaya ID is valid
     * 
     * @param int|null $id
     * @return bool
     */
    public static function isValid($id): bool
    {
        if ($id === null || !is_numeric($id)) {
            return false;
        }
        
        return isset(self::$wilayas[(int) $id]);
    }

    /**
     * Format text in red color for HTML output
     * 
     * @param string $text
     * @return string
     */
    protected static function colorRed(string $text): string
    {
        return '<span style="color: red; font-weight: bold;">' . htmlspecialchars($text) . '</span>';
    }

    /**
     * Get wilaya details with validation
     * 
     * @param int|null $id
     * @return array|string
     */
    public static function getDetails($id)
    {
        if (!self::isValid($id)) {
            return self::colorRed('خطأ: معرف الولاية غير صالح');
        }

        $id = (int) $id;
        return [
            'id' => $id,
            'name_fr' => self::$wilayas[$id][0],
            'name_ar' => self::$wilayas[$id][1],
            'all_names' => self::$wilayas[$id]
        ];
    }

    /**
     * Safe ID to name conversion (returns empty string instead of error for invalid IDs)
     * Useful for forms and displays where you don't want error messages
     * 
     * @param int|null $id
     * @param string $lang 'fr' for French, 'ar' for Arabic
     * @return string
     */
    public static function safeIdToName($id, string $lang = 'fr'): string
    {
        if (!self::isValid($id)) {
            return '';
        }

        return self::idToName($id, $lang);
    }
}