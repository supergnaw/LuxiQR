<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR;

class LuxiQR
{
    // traits
    use DebugTrait;
    use EncodeTrait;
    use GaloisFieldTrait;
    use InputValidation;
    use MaskTrait;
    use OutputTrait;
    use ReedSolomonTrait;
    use StructureTrait;
    use VersionFormatTrait;

    // modes
    public const NUMERIC = "0001";
    public const ALPHANUMERIC = "0010";
    public const BYTE = "0100";
    public const KANJI = "1000";
    private const ECI = "0111"; // TODO: this mode exists for extended character support but I do not know how to implement it

    // error correction levels
    public const EC_LOW = "L";
    public const EC_QUARTILE = "Q";
    public const EC_MEDIUM = "M";
    public const EC_HIGH = "H";

    // regex test patterns
    protected const REGEX_NUMERIC = '/^\d+$/';
    protected const REGEX_ALPHANUMERIC = '/^[A-Z0-9 $&\*\+\-\.\/:]+$/';

    // padding
    protected const PAD_DATA = "0";
    protected const PAD_ENCODED = "1110110000010001";

    // qr code lookup tables
    protected const CHARACTER_LIMIT_TABLE = [
        "1" => [
            self::EC_LOW => [self::NUMERIC => 41, self::ALPHANUMERIC => 25, self::BYTE => 17, self::KANJI => 10],
            self::EC_MEDIUM => [self::NUMERIC => 34, self::ALPHANUMERIC => 20, self::BYTE => 14, self::KANJI => 8],
            self::EC_QUARTILE => [self::NUMERIC => 27, self::ALPHANUMERIC => 16, self::BYTE => 11, self::KANJI => 7],
            self::EC_HIGH => [self::NUMERIC => 17, self::ALPHANUMERIC => 10, self::BYTE => 7, self::KANJI => 4]
        ],
        "2" => [
            self::EC_LOW => [self::NUMERIC => 77, self::ALPHANUMERIC => 47, self::BYTE => 32, self::KANJI => 20],
            self::EC_MEDIUM => [self::NUMERIC => 63, self::ALPHANUMERIC => 38, self::BYTE => 26, self::KANJI => 16],
            self::EC_QUARTILE => [self::NUMERIC => 48, self::ALPHANUMERIC => 29, self::BYTE => 20, self::KANJI => 12],
            self::EC_HIGH => [self::NUMERIC => 34, self::ALPHANUMERIC => 20, self::BYTE => 14, self::KANJI => 8]
        ],
        "3" => [
            self::EC_LOW => [self::NUMERIC => 127, self::ALPHANUMERIC => 77, self::BYTE => 53, self::KANJI => 32],
            self::EC_MEDIUM => [self::NUMERIC => 101, self::ALPHANUMERIC => 61, self::BYTE => 42, self::KANJI => 26],
            self::EC_QUARTILE => [self::NUMERIC => 77, self::ALPHANUMERIC => 47, self::BYTE => 32, self::KANJI => 20],
            self::EC_HIGH => [self::NUMERIC => 58, self::ALPHANUMERIC => 35, self::BYTE => 24, self::KANJI => 15]
        ],
        "4" => [
            self::EC_LOW => [self::NUMERIC => 187, self::ALPHANUMERIC => 114, self::BYTE => 78, self::KANJI => 48],
            self::EC_MEDIUM => [self::NUMERIC => 149, self::ALPHANUMERIC => 90, self::BYTE => 62, self::KANJI => 38],
            self::EC_QUARTILE => [self::NUMERIC => 111, self::ALPHANUMERIC => 67, self::BYTE => 46, self::KANJI => 28],
            self::EC_HIGH => [self::NUMERIC => 82, self::ALPHANUMERIC => 50, self::BYTE => 34, self::KANJI => 21]
        ],
        "5" => [
            self::EC_LOW => [self::NUMERIC => 255, self::ALPHANUMERIC => 154, self::BYTE => 106, self::KANJI => 65],
            self::EC_MEDIUM => [self::NUMERIC => 202, self::ALPHANUMERIC => 122, self::BYTE => 84, self::KANJI => 52],
            self::EC_QUARTILE => [self::NUMERIC => 144, self::ALPHANUMERIC => 87, self::BYTE => 60, self::KANJI => 37],
            self::EC_HIGH => [self::NUMERIC => 106, self::ALPHANUMERIC => 64, self::BYTE => 44, self::KANJI => 27]
        ],
        "6" => [
            self::EC_LOW => [self::NUMERIC => 322, self::ALPHANUMERIC => 195, self::BYTE => 134, self::KANJI => 82],
            self::EC_MEDIUM => [self::NUMERIC => 255, self::ALPHANUMERIC => 154, self::BYTE => 106, self::KANJI => 65],
            self::EC_QUARTILE => [self::NUMERIC => 178, self::ALPHANUMERIC => 108, self::BYTE => 74, self::KANJI => 45],
            self::EC_HIGH => [self::NUMERIC => 139, self::ALPHANUMERIC => 84, self::BYTE => 58, self::KANJI => 36]
        ],
        "7" => [
            self::EC_LOW => [self::NUMERIC => 370, self::ALPHANUMERIC => 224, self::BYTE => 154, self::KANJI => 95],
            self::EC_MEDIUM => [self::NUMERIC => 293, self::ALPHANUMERIC => 178, self::BYTE => 122, self::KANJI => 75],
            self::EC_QUARTILE => [self::NUMERIC => 207, self::ALPHANUMERIC => 125, self::BYTE => 86, self::KANJI => 53],
            self::EC_HIGH => [self::NUMERIC => 154, self::ALPHANUMERIC => 93, self::BYTE => 64, self::KANJI => 39]
        ],
        "8" => [
            self::EC_LOW => [self::NUMERIC => 461, self::ALPHANUMERIC => 279, self::BYTE => 192, self::KANJI => 118],
            self::EC_MEDIUM => [self::NUMERIC => 365, self::ALPHANUMERIC => 221, self::BYTE => 152, self::KANJI => 93],
            self::EC_QUARTILE => [self::NUMERIC => 259, self::ALPHANUMERIC => 157, self::BYTE => 108, self::KANJI => 66],
            self::EC_HIGH => [self::NUMERIC => 202, self::ALPHANUMERIC => 122, self::BYTE => 84, self::KANJI => 52]
        ],
        "9" => [
            self::EC_LOW => [self::NUMERIC => 552, self::ALPHANUMERIC => 335, self::BYTE => 230, self::KANJI => 141],
            self::EC_MEDIUM => [self::NUMERIC => 432, self::ALPHANUMERIC => 262, self::BYTE => 180, self::KANJI => 111],
            self::EC_QUARTILE => [self::NUMERIC => 312, self::ALPHANUMERIC => 189, self::BYTE => 130, self::KANJI => 80],
            self::EC_HIGH => [self::NUMERIC => 235, self::ALPHANUMERIC => 143, self::BYTE => 98, self::KANJI => 60]
        ],
        "10" => [
            self::EC_LOW => [self::NUMERIC => 652, self::ALPHANUMERIC => 395, self::BYTE => 271, self::KANJI => 167],
            self::EC_MEDIUM => [self::NUMERIC => 513, self::ALPHANUMERIC => 311, self::BYTE => 213, self::KANJI => 131],
            self::EC_QUARTILE => [self::NUMERIC => 364, self::ALPHANUMERIC => 221, self::BYTE => 151, self::KANJI => 93],
            self::EC_HIGH => [self::NUMERIC => 288, self::ALPHANUMERIC => 174, self::BYTE => 119, self::KANJI => 74]
        ],
        "11" => [
            self::EC_LOW => [self::NUMERIC => 772, self::ALPHANUMERIC => 468, self::BYTE => 321, self::KANJI => 198],
            self::EC_MEDIUM => [self::NUMERIC => 604, self::ALPHANUMERIC => 366, self::BYTE => 251, self::KANJI => 155],
            self::EC_QUARTILE => [self::NUMERIC => 427, self::ALPHANUMERIC => 259, self::BYTE => 177, self::KANJI => 109],
            self::EC_HIGH => [self::NUMERIC => 331, self::ALPHANUMERIC => 200, self::BYTE => 137, self::KANJI => 85]
        ],
        "12" => [
            self::EC_LOW => [self::NUMERIC => 883, self::ALPHANUMERIC => 535, self::BYTE => 367, self::KANJI => 226],
            self::EC_MEDIUM => [self::NUMERIC => 691, self::ALPHANUMERIC => 419, self::BYTE => 287, self::KANJI => 177],
            self::EC_QUARTILE => [self::NUMERIC => 489, self::ALPHANUMERIC => 296, self::BYTE => 203, self::KANJI => 125],
            self::EC_HIGH => [self::NUMERIC => 374, self::ALPHANUMERIC => 227, self::BYTE => 155, self::KANJI => 96]
        ],
        "13" => [
            self::EC_LOW => [self::NUMERIC => 1022, self::ALPHANUMERIC => 619, self::BYTE => 425, self::KANJI => 262],
            self::EC_MEDIUM => [self::NUMERIC => 796, self::ALPHANUMERIC => 483, self::BYTE => 331, self::KANJI => 204],
            self::EC_QUARTILE => [self::NUMERIC => 580, self::ALPHANUMERIC => 352, self::BYTE => 241, self::KANJI => 149],
            self::EC_HIGH => [self::NUMERIC => 427, self::ALPHANUMERIC => 259, self::BYTE => 177, self::KANJI => 109]
        ],
        "14" => [
            self::EC_LOW => [self::NUMERIC => 1101, self::ALPHANUMERIC => 667, self::BYTE => 458, self::KANJI => 282],
            self::EC_MEDIUM => [self::NUMERIC => 871, self::ALPHANUMERIC => 528, self::BYTE => 362, self::KANJI => 223],
            self::EC_QUARTILE => [self::NUMERIC => 621, self::ALPHANUMERIC => 376, self::BYTE => 258, self::KANJI => 159],
            self::EC_HIGH => [self::NUMERIC => 468, self::ALPHANUMERIC => 283, self::BYTE => 194, self::KANJI => 120]
        ],
        "15" => [
            self::EC_LOW => [self::NUMERIC => 1250, self::ALPHANUMERIC => 758, self::BYTE => 520, self::KANJI => 320],
            self::EC_MEDIUM => [self::NUMERIC => 991, self::ALPHANUMERIC => 600, self::BYTE => 412, self::KANJI => 254],
            self::EC_QUARTILE => [self::NUMERIC => 703, self::ALPHANUMERIC => 426, self::BYTE => 292, self::KANJI => 180],
            self::EC_HIGH => [self::NUMERIC => 530, self::ALPHANUMERIC => 321, self::BYTE => 220, self::KANJI => 136]
        ],
        "16" => [
            self::EC_LOW => [self::NUMERIC => 1408, self::ALPHANUMERIC => 854, self::BYTE => 586, self::KANJI => 361],
            self::EC_MEDIUM => [self::NUMERIC => 1082, self::ALPHANUMERIC => 656, self::BYTE => 450, self::KANJI => 277],
            self::EC_QUARTILE => [self::NUMERIC => 775, self::ALPHANUMERIC => 470, self::BYTE => 322, self::KANJI => 198],
            self::EC_HIGH => [self::NUMERIC => 602, self::ALPHANUMERIC => 365, self::BYTE => 250, self::KANJI => 154]
        ],
        "17" => [
            self::EC_LOW => [self::NUMERIC => 1548, self::ALPHANUMERIC => 938, self::BYTE => 644, self::KANJI => 397],
            self::EC_MEDIUM => [self::NUMERIC => 1212, self::ALPHANUMERIC => 734, self::BYTE => 504, self::KANJI => 310],
            self::EC_QUARTILE => [self::NUMERIC => 876, self::ALPHANUMERIC => 531, self::BYTE => 364, self::KANJI => 224],
            self::EC_HIGH => [self::NUMERIC => 674, self::ALPHANUMERIC => 408, self::BYTE => 280, self::KANJI => 173]
        ],
        "18" => [
            self::EC_LOW => [self::NUMERIC => 1725, self::ALPHANUMERIC => 1046, self::BYTE => 718, self::KANJI => 442],
            self::EC_MEDIUM => [self::NUMERIC => 1346, self::ALPHANUMERIC => 816, self::BYTE => 560, self::KANJI => 345],
            self::EC_QUARTILE => [self::NUMERIC => 948, self::ALPHANUMERIC => 574, self::BYTE => 394, self::KANJI => 243],
            self::EC_HIGH => [self::NUMERIC => 746, self::ALPHANUMERIC => 452, self::BYTE => 310, self::KANJI => 191]
        ],
        "19" => [
            self::EC_LOW => [self::NUMERIC => 1903, self::ALPHANUMERIC => 1153, self::BYTE => 792, self::KANJI => 488],
            self::EC_MEDIUM => [self::NUMERIC => 1500, self::ALPHANUMERIC => 909, self::BYTE => 624, self::KANJI => 384],
            self::EC_QUARTILE => [self::NUMERIC => 1063, self::ALPHANUMERIC => 644, self::BYTE => 442, self::KANJI => 272],
            self::EC_HIGH => [self::NUMERIC => 813, self::ALPHANUMERIC => 493, self::BYTE => 338, self::KANJI => 208]
        ],
        "20" => [
            self::EC_LOW => [self::NUMERIC => 2061, self::ALPHANUMERIC => 1249, self::BYTE => 858, self::KANJI => 528],
            self::EC_MEDIUM => [self::NUMERIC => 1600, self::ALPHANUMERIC => 970, self::BYTE => 666, self::KANJI => 410],
            self::EC_QUARTILE => [self::NUMERIC => 1159, self::ALPHANUMERIC => 702, self::BYTE => 482, self::KANJI => 297],
            self::EC_HIGH => [self::NUMERIC => 919, self::ALPHANUMERIC => 557, self::BYTE => 382, self::KANJI => 235]
        ],
        "21" => [
            self::EC_LOW => [self::NUMERIC => 2232, self::ALPHANUMERIC => 1352, self::BYTE => 929, self::KANJI => 572],
            self::EC_MEDIUM => [self::NUMERIC => 1708, self::ALPHANUMERIC => 1035, self::BYTE => 711, self::KANJI => 438],
            self::EC_QUARTILE => [self::NUMERIC => 1224, self::ALPHANUMERIC => 742, self::BYTE => 509, self::KANJI => 314],
            self::EC_HIGH => [self::NUMERIC => 969, self::ALPHANUMERIC => 587, self::BYTE => 403, self::KANJI => 248]
        ],
        "22" => [
            self::EC_LOW => [self::NUMERIC => 2409, self::ALPHANUMERIC => 1460, self::BYTE => 1003, self::KANJI => 618],
            self::EC_MEDIUM => [self::NUMERIC => 1872, self::ALPHANUMERIC => 1134, self::BYTE => 779, self::KANJI => 480],
            self::EC_QUARTILE => [self::NUMERIC => 1358, self::ALPHANUMERIC => 823, self::BYTE => 565, self::KANJI => 348],
            self::EC_HIGH => [self::NUMERIC => 1056, self::ALPHANUMERIC => 640, self::BYTE => 439, self::KANJI => 270]
        ],
        "23" => [
            self::EC_LOW => [self::NUMERIC => 2620, self::ALPHANUMERIC => 1588, self::BYTE => 1091, self::KANJI => 672],
            self::EC_MEDIUM => [self::NUMERIC => 2059, self::ALPHANUMERIC => 1248, self::BYTE => 857, self::KANJI => 528],
            self::EC_QUARTILE => [self::NUMERIC => 1468, self::ALPHANUMERIC => 890, self::BYTE => 611, self::KANJI => 376],
            self::EC_HIGH => [self::NUMERIC => 1108, self::ALPHANUMERIC => 672, self::BYTE => 461, self::KANJI => 284]
        ],
        "24" => [
            self::EC_LOW => [self::NUMERIC => 2812, self::ALPHANUMERIC => 1704, self::BYTE => 1171, self::KANJI => 721],
            self::EC_MEDIUM => [self::NUMERIC => 2188, self::ALPHANUMERIC => 1326, self::BYTE => 911, self::KANJI => 561],
            self::EC_QUARTILE => [self::NUMERIC => 1588, self::ALPHANUMERIC => 963, self::BYTE => 661, self::KANJI => 407],
            self::EC_HIGH => [self::NUMERIC => 1228, self::ALPHANUMERIC => 744, self::BYTE => 511, self::KANJI => 315]
        ],
        "25" => [
            self::EC_LOW => [self::NUMERIC => 3057, self::ALPHANUMERIC => 1853, self::BYTE => 1273, self::KANJI => 784],
            self::EC_MEDIUM => [self::NUMERIC => 2395, self::ALPHANUMERIC => 1451, self::BYTE => 997, self::KANJI => 614],
            self::EC_QUARTILE => [self::NUMERIC => 1718, self::ALPHANUMERIC => 1041, self::BYTE => 715, self::KANJI => 440],
            self::EC_HIGH => [self::NUMERIC => 1286, self::ALPHANUMERIC => 779, self::BYTE => 535, self::KANJI => 330]
        ],
        "26" => [
            self::EC_LOW => [self::NUMERIC => 3283, self::ALPHANUMERIC => 1990, self::BYTE => 1367, self::KANJI => 842],
            self::EC_MEDIUM => [self::NUMERIC => 2544, self::ALPHANUMERIC => 1542, self::BYTE => 1059, self::KANJI => 652],
            self::EC_QUARTILE => [self::NUMERIC => 1804, self::ALPHANUMERIC => 1094, self::BYTE => 751, self::KANJI => 462],
            self::EC_HIGH => [self::NUMERIC => 1425, self::ALPHANUMERIC => 864, self::BYTE => 593, self::KANJI => 365]
        ],
        "27" => [
            self::EC_LOW => [self::NUMERIC => 3517, self::ALPHANUMERIC => 2132, self::BYTE => 1465, self::KANJI => 902],
            self::EC_MEDIUM => [self::NUMERIC => 2701, self::ALPHANUMERIC => 1637, self::BYTE => 1125, self::KANJI => 692],
            self::EC_QUARTILE => [self::NUMERIC => 1933, self::ALPHANUMERIC => 1172, self::BYTE => 805, self::KANJI => 496],
            self::EC_HIGH => [self::NUMERIC => 1501, self::ALPHANUMERIC => 910, self::BYTE => 625, self::KANJI => 385]
        ],
        "28" => [
            self::EC_LOW => [self::NUMERIC => 3669, self::ALPHANUMERIC => 2223, self::BYTE => 1528, self::KANJI => 940],
            self::EC_MEDIUM => [self::NUMERIC => 2857, self::ALPHANUMERIC => 1732, self::BYTE => 1190, self::KANJI => 732],
            self::EC_QUARTILE => [self::NUMERIC => 2085, self::ALPHANUMERIC => 1263, self::BYTE => 868, self::KANJI => 534],
            self::EC_HIGH => [self::NUMERIC => 1581, self::ALPHANUMERIC => 958, self::BYTE => 658, self::KANJI => 405]
        ],
        "29" => [
            self::EC_LOW => [self::NUMERIC => 3909, self::ALPHANUMERIC => 2369, self::BYTE => 1628, self::KANJI => 1002],
            self::EC_MEDIUM => [self::NUMERIC => 3035, self::ALPHANUMERIC => 1839, self::BYTE => 1264, self::KANJI => 778],
            self::EC_QUARTILE => [self::NUMERIC => 2181, self::ALPHANUMERIC => 1322, self::BYTE => 908, self::KANJI => 559],
            self::EC_HIGH => [self::NUMERIC => 1677, self::ALPHANUMERIC => 1016, self::BYTE => 698, self::KANJI => 430]
        ],
        "30" => [
            self::EC_LOW => [self::NUMERIC => 4158, self::ALPHANUMERIC => 2520, self::BYTE => 1732, self::KANJI => 1066],
            self::EC_MEDIUM => [self::NUMERIC => 3289, self::ALPHANUMERIC => 1994, self::BYTE => 1370, self::KANJI => 843],
            self::EC_QUARTILE => [self::NUMERIC => 2358, self::ALPHANUMERIC => 1429, self::BYTE => 982, self::KANJI => 604],
            self::EC_HIGH => [self::NUMERIC => 1782, self::ALPHANUMERIC => 1080, self::BYTE => 742, self::KANJI => 457]
        ],
        "31" => [
            self::EC_LOW => [self::NUMERIC => 4417, self::ALPHANUMERIC => 2677, self::BYTE => 1840, self::KANJI => 1132],
            self::EC_MEDIUM => [self::NUMERIC => 3486, self::ALPHANUMERIC => 2113, self::BYTE => 1452, self::KANJI => 894],
            self::EC_QUARTILE => [self::NUMERIC => 2473, self::ALPHANUMERIC => 1499, self::BYTE => 1030, self::KANJI => 634],
            self::EC_HIGH => [self::NUMERIC => 1897, self::ALPHANUMERIC => 1150, self::BYTE => 790, self::KANJI => 486]
        ],
        "32" => [
            self::EC_LOW => [self::NUMERIC => 4686, self::ALPHANUMERIC => 2840, self::BYTE => 1952, self::KANJI => 1201],
            self::EC_MEDIUM => [self::NUMERIC => 3693, self::ALPHANUMERIC => 2238, self::BYTE => 1538, self::KANJI => 947],
            self::EC_QUARTILE => [self::NUMERIC => 2670, self::ALPHANUMERIC => 1618, self::BYTE => 1112, self::KANJI => 684],
            self::EC_HIGH => [self::NUMERIC => 2022, self::ALPHANUMERIC => 1226, self::BYTE => 842, self::KANJI => 518]
        ],
        "33" => [
            self::EC_LOW => [self::NUMERIC => 4965, self::ALPHANUMERIC => 3009, self::BYTE => 2068, self::KANJI => 1273],
            self::EC_MEDIUM => [self::NUMERIC => 3909, self::ALPHANUMERIC => 2369, self::BYTE => 1628, self::KANJI => 1002],
            self::EC_QUARTILE => [self::NUMERIC => 2805, self::ALPHANUMERIC => 1700, self::BYTE => 1168, self::KANJI => 719],
            self::EC_HIGH => [self::NUMERIC => 2157, self::ALPHANUMERIC => 1307, self::BYTE => 898, self::KANJI => 553]
        ],
        "34" => [
            self::EC_LOW => [self::NUMERIC => 5253, self::ALPHANUMERIC => 3183, self::BYTE => 2188, self::KANJI => 1347],
            self::EC_MEDIUM => [self::NUMERIC => 4134, self::ALPHANUMERIC => 2506, self::BYTE => 1722, self::KANJI => 1060],
            self::EC_QUARTILE => [self::NUMERIC => 2949, self::ALPHANUMERIC => 1787, self::BYTE => 1228, self::KANJI => 756],
            self::EC_HIGH => [self::NUMERIC => 2301, self::ALPHANUMERIC => 1394, self::BYTE => 958, self::KANJI => 590]
        ],
        "35" => [
            self::EC_LOW => [self::NUMERIC => 5529, self::ALPHANUMERIC => 3351, self::BYTE => 2303, self::KANJI => 1417],
            self::EC_MEDIUM => [self::NUMERIC => 4343, self::ALPHANUMERIC => 2632, self::BYTE => 1809, self::KANJI => 1113],
            self::EC_QUARTILE => [self::NUMERIC => 3081, self::ALPHANUMERIC => 1867, self::BYTE => 1283, self::KANJI => 790],
            self::EC_HIGH => [self::NUMERIC => 2361, self::ALPHANUMERIC => 1431, self::BYTE => 983, self::KANJI => 605]
        ],
        "36" => [
            self::EC_LOW => [self::NUMERIC => 5836, self::ALPHANUMERIC => 3537, self::BYTE => 2431, self::KANJI => 1496],
            self::EC_MEDIUM => [self::NUMERIC => 4588, self::ALPHANUMERIC => 2780, self::BYTE => 1911, self::KANJI => 1176],
            self::EC_QUARTILE => [self::NUMERIC => 3244, self::ALPHANUMERIC => 1966, self::BYTE => 1351, self::KANJI => 832],
            self::EC_HIGH => [self::NUMERIC => 2524, self::ALPHANUMERIC => 1530, self::BYTE => 1051, self::KANJI => 647]
        ],
        "37" => [
            self::EC_LOW => [self::NUMERIC => 6153, self::ALPHANUMERIC => 3729, self::BYTE => 2563, self::KANJI => 1577],
            self::EC_MEDIUM => [self::NUMERIC => 4775, self::ALPHANUMERIC => 2894, self::BYTE => 1989, self::KANJI => 1224],
            self::EC_QUARTILE => [self::NUMERIC => 3417, self::ALPHANUMERIC => 2071, self::BYTE => 1423, self::KANJI => 876],
            self::EC_HIGH => [self::NUMERIC => 2625, self::ALPHANUMERIC => 1591, self::BYTE => 1093, self::KANJI => 673]
        ],
        "38" => [
            self::EC_LOW => [self::NUMERIC => 6479, self::ALPHANUMERIC => 3927, self::BYTE => 2699, self::KANJI => 1661],
            self::EC_MEDIUM => [self::NUMERIC => 5039, self::ALPHANUMERIC => 3054, self::BYTE => 2099, self::KANJI => 1292],
            self::EC_QUARTILE => [self::NUMERIC => 3599, self::ALPHANUMERIC => 2181, self::BYTE => 1499, self::KANJI => 923],
            self::EC_HIGH => [self::NUMERIC => 2735, self::ALPHANUMERIC => 1658, self::BYTE => 1139, self::KANJI => 701]
        ],
        "39" => [
            self::EC_LOW => [self::NUMERIC => 6743, self::ALPHANUMERIC => 4087, self::BYTE => 2809, self::KANJI => 1729],
            self::EC_MEDIUM => [self::NUMERIC => 5313, self::ALPHANUMERIC => 3220, self::BYTE => 2213, self::KANJI => 1362],
            self::EC_QUARTILE => [self::NUMERIC => 3791, self::ALPHANUMERIC => 2298, self::BYTE => 1579, self::KANJI => 972],
            self::EC_HIGH => [self::NUMERIC => 2927, self::ALPHANUMERIC => 1774, self::BYTE => 1219, self::KANJI => 750]
        ],
        "40" => [
            self::EC_LOW => [self::NUMERIC => 7089, self::ALPHANUMERIC => 4296, self::BYTE => 2953, self::KANJI => 1817],
            self::EC_MEDIUM => [self::NUMERIC => 5596, self::ALPHANUMERIC => 3391, self::BYTE => 2331, self::KANJI => 1435],
            self::EC_QUARTILE => [self::NUMERIC => 3993, self::ALPHANUMERIC => 2420, self::BYTE => 1663, self::KANJI => 1024],
            self::EC_HIGH => [self::NUMERIC => 3057, self::ALPHANUMERIC => 1852, self::BYTE => 1273, self::KANJI => 784]
        ]
    ];
    protected const BYTE_COUNT_TABLE = [
        1 => [
            self::EC_LOW => ["total" => 19, "ecc" => 7, "groups" => [["blocks" => 1, "size" => 19]]],
            self::EC_MEDIUM => ["total" => 16, "ecc" => 10, "groups" => [["blocks" => 1, "size" => 16]]],
            self::EC_QUARTILE => ["total" => 13, "ecc" => 13, "groups" => [["blocks" => 1, "size" => 13]]],
            self::EC_HIGH => ["total" => 9, "ecc" => 17, "groups" => [["blocks" => 1, "size" => 9]]]
        ],
        2 => [
            self::EC_LOW => ["total" => 34, "ecc" => 10, "groups" => [["blocks" => 1, "size" => 34]]],
            self::EC_MEDIUM => ["total" => 28, "ecc" => 16, "groups" => [["blocks" => 1, "size" => 28]]],
            self::EC_QUARTILE => ["total" => 22, "ecc" => 22, "groups" => [["blocks" => 1, "size" => 22]]],
            self::EC_HIGH => ["total" => 16, "ecc" => 28, "groups" => [["blocks" => 1, "size" => 16]]]
        ],
        3 => [
            self::EC_LOW => ["total" => 55, "ecc" => 15, "groups" => [["blocks" => 1, "size" => 55]]],
            self::EC_MEDIUM => ["total" => 44, "ecc" => 26, "groups" => [["blocks" => 1, "size" => 44]]],
            self::EC_QUARTILE => ["total" => 34, "ecc" => 18, "groups" => [["blocks" => 2, "size" => 17]]],
            self::EC_HIGH => ["total" => 26, "ecc" => 22, "groups" => [["blocks" => 2, "size" => 13]]]
        ],
        4 => [
            self::EC_LOW => ["total" => 80, "ecc" => 20, "groups" => [["blocks" => 1, "size" => 80]]],
            self::EC_MEDIUM => ["total" => 64, "ecc" => 18, "groups" => [["blocks" => 2, "size" => 32]]],
            self::EC_QUARTILE => ["total" => 48, "ecc" => 26, "groups" => [["blocks" => 2, "size" => 24]]],
            self::EC_HIGH => ["total" => 36, "ecc" => 16, "groups" => [["blocks" => 4, "size" => 9]]]
        ],
        5 => [
            self::EC_LOW => ["total" => 108, "ecc" => 26, "groups" => [["blocks" => 1, "size" => 108]]],
            self::EC_MEDIUM => ["total" => 86, "ecc" => 24, "groups" => [["blocks" => 2, "size" => 43]]],
            self::EC_QUARTILE => ["total" => 62, "ecc" => 18, "groups" => [["blocks" => 2, "size" => 15], ["blocks" => 2, "size" => 16]]],
            self::EC_HIGH => ["total" => 46, "ecc" => 22, "groups" => [["blocks" => 2, "size" => 11], ["blocks" => 2, "size" => 12]]]
        ],
        6 => [
            self::EC_LOW => ["total" => 136, "ecc" => 18, "groups" => [["blocks" => 2, "size" => 68]]],
            self::EC_MEDIUM => ["total" => 108, "ecc" => 16, "groups" => [["blocks" => 4, "size" => 27]]],
            self::EC_QUARTILE => ["total" => 76, "ecc" => 24, "groups" => [["blocks" => 4, "size" => 19]]],
            self::EC_HIGH => ["total" => 60, "ecc" => 28, "groups" => [["blocks" => 4, "size" => 15]]]
        ],
        7 => [
            self::EC_LOW => ["total" => 156, "ecc" => 20, "groups" => [["blocks" => 2, "size" => 78]]],
            self::EC_MEDIUM => ["total" => 124, "ecc" => 18, "groups" => [["blocks" => 4, "size" => 31]]],
            self::EC_QUARTILE => ["total" => 88, "ecc" => 18, "groups" => [["blocks" => 2, "size" => 14], ["blocks" => 4, "size" => 15]]],
            self::EC_HIGH => ["total" => 66, "ecc" => 26, "groups" => [["blocks" => 4, "size" => 13], ["blocks" => 1, "size" => 14]]]
        ],
        8 => [
            self::EC_LOW => ["total" => 194, "ecc" => 24, "groups" => [["blocks" => 2, "size" => 97]]],
            self::EC_MEDIUM => ["total" => 154, "ecc" => 22, "groups" => [["blocks" => 2, "size" => 38], ["blocks" => 2, "size" => 39]]],
            self::EC_QUARTILE => ["total" => 110, "ecc" => 22, "groups" => [["blocks" => 4, "size" => 18], ["blocks" => 2, "size" => 19]]],
            self::EC_HIGH => ["total" => 86, "ecc" => 26, "groups" => [["blocks" => 4, "size" => 14], ["blocks" => 2, "size" => 15]]]
        ],
        9 => [
            self::EC_LOW => ["total" => 232, "ecc" => 30, "groups" => [["blocks" => 2, "size" => 116]]],
            self::EC_MEDIUM => ["total" => 182, "ecc" => 22, "groups" => [["blocks" => 3, "size" => 36], ["blocks" => 2, "size" => 37]]],
            self::EC_QUARTILE => ["total" => 132, "ecc" => 20, "groups" => [["blocks" => 4, "size" => 16], ["blocks" => 4, "size" => 17]]],
            self::EC_HIGH => ["total" => 100, "ecc" => 24, "groups" => [["blocks" => 4, "size" => 12], ["blocks" => 4, "size" => 13]]]
        ],
        10 => [
            self::EC_LOW => ["total" => 274, "ecc" => 18, "groups" => [["blocks" => 2, "size" => 68], ["blocks" => 2, "size" => 69]]],
            self::EC_MEDIUM => ["total" => 216, "ecc" => 26, "groups" => [["blocks" => 4, "size" => 43], ["blocks" => 1, "size" => 44]]],
            self::EC_QUARTILE => ["total" => 154, "ecc" => 24, "groups" => [["blocks" => 6, "size" => 19], ["blocks" => 2, "size" => 20]]],
            self::EC_HIGH => ["total" => 122, "ecc" => 28, "groups" => [["blocks" => 6, "size" => 15], ["blocks" => 2, "size" => 16]]]
        ],
        11 => [
            self::EC_LOW => ["total" => 324, "ecc" => 20, "groups" => [["blocks" => 4, "size" => 81]]],
            self::EC_MEDIUM => ["total" => 254, "ecc" => 30, "groups" => [["blocks" => 1, "size" => 50], ["blocks" => 4, "size" => 51]]],
            self::EC_QUARTILE => ["total" => 180, "ecc" => 28, "groups" => [["blocks" => 4, "size" => 22], ["blocks" => 4, "size" => 23]]],
            self::EC_HIGH => ["total" => 140, "ecc" => 24, "groups" => [["blocks" => 3, "size" => 12], ["blocks" => 8, "size" => 13]]]
        ],
        12 => [
            self::EC_LOW => ["total" => 370, "ecc" => 24, "groups" => [["blocks" => 2, "size" => 92], ["blocks" => 2, "size" => 93]]],
            self::EC_MEDIUM => ["total" => 290, "ecc" => 22, "groups" => [["blocks" => 6, "size" => 36], ["blocks" => 2, "size" => 37]]],
            self::EC_QUARTILE => ["total" => 206, "ecc" => 26, "groups" => [["blocks" => 4, "size" => 20], ["blocks" => 6, "size" => 21]]],
            self::EC_HIGH => ["total" => 158, "ecc" => 28, "groups" => [["blocks" => 7, "size" => 14], ["blocks" => 4, "size" => 15]]]
        ],
        13 => [
            self::EC_LOW => ["total" => 428, "ecc" => 26, "groups" => [["blocks" => 4, "size" => 107]]],
            self::EC_MEDIUM => ["total" => 334, "ecc" => 22, "groups" => [["blocks" => 8, "size" => 37], ["blocks" => 1, "size" => 38]]],
            self::EC_QUARTILE => ["total" => 244, "ecc" => 24, "groups" => [["blocks" => 8, "size" => 20], ["blocks" => 4, "size" => 21]]],
            self::EC_HIGH => ["total" => 180, "ecc" => 22, "groups" => [["blocks" => 12, "size" => 11], ["blocks" => 4, "size" => 12]]]
        ],
        14 => [
            self::EC_LOW => ["total" => 461, "ecc" => 30, "groups" => [["blocks" => 3, "size" => 115], ["blocks" => 1, "size" => 116]]],
            self::EC_MEDIUM => ["total" => 365, "ecc" => 24, "groups" => [["blocks" => 4, "size" => 40], ["blocks" => 5, "size" => 41]]],
            self::EC_QUARTILE => ["total" => 261, "ecc" => 20, "groups" => [["blocks" => 11, "size" => 16], ["blocks" => 5, "size" => 17]]],
            self::EC_HIGH => ["total" => 197, "ecc" => 24, "groups" => [["blocks" => 11, "size" => 12], ["blocks" => 5, "size" => 13]]]
        ],
        15 => [
            self::EC_LOW => ["total" => 523, "ecc" => 22, "groups" => [["blocks" => 5, "size" => 87], ["blocks" => 1, "size" => 88]]],
            self::EC_MEDIUM => ["total" => 415, "ecc" => 24, "groups" => [["blocks" => 5, "size" => 41], ["blocks" => 5, "size" => 42]]],
            self::EC_QUARTILE => ["total" => 295, "ecc" => 30, "groups" => [["blocks" => 5, "size" => 24], ["blocks" => 7, "size" => 25]]],
            self::EC_HIGH => ["total" => 223, "ecc" => 24, "groups" => [["blocks" => 11, "size" => 12], ["blocks" => 7, "size" => 13]]]
        ],
        16 => [
            self::EC_LOW => ["total" => 589, "ecc" => 24, "groups" => [["blocks" => 5, "size" => 98], ["blocks" => 1, "size" => 99]]],
            self::EC_MEDIUM => ["total" => 453, "ecc" => 28, "groups" => [["blocks" => 7, "size" => 45], ["blocks" => 3, "size" => 46]]],
            self::EC_QUARTILE => ["total" => 325, "ecc" => 24, "groups" => [["blocks" => 15, "size" => 19], ["blocks" => 2, "size" => 20]]],
            self::EC_HIGH => ["total" => 253, "ecc" => 30, "groups" => [["blocks" => 3, "size" => 15], ["blocks" => 13, "size" => 16]]]
        ],
        17 => [
            self::EC_LOW => ["total" => 647, "ecc" => 28, "groups" => [["blocks" => 1, "size" => 107], ["blocks" => 5, "size" => 108]]],
            self::EC_MEDIUM => ["total" => 507, "ecc" => 28, "groups" => [["blocks" => 10, "size" => 46], ["blocks" => 1, "size" => 47]]],
            self::EC_QUARTILE => ["total" => 367, "ecc" => 28, "groups" => [["blocks" => 1, "size" => 22], ["blocks" => 15, "size" => 23]]],
            self::EC_HIGH => ["total" => 283, "ecc" => 28, "groups" => [["blocks" => 2, "size" => 14], ["blocks" => 17, "size" => 15]]]
        ],
        18 => [
            self::EC_LOW => ["total" => 721, "ecc" => 30, "groups" => [["blocks" => 5, "size" => 120], ["blocks" => 1, "size" => 121]]],
            self::EC_MEDIUM => ["total" => 563, "ecc" => 26, "groups" => [["blocks" => 9, "size" => 43], ["blocks" => 4, "size" => 44]]],
            self::EC_QUARTILE => ["total" => 397, "ecc" => 28, "groups" => [["blocks" => 17, "size" => 22], ["blocks" => 1, "size" => 23]]],
            self::EC_HIGH => ["total" => 313, "ecc" => 28, "groups" => [["blocks" => 2, "size" => 14], ["blocks" => 19, "size" => 15]]]
        ],
        19 => [
            self::EC_LOW => ["total" => 795, "ecc" => 28, "groups" => [["blocks" => 3, "size" => 113], ["blocks" => 4, "size" => 114]]],
            self::EC_MEDIUM => ["total" => 627, "ecc" => 26, "groups" => [["blocks" => 3, "size" => 44], ["blocks" => 11, "size" => 45]]],
            self::EC_QUARTILE => ["total" => 445, "ecc" => 26, "groups" => [["blocks" => 17, "size" => 21], ["blocks" => 4, "size" => 22]]],
            self::EC_HIGH => ["total" => 341, "ecc" => 26, "groups" => [["blocks" => 9, "size" => 13], ["blocks" => 16, "size" => 14]]]
        ],
        20 => [
            self::EC_LOW => ["total" => 861, "ecc" => 28, "groups" => [["blocks" => 3, "size" => 107], ["blocks" => 5, "size" => 108]]],
            self::EC_MEDIUM => ["total" => 669, "ecc" => 26, "groups" => [["blocks" => 3, "size" => 41], ["blocks" => 13, "size" => 42]]],
            self::EC_QUARTILE => ["total" => 485, "ecc" => 30, "groups" => [["blocks" => 15, "size" => 24], ["blocks" => 5, "size" => 25]]],
            self::EC_HIGH => ["total" => 385, "ecc" => 28, "groups" => [["blocks" => 15, "size" => 15], ["blocks" => 10, "size" => 16]]]
        ],
        21 => [
            self::EC_LOW => ["total" => 932, "ecc" => 28, "groups" => [["blocks" => 4, "size" => 116], ["blocks" => 4, "size" => 117]]],
            self::EC_MEDIUM => ["total" => 714, "ecc" => 26, "groups" => [["blocks" => 17, "size" => 42]]],
            self::EC_QUARTILE => ["total" => 512, "ecc" => 28, "groups" => [["blocks" => 17, "size" => 22], ["blocks" => 6, "size" => 23]]],
            self::EC_HIGH => ["total" => 406, "ecc" => 30, "groups" => [["blocks" => 19, "size" => 16], ["blocks" => 6, "size" => 17]]]
        ],
        22 => [
            self::EC_LOW => ["total" => 1006, "ecc" => 28, "groups" => [["blocks" => 2, "size" => 111], ["blocks" => 7, "size" => 112]]],
            self::EC_MEDIUM => ["total" => 782, "ecc" => 28, "groups" => [["blocks" => 17, "size" => 46]]],
            self::EC_QUARTILE => ["total" => 568, "ecc" => 30, "groups" => [["blocks" => 7, "size" => 24], ["blocks" => 16, "size" => 25]]],
            self::EC_HIGH => ["total" => 442, "ecc" => 24, "groups" => [["blocks" => 34, "size" => 13]]]
        ],
        23 => [
            self::EC_LOW => ["total" => 1094, "ecc" => 30, "groups" => [["blocks" => 4, "size" => 121], ["blocks" => 5, "size" => 122]]],
            self::EC_MEDIUM => ["total" => 860, "ecc" => 28, "groups" => [["blocks" => 4, "size" => 47], ["blocks" => 14, "size" => 48]]],
            self::EC_QUARTILE => ["total" => 614, "ecc" => 30, "groups" => [["blocks" => 11, "size" => 24], ["blocks" => 14, "size" => 25]]],
            self::EC_HIGH => ["total" => 464, "ecc" => 30, "groups" => [["blocks" => 16, "size" => 15], ["blocks" => 14, "size" => 16]]]
        ],
        24 => [
            self::EC_LOW => ["total" => 1174, "ecc" => 30, "groups" => [["blocks" => 6, "size" => 117], ["blocks" => 4, "size" => 118]]],
            self::EC_MEDIUM => ["total" => 914, "ecc" => 28, "groups" => [["blocks" => 6, "size" => 45], ["blocks" => 14, "size" => 46]]],
            self::EC_QUARTILE => ["total" => 664, "ecc" => 30, "groups" => [["blocks" => 11, "size" => 24], ["blocks" => 16, "size" => 25]]],
            self::EC_HIGH => ["total" => 514, "ecc" => 30, "groups" => [["blocks" => 30, "size" => 16], ["blocks" => 2, "size" => 17]]]
        ],
        25 => [
            self::EC_LOW => ["total" => 1276, "ecc" => 26, "groups" => [["blocks" => 8, "size" => 106], ["blocks" => 4, "size" => 107]]],
            self::EC_MEDIUM => ["total" => 1000, "ecc" => 28, "groups" => [["blocks" => 8, "size" => 47], ["blocks" => 13, "size" => 48]]],
            self::EC_QUARTILE => ["total" => 718, "ecc" => 30, "groups" => [["blocks" => 7, "size" => 24], ["blocks" => 22, "size" => 25]]],
            self::EC_HIGH => ["total" => 538, "ecc" => 30, "groups" => [["blocks" => 22, "size" => 15], ["blocks" => 13, "size" => 16]]]
        ],
        26 => [
            self::EC_LOW => ["total" => 1370, "ecc" => 28, "groups" => [["blocks" => 10, "size" => 114], ["blocks" => 2, "size" => 115]]],
            self::EC_MEDIUM => ["total" => 1062, "ecc" => 28, "groups" => [["blocks" => 19, "size" => 46], ["blocks" => 4, "size" => 47]]],
            self::EC_QUARTILE => ["total" => 754, "ecc" => 28, "groups" => [["blocks" => 28, "size" => 22], ["blocks" => 6, "size" => 23]]],
            self::EC_HIGH => ["total" => 596, "ecc" => 30, "groups" => [["blocks" => 33, "size" => 16], ["blocks" => 4, "size" => 17]]]
        ],
        27 => [
            self::EC_LOW => ["total" => 1468, "ecc" => 30, "groups" => [["blocks" => 8, "size" => 122], ["blocks" => 4, "size" => 123]]],
            self::EC_MEDIUM => ["total" => 1128, "ecc" => 28, "groups" => [["blocks" => 22, "size" => 45], ["blocks" => 3, "size" => 46]]],
            self::EC_QUARTILE => ["total" => 808, "ecc" => 30, "groups" => [["blocks" => 8, "size" => 23], ["blocks" => 26, "size" => 24]]],
            self::EC_HIGH => ["total" => 628, "ecc" => 30, "groups" => [["blocks" => 12, "size" => 15], ["blocks" => 28, "size" => 16]]]
        ],
        28 => [
            self::EC_LOW => ["total" => 1531, "ecc" => 30, "groups" => [["blocks" => 3, "size" => 117], ["blocks" => 10, "size" => 118]]],
            self::EC_MEDIUM => ["total" => 1193, "ecc" => 28, "groups" => [["blocks" => 3, "size" => 45], ["blocks" => 23, "size" => 46]]],
            self::EC_QUARTILE => ["total" => 871, "ecc" => 30, "groups" => [["blocks" => 4, "size" => 24], ["blocks" => 31, "size" => 25]]],
            self::EC_HIGH => ["total" => 661, "ecc" => 30, "groups" => [["blocks" => 11, "size" => 15], ["blocks" => 31, "size" => 16]]]
        ],
        29 => [
            self::EC_LOW => ["total" => 1631, "ecc" => 30, "groups" => [["blocks" => 7, "size" => 116], ["blocks" => 7, "size" => 117]]],
            self::EC_MEDIUM => ["total" => 1267, "ecc" => 28, "groups" => [["blocks" => 21, "size" => 45], ["blocks" => 7, "size" => 46]]],
            self::EC_QUARTILE => ["total" => 911, "ecc" => 30, "groups" => [["blocks" => 1, "size" => 23], ["blocks" => 37, "size" => 24]]],
            self::EC_HIGH => ["total" => 701, "ecc" => 30, "groups" => [["blocks" => 19, "size" => 15], ["blocks" => 26, "size" => 16]]]
        ],
        30 => [
            self::EC_LOW => ["total" => 1735, "ecc" => 30, "groups" => [["blocks" => 5, "size" => 115], ["blocks" => 10, "size" => 116]]],
            self::EC_MEDIUM => ["total" => 1373, "ecc" => 28, "groups" => [["blocks" => 19, "size" => 47], ["blocks" => 10, "size" => 48]]],
            self::EC_QUARTILE => ["total" => 985, "ecc" => 30, "groups" => [["blocks" => 15, "size" => 24], ["blocks" => 25, "size" => 25]]],
            self::EC_HIGH => ["total" => 745, "ecc" => 30, "groups" => [["blocks" => 23, "size" => 15], ["blocks" => 25, "size" => 16]]]
        ],
        31 => [
            self::EC_LOW => ["total" => 1843, "ecc" => 30, "groups" => [["blocks" => 13, "size" => 115], ["blocks" => 3, "size" => 116]]],
            self::EC_MEDIUM => ["total" => 1455, "ecc" => 28, "groups" => [["blocks" => 2, "size" => 46], ["blocks" => 29, "size" => 47]]],
            self::EC_QUARTILE => ["total" => 1033, "ecc" => 30, "groups" => [["blocks" => 42, "size" => 24], ["blocks" => 1, "size" => 25]]],
            self::EC_HIGH => ["total" => 793, "ecc" => 30, "groups" => [["blocks" => 23, "size" => 15], ["blocks" => 28, "size" => 16]]]
        ],
        32 => [
            self::EC_LOW => ["total" => 1955, "ecc" => 30, "groups" => [["blocks" => 17, "size" => 115]]],
            self::EC_MEDIUM => ["total" => 1541, "ecc" => 28, "groups" => [["blocks" => 10, "size" => 46], ["blocks" => 23, "size" => 47]]],
            self::EC_QUARTILE => ["total" => 1115, "ecc" => 30, "groups" => [["blocks" => 10, "size" => 24], ["blocks" => 35, "size" => 25]]],
            self::EC_HIGH => ["total" => 845, "ecc" => 30, "groups" => [["blocks" => 19, "size" => 15], ["blocks" => 35, "size" => 16]]]
        ],
        33 => [
            self::EC_LOW => ["total" => 2071, "ecc" => 30, "groups" => [["blocks" => 17, "size" => 115], ["blocks" => 1, "size" => 116]]],
            self::EC_MEDIUM => ["total" => 1631, "ecc" => 28, "groups" => [["blocks" => 14, "size" => 46], ["blocks" => 21, "size" => 47]]],
            self::EC_QUARTILE => ["total" => 1171, "ecc" => 30, "groups" => [["blocks" => 29, "size" => 24], ["blocks" => 19, "size" => 25]]],
            self::EC_HIGH => ["total" => 901, "ecc" => 30, "groups" => [["blocks" => 11, "size" => 15], ["blocks" => 46, "size" => 16]]]
        ],
        34 => [
            self::EC_LOW => ["total" => 2191, "ecc" => 30, "groups" => [["blocks" => 13, "size" => 115], ["blocks" => 6, "size" => 116]]],
            self::EC_MEDIUM => ["total" => 1725, "ecc" => 28, "groups" => [["blocks" => 14, "size" => 46], ["blocks" => 23, "size" => 47]]],
            self::EC_QUARTILE => ["total" => 1231, "ecc" => 30, "groups" => [["blocks" => 44, "size" => 24], ["blocks" => 7, "size" => 25]]],
            self::EC_HIGH => ["total" => 961, "ecc" => 30, "groups" => [["blocks" => 59, "size" => 16], ["blocks" => 1, "size" => 17]]]
        ],
        35 => [
            self::EC_LOW => ["total" => 2306, "ecc" => 30, "groups" => [["blocks" => 12, "size" => 121], ["blocks" => 7, "size" => 122]]],
            self::EC_MEDIUM => ["total" => 1812, "ecc" => 28, "groups" => [["blocks" => 12, "size" => 47], ["blocks" => 26, "size" => 48]]],
            self::EC_QUARTILE => ["total" => 1286, "ecc" => 30, "groups" => [["blocks" => 39, "size" => 24], ["blocks" => 14, "size" => 25]]],
            self::EC_HIGH => ["total" => 986, "ecc" => 30, "groups" => [["blocks" => 22, "size" => 15], ["blocks" => 41, "size" => 16]]]
        ],
        36 => [
            self::EC_LOW => ["total" => 2434, "ecc" => 30, "groups" => [["blocks" => 6, "size" => 121], ["blocks" => 14, "size" => 122]]],
            self::EC_MEDIUM => ["total" => 1914, "ecc" => 28, "groups" => [["blocks" => 6, "size" => 47], ["blocks" => 34, "size" => 48]]],
            self::EC_QUARTILE => ["total" => 1354, "ecc" => 30, "groups" => [["blocks" => 46, "size" => 24], ["blocks" => 10, "size" => 25]]],
            self::EC_HIGH => ["total" => 1054, "ecc" => 30, "groups" => [["blocks" => 2, "size" => 15], ["blocks" => 64, "size" => 16]]]
        ],
        37 => [
            self::EC_LOW => ["total" => 2566, "ecc" => 30, "groups" => [["blocks" => 17, "size" => 122], ["blocks" => 4, "size" => 123]]],
            self::EC_MEDIUM => ["total" => 1992, "ecc" => 28, "groups" => [["blocks" => 29, "size" => 46], ["blocks" => 14, "size" => 47]]],
            self::EC_QUARTILE => ["total" => 1426, "ecc" => 30, "groups" => [["blocks" => 49, "size" => 24], ["blocks" => 10, "size" => 25]]],
            self::EC_HIGH => ["total" => 1096, "ecc" => 30, "groups" => [["blocks" => 24, "size" => 15], ["blocks" => 46, "size" => 16]]]
        ],
        38 => [
            self::EC_LOW => ["total" => 2702, "ecc" => 30, "groups" => [["blocks" => 4, "size" => 122], ["blocks" => 18, "size" => 123]]],
            self::EC_MEDIUM => ["total" => 2102, "ecc" => 28, "groups" => [["blocks" => 13, "size" => 46], ["blocks" => 32, "size" => 47]]],
            self::EC_QUARTILE => ["total" => 1502, "ecc" => 30, "groups" => [["blocks" => 48, "size" => 24], ["blocks" => 14, "size" => 25]]],
            self::EC_HIGH => ["total" => 1142, "ecc" => 30, "groups" => [["blocks" => 42, "size" => 15], ["blocks" => 32, "size" => 16]]]
        ],
        39 => [
            self::EC_LOW => ["total" => 2812, "ecc" => 30, "groups" => [["blocks" => 20, "size" => 117], ["blocks" => 4, "size" => 118]]],
            self::EC_MEDIUM => ["total" => 2216, "ecc" => 28, "groups" => [["blocks" => 40, "size" => 47], ["blocks" => 7, "size" => 48]]],
            self::EC_QUARTILE => ["total" => 1582, "ecc" => 30, "groups" => [["blocks" => 43, "size" => 24], ["blocks" => 22, "size" => 25]]],
            self::EC_HIGH => ["total" => 1222, "ecc" => 30, "groups" => [["blocks" => 10, "size" => 15], ["blocks" => 67, "size" => 16]]]
        ],
        40 => [
            self::EC_LOW => ["total" => 2956, "ecc" => 30, "groups" => [["blocks" => 19, "size" => 118], ["blocks" => 6, "size" => 119]]],
            self::EC_MEDIUM => ["total" => 2334, "ecc" => 28, "groups" => [["blocks" => 18, "size" => 47], ["blocks" => 31, "size" => 48]]],
            self::EC_QUARTILE => ["total" => 1666, "ecc" => 30, "groups" => [["blocks" => 34, "size" => 24], ["blocks" => 34, "size" => 25]]],
            self::EC_HIGH => ["total" => 1276, "ecc" => 30, "groups" => [["blocks" => 20, "size" => 15], ["blocks" => 61, "size" => 16]]]
        ]
    ];
    protected const REMAINDER_BITS = [
        1 => "",
        2 => "0000000",
        3 => "0000000",
        4 => "0000000",
        5 => "0000000",
        6 => "0000000",
        7 => "",
        8 => "",
        9 => "",
        10 => "",
        11 => "",
        12 => "",
        13 => "",
        14 => "000",
        15 => "000",
        16 => "000",
        17 => "000",
        18 => "000",
        19 => "000",
        20 => "000",
        21 => "0000",
        22 => "0000",
        23 => "0000",
        24 => "0000",
        25 => "0000",
        26 => "0000",
        27 => "0000",
        28 => "000",
        29 => "000",
        30 => "000",
        31 => "000",
        32 => "000",
        33 => "000",
        34 => "000",
        35 => "",
        36 => "",
        37 => "",
        38 => "",
        39 => "",
        40 => "",
    ];

