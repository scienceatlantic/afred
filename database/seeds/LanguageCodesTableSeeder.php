<?php

use App\LanguageCode;
use Illuminate\Database\Seeder;

class LanguageCodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $codes = [
            [
                'name'      => 'Abkhazian',
                'iso_639_1' => 'ab'
            ],
            [
                'name'      => 'Afar',
                'iso_639_1' => 'aa'
            ],
            [
                'name'      => 'Afrikaans',
                'iso_639_1' => 'af'
            ],
            [
                'name'      => 'Akan',
                'iso_639_1' => 'ak'
            ],
            [
                'name'      => 'Albanian',
                'iso_639_1' => 'sq'
            ],
            [
                'name'      => 'Amharic',
                'iso_639_1' => 'am'
            ],
            [
                'name'      => 'Arabic',
                'iso_639_1' => 'ar'
            ],
            [
                'name'      => 'Aragonese',
                'iso_639_1' => 'an'
            ],
            [
                'name'      => 'Armenian',
                'iso_639_1' => 'hy'
            ],
            [
                'name'      => 'Assamese',
                'iso_639_1' => 'as'
            ],
            [
                'name'      => 'Avaric',
                'iso_639_1' => 'av'
            ],
            [
                'name'      => 'Avestan',
                'iso_639_1' => 'ae'
            ],
            [
                'name'      => 'Aymara',
                'iso_639_1' => 'ay'
            ],
            [
                'name'      => 'Azerbaijani',
                'iso_639_1' => 'az'
            ],
            [
                'name'      => 'Bambara',
                'iso_639_1' => 'bm'
            ],
            [
                'name'      => 'Bashkir',
                'iso_639_1' => 'ba'
            ],
            [
                'name'      => 'Basque',
                'iso_639_1' => 'eu'
            ],
            [
                'name'      => 'Belarusian',
                'iso_639_1' => 'be'
            ],
            [
                'name'      => 'Bengali',
                'iso_639_1' => 'bn'
            ],
            [
                'name'      => 'Bihari languages',
                'iso_639_1' => 'bh'
            ],
            [
                'name'      => 'Bislama',
                'iso_639_1' => 'bi'
            ],
            [
                'name'      => 'Bosnian',
                'iso_639_1' => 'bs'
            ],
            [
                'name'      => 'Breton',
                'iso_639_1' => 'br'
            ],
            [
                'name'      => 'Bulgarian',
                'iso_639_1' => 'bg'
            ],
            [
                'name'      => 'Burmese',
                'iso_639_1' => 'my'
            ],
            [
                'name'      => 'Catalan, Valencian',
                'iso_639_1' => 'ca'
            ],
            [
                'name'      => 'Chamorro',
                'iso_639_1' => 'ch'
            ],
            [
                'name'      => 'Chechen',
                'iso_639_1' => 'ce'
            ],
            [
                'name'      => 'Chichewa, Chewa, Nyanja',
                'iso_639_1' => 'ny'
            ],
            [
                'name'      => 'Chinese',
                'iso_639_1' => 'zh'
            ],
            [
                'name'      => 'Chuvash',
                'iso_639_1' => 'cv'
            ],
            [
                'name'      => 'Cornish',
                'iso_639_1' => 'kw'
            ],
            [
                'name'      => 'Corsican',
                'iso_639_1' => 'co'
            ],
            [
                'name'      => 'Cree',
                'iso_639_1' => 'cr'
            ],
            [
                'name'      => 'Croatian',
                'iso_639_1' => 'hr'
            ],
            [
                'name'      => 'Czech',
                'iso_639_1' => 'cs'
            ],
            [
                'name'      => 'Danish',
                'iso_639_1' => 'da'
            ],
            [
                'name'      => 'Divehi, Dhivehi, Maldivian',
                'iso_639_1' => 'dv'
            ],
            [
                'name'      => 'Dutch, Flemish',
                'iso_639_1' => 'nl'
            ],
            [
                'name'      => 'Dzongkha',
                'iso_639_1' => 'dz'
            ],
            [
                'name'      => 'English',
                'iso_639_1' => 'en'
            ],
            [
                'name'      => 'Esperanto',
                'iso_639_1' => 'eo'
            ],
            [
                'name'      => 'Estonian',
                'iso_639_1' => 'et'
            ],
            [
                'name'      => 'Ewe',
                'iso_639_1' => 'ee'
            ],
            [
                'name'      => 'Faroese',
                'iso_639_1' => 'fo'
            ],
            [
                'name'      => 'Fijian',
                'iso_639_1' => 'fj'
            ],
            [
                'name'      => 'Finnish',
                'iso_639_1' => 'fi'
            ],
            [
                'name'      => 'French',
                'iso_639_1' => 'fr'
            ],
            [
                'name'      => 'Fulah',
                'iso_639_1' => 'ff'
            ],
            [
                'name'      => 'Galician',
                'iso_639_1' => 'gl'
            ],
            [
                'name'      => 'Georgian',
                'iso_639_1' => 'ka'
            ],
            [
                'name'      => 'German',
                'iso_639_1' => 'de'
            ],
            [
                'name'      => 'Greek (modern)',
                'iso_639_1' => 'el'
            ],
            [
                'name'      => 'Guaraní',
                'iso_639_1' => 'gn'
            ],
            [
                'name'      => 'Gujarati',
                'iso_639_1' => 'gu'
            ],
            [
                'name'      => 'Haitian, Haitian Creole',
                'iso_639_1' => 'ht'
            ],
            [
                'name'      => 'Hausa',
                'iso_639_1' => 'ha'
            ],
            [
                'name'      => 'Hebrew (modern)',
                'iso_639_1' => 'he'
            ],
            [
                'name'      => 'Herero',
                'iso_639_1' => 'hz'
            ],
            [
                'name'      => 'Hindi',
                'iso_639_1' => 'hi'
            ],
            [
                'name'      => 'Hiri Motu',
                'iso_639_1' => 'ho'
            ],
            [
                'name'      => 'Hungarian',
                'iso_639_1' => 'hu'
            ],
            [
                'name'      => 'Interlingua',
                'iso_639_1' => 'ia'
            ],
            [
                'name'      => 'Indonesian',
                'iso_639_1' => 'id'
            ],
            [
                'name'      => 'Interlingue',
                'iso_639_1' => 'ie'
            ],
            [
                'name'      => 'Irish',
                'iso_639_1' => 'ga'
            ],
            [
                'name'      => 'Igbo',
                'iso_639_1' => 'ig'
            ],
            [
                'name'      => 'Inupiaq',
                'iso_639_1' => 'ik'
            ],
            [
                'name'      => 'Ido',
                'iso_639_1' => 'io'
            ],
            [
                'name'      => 'Icelandic',
                'iso_639_1' => 'is'
            ],
            [
                'name'      => 'Italian',
                'iso_639_1' => 'it'
            ],
            [
                'name'      => 'Inuktitut',
                'iso_639_1' => 'iu'
            ],
            [
                'name'      => 'Japanese',
                'iso_639_1' => 'ja'
            ],
            [
                'name'      => 'Javanese',
                'iso_639_1' => 'jv'
            ],
            [
                'name'      => 'Kalaallisut, Greenlandic',
                'iso_639_1' => 'kl'
            ],
            [
                'name'      => 'Kannada',
                'iso_639_1' => 'kn'
            ],
            [
                'name'      => 'Kanuri',
                'iso_639_1' => 'kr'
            ],
            [
                'name'      => 'Kashmiri',
                'iso_639_1' => 'ks'
            ],
            [
                'name'      => 'Kazakh',
                'iso_639_1' => 'kk'
            ],
            [
                'name'      => 'Central Khmer',
                'iso_639_1' => 'km'
            ],
            [
                'name'      => 'Kikuyu, Gikuyu',
                'iso_639_1' => 'ki'
            ],
            [
                'name'      => 'Kinyarwanda',
                'iso_639_1' => 'rw'
            ],
            [
                'name'      => 'Kirghiz, Kyrgyz',
                'iso_639_1' => 'ky'
            ],
            [
                'name'      => 'Komi',
                'iso_639_1' => 'kv'
            ],
            [
                'name'      => 'Kongo',
                'iso_639_1' => 'kg'
            ],
            [
                'name'      => 'Korean',
                'iso_639_1' => 'ko'
            ],
            [
                'name'      => 'Kurdish',
                'iso_639_1' => 'ku'
            ],
            [
                'name'      => 'Kuanyama, Kwanyama',
                'iso_639_1' => 'kj'
            ],
            [
                'name'      => 'Latin',
                'iso_639_1' => 'la'
            ],
            [
                'name'      => 'Luxembourgish, Letzeburgesch',
                'iso_639_1' => 'lb'
            ],
            [
                'name'      => 'Ganda',
                'iso_639_1' => 'lg'
            ],
            [
                'name'      => 'Limburgan, Limburger, Limburgish',
                'iso_639_1' => 'li'
            ],
            [
                'name'      => 'Lingala',
                'iso_639_1' => 'ln'
            ],
            [
                'name'      => 'Lao',
                'iso_639_1' => 'lo'
            ],
            [
                'name'      => 'Lithuanian',
                'iso_639_1' => 'lt'
            ],
            [
                'name'      => 'Luba-Katanga',
                'iso_639_1' => 'lu'
            ],
            [
                'name'      => 'Latvian',
                'iso_639_1' => 'lv'
            ],
            [
                'name'      => 'Manx',
                'iso_639_1' => 'gv'
            ],
            [
                'name'      => 'Macedonian',
                'iso_639_1' => 'mk'
            ],
            [
                'name'      => 'Malagasy',
                'iso_639_1' => 'mg'
            ],
            [
                'name'      => 'Malay',
                'iso_639_1' => 'ms'
            ],
            [
                'name'      => 'Malayalam',
                'iso_639_1' => 'ml'
            ],
            [
                'name'      => 'Maltese',
                'iso_639_1' => 'mt'
            ],
            [
                'name'      => 'Maori',
                'iso_639_1' => 'mi'
            ],
            [
                'name'      => 'Marathi',
                'iso_639_1' => 'mr'
            ],
            [
                'name'      => 'Marshallese',
                'iso_639_1' => 'mh'
            ],
            [
                'name'      => 'Mongolian',
                'iso_639_1' => 'mn'
            ],
            [
                'name'      => 'Nauru',
                'iso_639_1' => 'na'
            ],
            [
                'name'      => 'Navajo, Navaho',
                'iso_639_1' => 'nv'
            ],
            [
                'name'      => 'North Ndebele',
                'iso_639_1' => 'nd'
            ],
            [
                'name'      => 'Nepali',
                'iso_639_1' => 'ne'
            ],
            [
                'name'      => 'Ndonga',
                'iso_639_1' => 'ng'
            ],
            [
                'name'      => 'Norwegian Bokmål',
                'iso_639_1' => 'nb'
            ],
            [
                'name'      => 'Norwegian Nynorsk',
                'iso_639_1' => 'nn'
            ],
            [
                'name'      => 'Norwegian',
                'iso_639_1' => 'no'
            ],
            [
                'name'      => 'Sichuan Yi, Nuosu',
                'iso_639_1' => 'ii'
            ],
            [
                'name'      => 'South Ndebele',
                'iso_639_1' => 'nr'
            ],
            [
                'name'      => 'Occitan',
                'iso_639_1' => 'oc'
            ],
            [
                'name'      => 'Ojibwa',
                'iso_639_1' => 'oj'
            ],
            [
                'name'      => 'Church Slavic, Church Slavonic, Old Church Slavonic, Old Slavonic, Old Bulgarian',
                'iso_639_1' => 'cu'
            ],
            [
                'name'      => 'Oromo',
                'iso_639_1' => 'om'
            ],
            [
                'name'      => 'Oriya',
                'iso_639_1' => 'or'
            ],
            [
                'name'      => 'Ossetian, Ossetic',
                'iso_639_1' => 'os'
            ],
            [
                'name'      => 'Panjabi, Punjabi',
                'iso_639_1' => 'pa'
            ],
            [
                'name'      => 'Pali',
                'iso_639_1' => 'pi'
            ],
            [
                'name'      => 'Persian',
                'iso_639_1' => 'fa'
            ],
            [
                'name'      => 'Polish',
                'iso_639_1' => 'pl'
            ],
            [
                'name'      => 'Pashto, Pushto',
                'iso_639_1' => 'ps'
            ],
            [
                'name'      => 'Portuguese',
                'iso_639_1' => 'pt'
            ],
            [
                'name'      => 'Quechua',
                'iso_639_1' => 'qu'
            ],
            [
                'name'      => 'Romansh',
                'iso_639_1' => 'rm'
            ],
            [
                'name'      => 'Rundi',
                'iso_639_1' => 'rn'
            ],
            [
                'name'      => 'Romanian, Moldavian, Moldovan',
                'iso_639_1' => 'ro'
            ],
            [
                'name'      => 'Russian',
                'iso_639_1' => 'ru'
            ],
            [
                'name'      => 'Sanskrit',
                'iso_639_1' => 'sa'
            ],
            [
                'name'      => 'Sardinian',
                'iso_639_1' => 'sc'
            ],
            [
                'name'      => 'Sindhi',
                'iso_639_1' => 'sd'
            ],
            [
                'name'      => 'Northern Sami',
                'iso_639_1' => 'se'
            ],
            [
                'name'      => 'Samoan',
                'iso_639_1' => 'sm'
            ],
            [
                'name'      => 'Sango',
                'iso_639_1' => 'sg'
            ],
            [
                'name'      => 'Serbian',
                'iso_639_1' => 'sr'
            ],
            [
                'name'      => 'Gaelic, Scottish Gaelic',
                'iso_639_1' => 'gd'
            ],
            [
                'name'      => 'Shona',
                'iso_639_1' => 'sn'
            ],
            [
                'name'      => 'Sinhala, Sinhalese',
                'iso_639_1' => 'si'
            ],
            [
                'name'      => 'Slovak',
                'iso_639_1' => 'sk'
            ],
            [
                'name'      => 'Slovenian',
                'iso_639_1' => 'sl'
            ],
            [
                'name'      => 'Somali',
                'iso_639_1' => 'so'
            ],
            [
                'name'      => 'Southern Sotho',
                'iso_639_1' => 'st'
            ],
            [
                'name'      => 'Spanish, Castilian',
                'iso_639_1' => 'es'
            ],
            [
                'name'      => 'Sundanese',
                'iso_639_1' => 'su'
            ],
            [
                'name'      => 'Swahili',
                'iso_639_1' => 'sw'
            ],
            [
                'name'      => 'Swati',
                'iso_639_1' => 'ss'
            ],
            [
                'name'      => 'Swedish',
                'iso_639_1' => 'sv'
            ],
            [
                'name'      => 'Tamil',
                'iso_639_1' => 'ta'
            ],
            [
                'name'      => 'Telugu',
                'iso_639_1' => 'te'
            ],
            [
                'name'      => 'Tajik',
                'iso_639_1' => 'tg'
            ],
            [
                'name'      => 'Thai',
                'iso_639_1' => 'th'
            ],
            [
                'name'      => 'Tigrinya',
                'iso_639_1' => 'ti'
            ],
            [
                'name'      => 'Tibetan',
                'iso_639_1' => 'bo'
            ],
            [
                'name'      => 'Turkmen',
                'iso_639_1' => 'tk'
            ],
            [
                'name'      => 'Tagalog',
                'iso_639_1' => 'tl'
            ],
            [
                'name'      => 'Tswana',
                'iso_639_1' => 'tn'
            ],
            [
                'name'      => 'Tonga (Tonga Islands)',
                'iso_639_1' => 'to'
            ],
            [
                'name'      => 'Turkish',
                'iso_639_1' => 'tr'
            ],
            [
                'name'      => 'Tsonga',
                'iso_639_1' => 'ts'
            ],
            [
                'name'      => 'Tatar',
                'iso_639_1' => 'tt'
            ],
            [
                'name'      => 'Twi',
                'iso_639_1' => 'tw'
            ],
            [
                'name'      => 'Tahitian',
                'iso_639_1' => 'ty'
            ],
            [
                'name'      => 'Uighur, Uyghur',
                'iso_639_1' => 'ug'
            ],
            [
                'name'      => 'Ukrainian',
                'iso_639_1' => 'uk'
            ],
            [
                'name'      => 'Urdu',
                'iso_639_1' => 'ur'
            ],
            [
                'name'      => 'Uzbek',
                'iso_639_1' => 'uz'
            ],
            [
                'name'      => 'Venda',
                'iso_639_1' => 've'
            ],
            [
                'name'      => 'Vietnamese',
                'iso_639_1' => 'vi'
            ],
            [
                'name'      => 'Volapük',
                'iso_639_1' => 'vo'
            ],
            [
                'name'      => 'Walloon',
                'iso_639_1' => 'wa'
            ],
            [
                'name'      => 'Welsh',
                'iso_639_1' => 'cy'
            ],
            [
                'name'      => 'Wolof',
                'iso_639_1' => 'wo'
            ],
            [
                'name'      => 'Western Frisian',
                'iso_639_1' => 'fy'
            ],
            [
                'name'      => 'Xhosa',
                'iso_639_1' => 'xh'
            ],
            [
                'name'      => 'Yiddish',
                'iso_639_1' => 'yi'
            ],
            [
                'name'      => 'Yoruba',
                'iso_639_1' => 'yo'
            ],
            [
                'name'      => 'Zhuang, Chuang',
                'iso_639_1' => 'za'
            ],
            [
                'name'      => 'Zulu',
                'iso_639_1' => 'zu'
            ]          
        ];

        foreach($codes as $code) {
            $lc = new LanguageCode();
            $lc->name = $code['name'];
            $lc->iso_639_1 = $code['iso_639_1'];
            $lc->save();
        }
    }
}