    // galois field tables
    public const LOG_TABLE = [
        null, 0, 1, 25, 2, 50, 26, 198, 3, 223, 51, 238, 27, 104, 199, 75,
        4, 100, 224, 14, 52, 141, 239, 129, 28, 193, 105, 248, 200, 8, 76, 113,
        5, 138, 101, 47, 225, 36, 15, 33, 53, 147, 142, 218, 240, 18, 130, 69,
        29, 181, 194, 125, 106, 39, 249, 185, 201, 154, 9, 120, 77, 228, 114, 166,
        6, 191, 139, 98, 102, 221, 48, 253, 226, 152, 37, 179, 16, 145, 34, 136,
        54, 208, 148, 206, 143, 150, 219, 189, 241, 210, 19, 92, 131, 56, 70, 64,
        30, 66, 182, 163, 195, 72, 126, 110, 107, 58, 40, 84, 250, 133, 186, 61,
        202, 94, 155, 159, 10, 21, 121, 43, 78, 212, 229, 172, 115, 243, 167, 87,
        7, 112, 192, 247, 140, 128, 99, 13, 103, 74, 222, 237, 49, 197, 254, 24,
        227, 165, 153, 119, 38, 184, 180, 124, 17, 68, 146, 217, 35, 32, 137, 46,
        55, 63, 209, 91, 149, 188, 207, 205, 144, 135, 151, 178, 220, 252, 190, 97,
        242, 86, 211, 171, 20, 42, 93, 158, 132, 60, 57, 83, 71, 109, 65, 162,
        31, 45, 67, 216, 183, 123, 164, 118, 196, 23, 73, 236, 127, 12, 111, 246,
        108, 161, 59, 82, 41, 157, 85, 170, 251, 96, 134, 177, 187, 204, 62, 90,
        203, 89, 95, 176, 156, 169, 160, 81, 11, 245, 22, 235, 122, 117, 44, 215,
        79, 174, 213, 233, 230, 231, 173, 232, 116, 214, 244, 234, 168, 80, 88, 175,
    ];
    public const EXP_TABLE = [
        1, 2, 4, 8, 16, 32, 64, 128, 29, 58, 116, 232, 205, 135, 19, 38,
        76, 152, 45, 90, 180, 117, 234, 201, 143, 3, 6, 12, 24, 48, 96, 192,
        157, 39, 78, 156, 37, 74, 148, 53, 106, 212, 181, 119, 238, 193, 159, 35,
        70, 140, 5, 10, 20, 40, 80, 160, 93, 186, 105, 210, 185, 111, 222, 161,
        95, 190, 97, 194, 153, 47, 94, 188, 101, 202, 137, 15, 30, 60, 120, 240,
        253, 231, 211, 187, 107, 214, 177, 127, 254, 225, 223, 163, 91, 182, 113, 226,
        217, 175, 67, 134, 17, 34, 68, 136, 13, 26, 52, 104, 208, 189, 103, 206,
        129, 31, 62, 124, 248, 237, 199, 147, 59, 118, 236, 197, 151, 51, 102, 204,
        133, 23, 46, 92, 184, 109, 218, 169, 79, 158, 33, 66, 132, 21, 42, 84,
        168, 77, 154, 41, 82, 164, 85, 170, 73, 146, 57, 114, 228, 213, 183, 115,
        230, 209, 191, 99, 198, 145, 63, 126, 252, 229, 215, 179, 123, 246, 241, 255,
        227, 219, 171, 75, 150, 49, 98, 196, 149, 55, 110, 220, 165, 87, 174, 65,
        130, 25, 50, 100, 200, 141, 7, 14, 28, 56, 112, 224, 221, 167, 83, 166,
        81, 162, 89, 178, 121, 242, 249, 239, 195, 155, 43, 86, 172, 69, 138, 9,
        18, 36, 72, 144, 61, 122, 244, 245, 247, 243, 251, 235, 203, 139, 11, 22,
        44, 88, 176, 125, 250, 233, 207, 131, 27, 54, 108, 216, 173, 71, 142, 1
    ];

    // module matrix properties
    protected string $mode = "";
    protected string $eccLevel = self::EC_MEDIUM;
    protected int $version = 0;
    protected int $characterCount = 0;
    protected int $matrixSize = 0;
    protected array $moduleMatrix = [];
    protected string $data = "";
    protected string $encodedData = "";
    protected array $dataBlocks = [];
    protected array $eccBlocks = [];
    protected array $interleavedBlocks = [];
    protected string $bitstream = "";

    // qr code masking
    protected int $maskVersion = 0;
    protected array $maskMask = [];

    public function __construct(
        string|int $data,
        string     $eccLevel = null,
        string     $mode = null,
        int        $version = null
    )
    {
        $this->initialize(
            data: $data,
            eccLevel: $eccLevel,
            mode: $mode,
            version: $version
        );
    }

    /**
     * Initializes a new Quick Response Code
     *
     * @param string|int $data
     * @param string|null $eccLevel
     * @param string|null $mode
     * @param int|null $version
     * @return void
     */
    public function initialize(
        string|int $data,
        string     $eccLevel = null,
        string     $mode = null,
        int        $version = null,
    ): void
    {
        // input validation
        $this->data = $this->validateData($data);
        $this->eccLevel = $this->validateECCLevel($eccLevel);
        $this->mode = $this->validateMode($mode);
        $this->version = $this->validateVersion($version);

        // generate payload
        $this->encodeData();
        $this->splitDataBlocks();
        $this->generateEccBlocks();
        $this->interleaveBlocks();
        $this->bitstream = $this->bytesToBits($this->interleavedBlocks) . self::REMAINDER_BITS[$this->version];

        // generate module matrix
        $this->generateMatrix();
    }

    ///////////////
    /// UTILITY ///
    ///////////////

    /**
     * Converts a bit string into an array of integer bytes
     *
     * @param string $bits
     * @return array
     */
    protected function bitsToBytes(string $bits): array
    {
        if (0 !== strlen($bits) % 8) {
            throw new LuxiQRException("Number of bits is not a multiple of 8: " . strlen($bits));
        }

        $bytes = [];

        for ($i = 0; $i < strlen($bits); $i += 8) {
            $bytes[] = bindec(substr($bits, $i, 8));
        }

        return $bytes;
    }

    /**
     * Converts an array of integer bytes to a bit string
     *
     * @param array $bytes
     * @return string
     */
    protected function bytesToBits(array $bytes): string
    {
        $bits = "";

        foreach ($bytes as $byte) {
            $bits .= str_pad(
                string: decbin($byte),
                length: 8,
                pad_string: self::PAD_DATA,
                pad_type: STR_PAD_LEFT
            );
        }

        return $bits;
    }
}