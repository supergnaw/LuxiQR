<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR\constants;

class CapacityTables
{

    public const CHARACTER = [
        "1" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 41, Modes::ALPHANUMERIC => 25, Modes::BYTE => 17, Modes::KANJI => 10],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 34, Modes::ALPHANUMERIC => 20, Modes::BYTE => 14, Modes::KANJI => 8],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 27, Modes::ALPHANUMERIC => 16, Modes::BYTE => 11, Modes::KANJI => 7],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 17, Modes::ALPHANUMERIC => 10, Modes::BYTE => 7, Modes::KANJI => 4]
        ],
        "2" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 77, Modes::ALPHANUMERIC => 47, Modes::BYTE => 32, Modes::KANJI => 20],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 63, Modes::ALPHANUMERIC => 38, Modes::BYTE => 26, Modes::KANJI => 16],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 48, Modes::ALPHANUMERIC => 29, Modes::BYTE => 20, Modes::KANJI => 12],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 34, Modes::ALPHANUMERIC => 20, Modes::BYTE => 14, Modes::KANJI => 8]
        ],
        "3" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 127, Modes::ALPHANUMERIC => 77, Modes::BYTE => 53, Modes::KANJI => 32],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 101, Modes::ALPHANUMERIC => 61, Modes::BYTE => 42, Modes::KANJI => 26],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 77, Modes::ALPHANUMERIC => 47, Modes::BYTE => 32, Modes::KANJI => 20],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 58, Modes::ALPHANUMERIC => 35, Modes::BYTE => 24, Modes::KANJI => 15]
        ],
        "4" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 187, Modes::ALPHANUMERIC => 114, Modes::BYTE => 78, Modes::KANJI => 48],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 149, Modes::ALPHANUMERIC => 90, Modes::BYTE => 62, Modes::KANJI => 38],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 111, Modes::ALPHANUMERIC => 67, Modes::BYTE => 46, Modes::KANJI => 28],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 82, Modes::ALPHANUMERIC => 50, Modes::BYTE => 34, Modes::KANJI => 21]
        ],
        "5" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 255, Modes::ALPHANUMERIC => 154, Modes::BYTE => 106, Modes::KANJI => 65],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 202, Modes::ALPHANUMERIC => 122, Modes::BYTE => 84, Modes::KANJI => 52],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 144, Modes::ALPHANUMERIC => 87, Modes::BYTE => 60, Modes::KANJI => 37],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 106, Modes::ALPHANUMERIC => 64, Modes::BYTE => 44, Modes::KANJI => 27]
        ],
        "6" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 322, Modes::ALPHANUMERIC => 195, Modes::BYTE => 134, Modes::KANJI => 82],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 255, Modes::ALPHANUMERIC => 154, Modes::BYTE => 106, Modes::KANJI => 65],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 178, Modes::ALPHANUMERIC => 108, Modes::BYTE => 74, Modes::KANJI => 45],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 139, Modes::ALPHANUMERIC => 84, Modes::BYTE => 58, Modes::KANJI => 36]
        ],
        "7" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 370, Modes::ALPHANUMERIC => 224, Modes::BYTE => 154, Modes::KANJI => 95],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 293, Modes::ALPHANUMERIC => 178, Modes::BYTE => 122, Modes::KANJI => 75],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 207, Modes::ALPHANUMERIC => 125, Modes::BYTE => 86, Modes::KANJI => 53],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 154, Modes::ALPHANUMERIC => 93, Modes::BYTE => 64, Modes::KANJI => 39]
        ],
        "8" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 461, Modes::ALPHANUMERIC => 279, Modes::BYTE => 192, Modes::KANJI => 118],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 365, Modes::ALPHANUMERIC => 221, Modes::BYTE => 152, Modes::KANJI => 93],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 259, Modes::ALPHANUMERIC => 157, Modes::BYTE => 108, Modes::KANJI => 66],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 202, Modes::ALPHANUMERIC => 122, Modes::BYTE => 84, Modes::KANJI => 52]
        ],
        "9" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 552, Modes::ALPHANUMERIC => 335, Modes::BYTE => 230, Modes::KANJI => 141],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 432, Modes::ALPHANUMERIC => 262, Modes::BYTE => 180, Modes::KANJI => 111],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 312, Modes::ALPHANUMERIC => 189, Modes::BYTE => 130, Modes::KANJI => 80],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 235, Modes::ALPHANUMERIC => 143, Modes::BYTE => 98, Modes::KANJI => 60]
        ],
        "10" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 652, Modes::ALPHANUMERIC => 395, Modes::BYTE => 271, Modes::KANJI => 167],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 513, Modes::ALPHANUMERIC => 311, Modes::BYTE => 213, Modes::KANJI => 131],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 364, Modes::ALPHANUMERIC => 221, Modes::BYTE => 151, Modes::KANJI => 93],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 288, Modes::ALPHANUMERIC => 174, Modes::BYTE => 119, Modes::KANJI => 74]
        ],
        "11" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 772, Modes::ALPHANUMERIC => 468, Modes::BYTE => 321, Modes::KANJI => 198],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 604, Modes::ALPHANUMERIC => 366, Modes::BYTE => 251, Modes::KANJI => 155],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 427, Modes::ALPHANUMERIC => 259, Modes::BYTE => 177, Modes::KANJI => 109],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 331, Modes::ALPHANUMERIC => 200, Modes::BYTE => 137, Modes::KANJI => 85]
        ],
        "12" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 883, Modes::ALPHANUMERIC => 535, Modes::BYTE => 367, Modes::KANJI => 226],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 691, Modes::ALPHANUMERIC => 419, Modes::BYTE => 287, Modes::KANJI => 177],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 489, Modes::ALPHANUMERIC => 296, Modes::BYTE => 203, Modes::KANJI => 125],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 374, Modes::ALPHANUMERIC => 227, Modes::BYTE => 155, Modes::KANJI => 96]
        ],
        "13" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 1022, Modes::ALPHANUMERIC => 619, Modes::BYTE => 425, Modes::KANJI => 262],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 796, Modes::ALPHANUMERIC => 483, Modes::BYTE => 331, Modes::KANJI => 204],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 580, Modes::ALPHANUMERIC => 352, Modes::BYTE => 241, Modes::KANJI => 149],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 427, Modes::ALPHANUMERIC => 259, Modes::BYTE => 177, Modes::KANJI => 109]
        ],
        "14" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 1101, Modes::ALPHANUMERIC => 667, Modes::BYTE => 458, Modes::KANJI => 282],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 871, Modes::ALPHANUMERIC => 528, Modes::BYTE => 362, Modes::KANJI => 223],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 621, Modes::ALPHANUMERIC => 376, Modes::BYTE => 258, Modes::KANJI => 159],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 468, Modes::ALPHANUMERIC => 283, Modes::BYTE => 194, Modes::KANJI => 120]
        ],
        "15" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 1250, Modes::ALPHANUMERIC => 758, Modes::BYTE => 520, Modes::KANJI => 320],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 991, Modes::ALPHANUMERIC => 600, Modes::BYTE => 412, Modes::KANJI => 254],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 703, Modes::ALPHANUMERIC => 426, Modes::BYTE => 292, Modes::KANJI => 180],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 530, Modes::ALPHANUMERIC => 321, Modes::BYTE => 220, Modes::KANJI => 136]
        ],
        "16" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 1408, Modes::ALPHANUMERIC => 854, Modes::BYTE => 586, Modes::KANJI => 361],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 1082, Modes::ALPHANUMERIC => 656, Modes::BYTE => 450, Modes::KANJI => 277],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 775, Modes::ALPHANUMERIC => 470, Modes::BYTE => 322, Modes::KANJI => 198],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 602, Modes::ALPHANUMERIC => 365, Modes::BYTE => 250, Modes::KANJI => 154]
        ],
        "17" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 1548, Modes::ALPHANUMERIC => 938, Modes::BYTE => 644, Modes::KANJI => 397],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 1212, Modes::ALPHANUMERIC => 734, Modes::BYTE => 504, Modes::KANJI => 310],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 876, Modes::ALPHANUMERIC => 531, Modes::BYTE => 364, Modes::KANJI => 224],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 674, Modes::ALPHANUMERIC => 408, Modes::BYTE => 280, Modes::KANJI => 173]
        ],
        "18" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 1725, Modes::ALPHANUMERIC => 1046, Modes::BYTE => 718, Modes::KANJI => 442],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 1346, Modes::ALPHANUMERIC => 816, Modes::BYTE => 560, Modes::KANJI => 345],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 948, Modes::ALPHANUMERIC => 574, Modes::BYTE => 394, Modes::KANJI => 243],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 746, Modes::ALPHANUMERIC => 452, Modes::BYTE => 310, Modes::KANJI => 191]
        ],
        "19" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 1903, Modes::ALPHANUMERIC => 1153, Modes::BYTE => 792, Modes::KANJI => 488],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 1500, Modes::ALPHANUMERIC => 909, Modes::BYTE => 624, Modes::KANJI => 384],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 1063, Modes::ALPHANUMERIC => 644, Modes::BYTE => 442, Modes::KANJI => 272],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 813, Modes::ALPHANUMERIC => 493, Modes::BYTE => 338, Modes::KANJI => 208]
        ],
        "20" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 2061, Modes::ALPHANUMERIC => 1249, Modes::BYTE => 858, Modes::KANJI => 528],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 1600, Modes::ALPHANUMERIC => 970, Modes::BYTE => 666, Modes::KANJI => 410],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 1159, Modes::ALPHANUMERIC => 702, Modes::BYTE => 482, Modes::KANJI => 297],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 919, Modes::ALPHANUMERIC => 557, Modes::BYTE => 382, Modes::KANJI => 235]
        ],
        "21" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 2232, Modes::ALPHANUMERIC => 1352, Modes::BYTE => 929, Modes::KANJI => 572],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 1708, Modes::ALPHANUMERIC => 1035, Modes::BYTE => 711, Modes::KANJI => 438],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 1224, Modes::ALPHANUMERIC => 742, Modes::BYTE => 509, Modes::KANJI => 314],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 969, Modes::ALPHANUMERIC => 587, Modes::BYTE => 403, Modes::KANJI => 248]
        ],
        "22" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 2409, Modes::ALPHANUMERIC => 1460, Modes::BYTE => 1003, Modes::KANJI => 618],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 1872, Modes::ALPHANUMERIC => 1134, Modes::BYTE => 779, Modes::KANJI => 480],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 1358, Modes::ALPHANUMERIC => 823, Modes::BYTE => 565, Modes::KANJI => 348],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 1056, Modes::ALPHANUMERIC => 640, Modes::BYTE => 439, Modes::KANJI => 270]
        ],
        "23" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 2620, Modes::ALPHANUMERIC => 1588, Modes::BYTE => 1091, Modes::KANJI => 672],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 2059, Modes::ALPHANUMERIC => 1248, Modes::BYTE => 857, Modes::KANJI => 528],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 1468, Modes::ALPHANUMERIC => 890, Modes::BYTE => 611, Modes::KANJI => 376],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 1108, Modes::ALPHANUMERIC => 672, Modes::BYTE => 461, Modes::KANJI => 284]
        ],
        "24" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 2812, Modes::ALPHANUMERIC => 1704, Modes::BYTE => 1171, Modes::KANJI => 721],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 2188, Modes::ALPHANUMERIC => 1326, Modes::BYTE => 911, Modes::KANJI => 561],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 1588, Modes::ALPHANUMERIC => 963, Modes::BYTE => 661, Modes::KANJI => 407],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 1228, Modes::ALPHANUMERIC => 744, Modes::BYTE => 511, Modes::KANJI => 315]
        ],
        "25" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 3057, Modes::ALPHANUMERIC => 1853, Modes::BYTE => 1273, Modes::KANJI => 784],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 2395, Modes::ALPHANUMERIC => 1451, Modes::BYTE => 997, Modes::KANJI => 614],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 1718, Modes::ALPHANUMERIC => 1041, Modes::BYTE => 715, Modes::KANJI => 440],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 1286, Modes::ALPHANUMERIC => 779, Modes::BYTE => 535, Modes::KANJI => 330]
        ],
        "26" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 3283, Modes::ALPHANUMERIC => 1990, Modes::BYTE => 1367, Modes::KANJI => 842],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 2544, Modes::ALPHANUMERIC => 1542, Modes::BYTE => 1059, Modes::KANJI => 652],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 1804, Modes::ALPHANUMERIC => 1094, Modes::BYTE => 751, Modes::KANJI => 462],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 1425, Modes::ALPHANUMERIC => 864, Modes::BYTE => 593, Modes::KANJI => 365]
        ],
        "27" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 3517, Modes::ALPHANUMERIC => 2132, Modes::BYTE => 1465, Modes::KANJI => 902],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 2701, Modes::ALPHANUMERIC => 1637, Modes::BYTE => 1125, Modes::KANJI => 692],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 1933, Modes::ALPHANUMERIC => 1172, Modes::BYTE => 805, Modes::KANJI => 496],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 1501, Modes::ALPHANUMERIC => 910, Modes::BYTE => 625, Modes::KANJI => 385]
        ],
        "28" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 3669, Modes::ALPHANUMERIC => 2223, Modes::BYTE => 1528, Modes::KANJI => 940],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 2857, Modes::ALPHANUMERIC => 1732, Modes::BYTE => 1190, Modes::KANJI => 732],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 2085, Modes::ALPHANUMERIC => 1263, Modes::BYTE => 868, Modes::KANJI => 534],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 1581, Modes::ALPHANUMERIC => 958, Modes::BYTE => 658, Modes::KANJI => 405]
        ],
        "29" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 3909, Modes::ALPHANUMERIC => 2369, Modes::BYTE => 1628, Modes::KANJI => 1002],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 3035, Modes::ALPHANUMERIC => 1839, Modes::BYTE => 1264, Modes::KANJI => 778],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 2181, Modes::ALPHANUMERIC => 1322, Modes::BYTE => 908, Modes::KANJI => 559],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 1677, Modes::ALPHANUMERIC => 1016, Modes::BYTE => 698, Modes::KANJI => 430]
        ],
        "30" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 4158, Modes::ALPHANUMERIC => 2520, Modes::BYTE => 1732, Modes::KANJI => 1066],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 3289, Modes::ALPHANUMERIC => 1994, Modes::BYTE => 1370, Modes::KANJI => 843],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 2358, Modes::ALPHANUMERIC => 1429, Modes::BYTE => 982, Modes::KANJI => 604],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 1782, Modes::ALPHANUMERIC => 1080, Modes::BYTE => 742, Modes::KANJI => 457]
        ],
        "31" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 4417, Modes::ALPHANUMERIC => 2677, Modes::BYTE => 1840, Modes::KANJI => 1132],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 3486, Modes::ALPHANUMERIC => 2113, Modes::BYTE => 1452, Modes::KANJI => 894],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 2473, Modes::ALPHANUMERIC => 1499, Modes::BYTE => 1030, Modes::KANJI => 634],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 1897, Modes::ALPHANUMERIC => 1150, Modes::BYTE => 790, Modes::KANJI => 486]
        ],
        "32" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 4686, Modes::ALPHANUMERIC => 2840, Modes::BYTE => 1952, Modes::KANJI => 1201],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 3693, Modes::ALPHANUMERIC => 2238, Modes::BYTE => 1538, Modes::KANJI => 947],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 2670, Modes::ALPHANUMERIC => 1618, Modes::BYTE => 1112, Modes::KANJI => 684],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 2022, Modes::ALPHANUMERIC => 1226, Modes::BYTE => 842, Modes::KANJI => 518]
        ],
        "33" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 4965, Modes::ALPHANUMERIC => 3009, Modes::BYTE => 2068, Modes::KANJI => 1273],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 3909, Modes::ALPHANUMERIC => 2369, Modes::BYTE => 1628, Modes::KANJI => 1002],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 2805, Modes::ALPHANUMERIC => 1700, Modes::BYTE => 1168, Modes::KANJI => 719],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 2157, Modes::ALPHANUMERIC => 1307, Modes::BYTE => 898, Modes::KANJI => 553]
        ],
        "34" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 5253, Modes::ALPHANUMERIC => 3183, Modes::BYTE => 2188, Modes::KANJI => 1347],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 4134, Modes::ALPHANUMERIC => 2506, Modes::BYTE => 1722, Modes::KANJI => 1060],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 2949, Modes::ALPHANUMERIC => 1787, Modes::BYTE => 1228, Modes::KANJI => 756],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 2301, Modes::ALPHANUMERIC => 1394, Modes::BYTE => 958, Modes::KANJI => 590]
        ],
        "35" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 5529, Modes::ALPHANUMERIC => 3351, Modes::BYTE => 2303, Modes::KANJI => 1417],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 4343, Modes::ALPHANUMERIC => 2632, Modes::BYTE => 1809, Modes::KANJI => 1113],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 3081, Modes::ALPHANUMERIC => 1867, Modes::BYTE => 1283, Modes::KANJI => 790],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 2361, Modes::ALPHANUMERIC => 1431, Modes::BYTE => 983, Modes::KANJI => 605]
        ],
        "36" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 5836, Modes::ALPHANUMERIC => 3537, Modes::BYTE => 2431, Modes::KANJI => 1496],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 4588, Modes::ALPHANUMERIC => 2780, Modes::BYTE => 1911, Modes::KANJI => 1176],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 3244, Modes::ALPHANUMERIC => 1966, Modes::BYTE => 1351, Modes::KANJI => 832],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 2524, Modes::ALPHANUMERIC => 1530, Modes::BYTE => 1051, Modes::KANJI => 647]
        ],
        "37" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 6153, Modes::ALPHANUMERIC => 3729, Modes::BYTE => 2563, Modes::KANJI => 1577],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 4775, Modes::ALPHANUMERIC => 2894, Modes::BYTE => 1989, Modes::KANJI => 1224],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 3417, Modes::ALPHANUMERIC => 2071, Modes::BYTE => 1423, Modes::KANJI => 876],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 2625, Modes::ALPHANUMERIC => 1591, Modes::BYTE => 1093, Modes::KANJI => 673]
        ],
        "38" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 6479, Modes::ALPHANUMERIC => 3927, Modes::BYTE => 2699, Modes::KANJI => 1661],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 5039, Modes::ALPHANUMERIC => 3054, Modes::BYTE => 2099, Modes::KANJI => 1292],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 3599, Modes::ALPHANUMERIC => 2181, Modes::BYTE => 1499, Modes::KANJI => 923],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 2735, Modes::ALPHANUMERIC => 1658, Modes::BYTE => 1139, Modes::KANJI => 701]
        ],
        "39" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 6743, Modes::ALPHANUMERIC => 4087, Modes::BYTE => 2809, Modes::KANJI => 1729],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 5313, Modes::ALPHANUMERIC => 3220, Modes::BYTE => 2213, Modes::KANJI => 1362],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 3791, Modes::ALPHANUMERIC => 2298, Modes::BYTE => 1579, Modes::KANJI => 972],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 2927, Modes::ALPHANUMERIC => 1774, Modes::BYTE => 1219, Modes::KANJI => 750]
        ],
        "40" => [
            ErrorCorrection::LOW => [Modes::NUMERIC => 7089, Modes::ALPHANUMERIC => 4296, Modes::BYTE => 2953, Modes::KANJI => 1817],
            ErrorCorrection::MEDIUM => [Modes::NUMERIC => 5596, Modes::ALPHANUMERIC => 3391, Modes::BYTE => 2331, Modes::KANJI => 1435],
            ErrorCorrection::QUARTILE => [Modes::NUMERIC => 3993, Modes::ALPHANUMERIC => 2420, Modes::BYTE => 1663, Modes::KANJI => 1024],
            ErrorCorrection::HIGH => [Modes::NUMERIC => 3057, Modes::ALPHANUMERIC => 1852, Modes::BYTE => 1273, Modes::KANJI => 784]
        ]
    ];

    public const BYTE = [
        1 => [
            ErrorCorrection::LOW => ["total" => 19, "ecc" => 7, "groups" => [["blocks" => 1, "size" => 19]]],
            ErrorCorrection::MEDIUM => ["total" => 16, "ecc" => 10, "groups" => [["blocks" => 1, "size" => 16]]],
            ErrorCorrection::QUARTILE => ["total" => 13, "ecc" => 13, "groups" => [["blocks" => 1, "size" => 13]]],
            ErrorCorrection::HIGH => ["total" => 9, "ecc" => 17, "groups" => [["blocks" => 1, "size" => 9]]]
        ],
        2 => [
            ErrorCorrection::LOW => ["total" => 34, "ecc" => 10, "groups" => [["blocks" => 1, "size" => 34]]],
            ErrorCorrection::MEDIUM => ["total" => 28, "ecc" => 16, "groups" => [["blocks" => 1, "size" => 28]]],
            ErrorCorrection::QUARTILE => ["total" => 22, "ecc" => 22, "groups" => [["blocks" => 1, "size" => 22]]],
            ErrorCorrection::HIGH => ["total" => 16, "ecc" => 28, "groups" => [["blocks" => 1, "size" => 16]]]
        ],
        3 => [
            ErrorCorrection::LOW => ["total" => 55, "ecc" => 15, "groups" => [["blocks" => 1, "size" => 55]]],
            ErrorCorrection::MEDIUM => ["total" => 44, "ecc" => 26, "groups" => [["blocks" => 1, "size" => 44]]],
            ErrorCorrection::QUARTILE => ["total" => 34, "ecc" => 18, "groups" => [["blocks" => 2, "size" => 17]]],
            ErrorCorrection::HIGH => ["total" => 26, "ecc" => 22, "groups" => [["blocks" => 2, "size" => 13]]]
        ],
        4 => [
            ErrorCorrection::LOW => ["total" => 80, "ecc" => 20, "groups" => [["blocks" => 1, "size" => 80]]],
            ErrorCorrection::MEDIUM => ["total" => 64, "ecc" => 18, "groups" => [["blocks" => 2, "size" => 32]]],
            ErrorCorrection::QUARTILE => ["total" => 48, "ecc" => 26, "groups" => [["blocks" => 2, "size" => 24]]],
            ErrorCorrection::HIGH => ["total" => 36, "ecc" => 16, "groups" => [["blocks" => 4, "size" => 9]]]
        ],
        5 => [
            ErrorCorrection::LOW => ["total" => 108, "ecc" => 26, "groups" => [["blocks" => 1, "size" => 108]]],
            ErrorCorrection::MEDIUM => ["total" => 86, "ecc" => 24, "groups" => [["blocks" => 2, "size" => 43]]],
            ErrorCorrection::QUARTILE => ["total" => 62, "ecc" => 18, "groups" => [["blocks" => 2, "size" => 15], ["blocks" => 2, "size" => 16]]],
            ErrorCorrection::HIGH => ["total" => 46, "ecc" => 22, "groups" => [["blocks" => 2, "size" => 11], ["blocks" => 2, "size" => 12]]]
        ],
        6 => [
            ErrorCorrection::LOW => ["total" => 136, "ecc" => 18, "groups" => [["blocks" => 2, "size" => 68]]],
            ErrorCorrection::MEDIUM => ["total" => 108, "ecc" => 16, "groups" => [["blocks" => 4, "size" => 27]]],
            ErrorCorrection::QUARTILE => ["total" => 76, "ecc" => 24, "groups" => [["blocks" => 4, "size" => 19]]],
            ErrorCorrection::HIGH => ["total" => 60, "ecc" => 28, "groups" => [["blocks" => 4, "size" => 15]]]
        ],
        7 => [
            ErrorCorrection::LOW => ["total" => 156, "ecc" => 20, "groups" => [["blocks" => 2, "size" => 78]]],
            ErrorCorrection::MEDIUM => ["total" => 124, "ecc" => 18, "groups" => [["blocks" => 4, "size" => 31]]],
            ErrorCorrection::QUARTILE => ["total" => 88, "ecc" => 18, "groups" => [["blocks" => 2, "size" => 14], ["blocks" => 4, "size" => 15]]],
            ErrorCorrection::HIGH => ["total" => 66, "ecc" => 26, "groups" => [["blocks" => 4, "size" => 13], ["blocks" => 1, "size" => 14]]]
        ],
        8 => [
            ErrorCorrection::LOW => ["total" => 194, "ecc" => 24, "groups" => [["blocks" => 2, "size" => 97]]],
            ErrorCorrection::MEDIUM => ["total" => 154, "ecc" => 22, "groups" => [["blocks" => 2, "size" => 38], ["blocks" => 2, "size" => 39]]],
            ErrorCorrection::QUARTILE => ["total" => 110, "ecc" => 22, "groups" => [["blocks" => 4, "size" => 18], ["blocks" => 2, "size" => 19]]],
            ErrorCorrection::HIGH => ["total" => 86, "ecc" => 26, "groups" => [["blocks" => 4, "size" => 14], ["blocks" => 2, "size" => 15]]]
        ],
        9 => [
            ErrorCorrection::LOW => ["total" => 232, "ecc" => 30, "groups" => [["blocks" => 2, "size" => 116]]],
            ErrorCorrection::MEDIUM => ["total" => 182, "ecc" => 22, "groups" => [["blocks" => 3, "size" => 36], ["blocks" => 2, "size" => 37]]],
            ErrorCorrection::QUARTILE => ["total" => 132, "ecc" => 20, "groups" => [["blocks" => 4, "size" => 16], ["blocks" => 4, "size" => 17]]],
            ErrorCorrection::HIGH => ["total" => 100, "ecc" => 24, "groups" => [["blocks" => 4, "size" => 12], ["blocks" => 4, "size" => 13]]]
        ],
        10 => [
            ErrorCorrection::LOW => ["total" => 274, "ecc" => 18, "groups" => [["blocks" => 2, "size" => 68], ["blocks" => 2, "size" => 69]]],
            ErrorCorrection::MEDIUM => ["total" => 216, "ecc" => 26, "groups" => [["blocks" => 4, "size" => 43], ["blocks" => 1, "size" => 44]]],
            ErrorCorrection::QUARTILE => ["total" => 154, "ecc" => 24, "groups" => [["blocks" => 6, "size" => 19], ["blocks" => 2, "size" => 20]]],
            ErrorCorrection::HIGH => ["total" => 122, "ecc" => 28, "groups" => [["blocks" => 6, "size" => 15], ["blocks" => 2, "size" => 16]]]
        ],
        11 => [
            ErrorCorrection::LOW => ["total" => 324, "ecc" => 20, "groups" => [["blocks" => 4, "size" => 81]]],
            ErrorCorrection::MEDIUM => ["total" => 254, "ecc" => 30, "groups" => [["blocks" => 1, "size" => 50], ["blocks" => 4, "size" => 51]]],
            ErrorCorrection::QUARTILE => ["total" => 180, "ecc" => 28, "groups" => [["blocks" => 4, "size" => 22], ["blocks" => 4, "size" => 23]]],
            ErrorCorrection::HIGH => ["total" => 140, "ecc" => 24, "groups" => [["blocks" => 3, "size" => 12], ["blocks" => 8, "size" => 13]]]
        ],
        12 => [
            ErrorCorrection::LOW => ["total" => 370, "ecc" => 24, "groups" => [["blocks" => 2, "size" => 92], ["blocks" => 2, "size" => 93]]],
            ErrorCorrection::MEDIUM => ["total" => 290, "ecc" => 22, "groups" => [["blocks" => 6, "size" => 36], ["blocks" => 2, "size" => 37]]],
            ErrorCorrection::QUARTILE => ["total" => 206, "ecc" => 26, "groups" => [["blocks" => 4, "size" => 20], ["blocks" => 6, "size" => 21]]],
            ErrorCorrection::HIGH => ["total" => 158, "ecc" => 28, "groups" => [["blocks" => 7, "size" => 14], ["blocks" => 4, "size" => 15]]]
        ],
        13 => [
            ErrorCorrection::LOW => ["total" => 428, "ecc" => 26, "groups" => [["blocks" => 4, "size" => 107]]],
            ErrorCorrection::MEDIUM => ["total" => 334, "ecc" => 22, "groups" => [["blocks" => 8, "size" => 37], ["blocks" => 1, "size" => 38]]],
            ErrorCorrection::QUARTILE => ["total" => 244, "ecc" => 24, "groups" => [["blocks" => 8, "size" => 20], ["blocks" => 4, "size" => 21]]],
            ErrorCorrection::HIGH => ["total" => 180, "ecc" => 22, "groups" => [["blocks" => 12, "size" => 11], ["blocks" => 4, "size" => 12]]]
        ],
        14 => [
            ErrorCorrection::LOW => ["total" => 461, "ecc" => 30, "groups" => [["blocks" => 3, "size" => 115], ["blocks" => 1, "size" => 116]]],
            ErrorCorrection::MEDIUM => ["total" => 365, "ecc" => 24, "groups" => [["blocks" => 4, "size" => 40], ["blocks" => 5, "size" => 41]]],
            ErrorCorrection::QUARTILE => ["total" => 261, "ecc" => 20, "groups" => [["blocks" => 11, "size" => 16], ["blocks" => 5, "size" => 17]]],
            ErrorCorrection::HIGH => ["total" => 197, "ecc" => 24, "groups" => [["blocks" => 11, "size" => 12], ["blocks" => 5, "size" => 13]]]
        ],
        15 => [
            ErrorCorrection::LOW => ["total" => 523, "ecc" => 22, "groups" => [["blocks" => 5, "size" => 87], ["blocks" => 1, "size" => 88]]],
            ErrorCorrection::MEDIUM => ["total" => 415, "ecc" => 24, "groups" => [["blocks" => 5, "size" => 41], ["blocks" => 5, "size" => 42]]],
            ErrorCorrection::QUARTILE => ["total" => 295, "ecc" => 30, "groups" => [["blocks" => 5, "size" => 24], ["blocks" => 7, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 223, "ecc" => 24, "groups" => [["blocks" => 11, "size" => 12], ["blocks" => 7, "size" => 13]]]
        ],
        16 => [
            ErrorCorrection::LOW => ["total" => 589, "ecc" => 24, "groups" => [["blocks" => 5, "size" => 98], ["blocks" => 1, "size" => 99]]],
            ErrorCorrection::MEDIUM => ["total" => 453, "ecc" => 28, "groups" => [["blocks" => 7, "size" => 45], ["blocks" => 3, "size" => 46]]],
            ErrorCorrection::QUARTILE => ["total" => 325, "ecc" => 24, "groups" => [["blocks" => 15, "size" => 19], ["blocks" => 2, "size" => 20]]],
            ErrorCorrection::HIGH => ["total" => 253, "ecc" => 30, "groups" => [["blocks" => 3, "size" => 15], ["blocks" => 13, "size" => 16]]]
        ],
        17 => [
            ErrorCorrection::LOW => ["total" => 647, "ecc" => 28, "groups" => [["blocks" => 1, "size" => 107], ["blocks" => 5, "size" => 108]]],
            ErrorCorrection::MEDIUM => ["total" => 507, "ecc" => 28, "groups" => [["blocks" => 10, "size" => 46], ["blocks" => 1, "size" => 47]]],
            ErrorCorrection::QUARTILE => ["total" => 367, "ecc" => 28, "groups" => [["blocks" => 1, "size" => 22], ["blocks" => 15, "size" => 23]]],
            ErrorCorrection::HIGH => ["total" => 283, "ecc" => 28, "groups" => [["blocks" => 2, "size" => 14], ["blocks" => 17, "size" => 15]]]
        ],
        18 => [
            ErrorCorrection::LOW => ["total" => 721, "ecc" => 30, "groups" => [["blocks" => 5, "size" => 120], ["blocks" => 1, "size" => 121]]],
            ErrorCorrection::MEDIUM => ["total" => 563, "ecc" => 26, "groups" => [["blocks" => 9, "size" => 43], ["blocks" => 4, "size" => 44]]],
            ErrorCorrection::QUARTILE => ["total" => 397, "ecc" => 28, "groups" => [["blocks" => 17, "size" => 22], ["blocks" => 1, "size" => 23]]],
            ErrorCorrection::HIGH => ["total" => 313, "ecc" => 28, "groups" => [["blocks" => 2, "size" => 14], ["blocks" => 19, "size" => 15]]]
        ],
        19 => [
            ErrorCorrection::LOW => ["total" => 795, "ecc" => 28, "groups" => [["blocks" => 3, "size" => 113], ["blocks" => 4, "size" => 114]]],
            ErrorCorrection::MEDIUM => ["total" => 627, "ecc" => 26, "groups" => [["blocks" => 3, "size" => 44], ["blocks" => 11, "size" => 45]]],
            ErrorCorrection::QUARTILE => ["total" => 445, "ecc" => 26, "groups" => [["blocks" => 17, "size" => 21], ["blocks" => 4, "size" => 22]]],
            ErrorCorrection::HIGH => ["total" => 341, "ecc" => 26, "groups" => [["blocks" => 9, "size" => 13], ["blocks" => 16, "size" => 14]]]
        ],
        20 => [
            ErrorCorrection::LOW => ["total" => 861, "ecc" => 28, "groups" => [["blocks" => 3, "size" => 107], ["blocks" => 5, "size" => 108]]],
            ErrorCorrection::MEDIUM => ["total" => 669, "ecc" => 26, "groups" => [["blocks" => 3, "size" => 41], ["blocks" => 13, "size" => 42]]],
            ErrorCorrection::QUARTILE => ["total" => 485, "ecc" => 30, "groups" => [["blocks" => 15, "size" => 24], ["blocks" => 5, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 385, "ecc" => 28, "groups" => [["blocks" => 15, "size" => 15], ["blocks" => 10, "size" => 16]]]
        ],
        21 => [
            ErrorCorrection::LOW => ["total" => 932, "ecc" => 28, "groups" => [["blocks" => 4, "size" => 116], ["blocks" => 4, "size" => 117]]],
            ErrorCorrection::MEDIUM => ["total" => 714, "ecc" => 26, "groups" => [["blocks" => 17, "size" => 42]]],
            ErrorCorrection::QUARTILE => ["total" => 512, "ecc" => 28, "groups" => [["blocks" => 17, "size" => 22], ["blocks" => 6, "size" => 23]]],
            ErrorCorrection::HIGH => ["total" => 406, "ecc" => 30, "groups" => [["blocks" => 19, "size" => 16], ["blocks" => 6, "size" => 17]]]
        ],
        22 => [
            ErrorCorrection::LOW => ["total" => 1006, "ecc" => 28, "groups" => [["blocks" => 2, "size" => 111], ["blocks" => 7, "size" => 112]]],
            ErrorCorrection::MEDIUM => ["total" => 782, "ecc" => 28, "groups" => [["blocks" => 17, "size" => 46]]],
            ErrorCorrection::QUARTILE => ["total" => 568, "ecc" => 30, "groups" => [["blocks" => 7, "size" => 24], ["blocks" => 16, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 442, "ecc" => 24, "groups" => [["blocks" => 34, "size" => 13]]]
        ],
        23 => [
            ErrorCorrection::LOW => ["total" => 1094, "ecc" => 30, "groups" => [["blocks" => 4, "size" => 121], ["blocks" => 5, "size" => 122]]],
            ErrorCorrection::MEDIUM => ["total" => 860, "ecc" => 28, "groups" => [["blocks" => 4, "size" => 47], ["blocks" => 14, "size" => 48]]],
            ErrorCorrection::QUARTILE => ["total" => 614, "ecc" => 30, "groups" => [["blocks" => 11, "size" => 24], ["blocks" => 14, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 464, "ecc" => 30, "groups" => [["blocks" => 16, "size" => 15], ["blocks" => 14, "size" => 16]]]
        ],
        24 => [
            ErrorCorrection::LOW => ["total" => 1174, "ecc" => 30, "groups" => [["blocks" => 6, "size" => 117], ["blocks" => 4, "size" => 118]]],
            ErrorCorrection::MEDIUM => ["total" => 914, "ecc" => 28, "groups" => [["blocks" => 6, "size" => 45], ["blocks" => 14, "size" => 46]]],
            ErrorCorrection::QUARTILE => ["total" => 664, "ecc" => 30, "groups" => [["blocks" => 11, "size" => 24], ["blocks" => 16, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 514, "ecc" => 30, "groups" => [["blocks" => 30, "size" => 16], ["blocks" => 2, "size" => 17]]]
        ],
        25 => [
            ErrorCorrection::LOW => ["total" => 1276, "ecc" => 26, "groups" => [["blocks" => 8, "size" => 106], ["blocks" => 4, "size" => 107]]],
            ErrorCorrection::MEDIUM => ["total" => 1000, "ecc" => 28, "groups" => [["blocks" => 8, "size" => 47], ["blocks" => 13, "size" => 48]]],
            ErrorCorrection::QUARTILE => ["total" => 718, "ecc" => 30, "groups" => [["blocks" => 7, "size" => 24], ["blocks" => 22, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 538, "ecc" => 30, "groups" => [["blocks" => 22, "size" => 15], ["blocks" => 13, "size" => 16]]]
        ],
        26 => [
            ErrorCorrection::LOW => ["total" => 1370, "ecc" => 28, "groups" => [["blocks" => 10, "size" => 114], ["blocks" => 2, "size" => 115]]],
            ErrorCorrection::MEDIUM => ["total" => 1062, "ecc" => 28, "groups" => [["blocks" => 19, "size" => 46], ["blocks" => 4, "size" => 47]]],
            ErrorCorrection::QUARTILE => ["total" => 754, "ecc" => 28, "groups" => [["blocks" => 28, "size" => 22], ["blocks" => 6, "size" => 23]]],
            ErrorCorrection::HIGH => ["total" => 596, "ecc" => 30, "groups" => [["blocks" => 33, "size" => 16], ["blocks" => 4, "size" => 17]]]
        ],
        27 => [
            ErrorCorrection::LOW => ["total" => 1468, "ecc" => 30, "groups" => [["blocks" => 8, "size" => 122], ["blocks" => 4, "size" => 123]]],
            ErrorCorrection::MEDIUM => ["total" => 1128, "ecc" => 28, "groups" => [["blocks" => 22, "size" => 45], ["blocks" => 3, "size" => 46]]],
            ErrorCorrection::QUARTILE => ["total" => 808, "ecc" => 30, "groups" => [["blocks" => 8, "size" => 23], ["blocks" => 26, "size" => 24]]],
            ErrorCorrection::HIGH => ["total" => 628, "ecc" => 30, "groups" => [["blocks" => 12, "size" => 15], ["blocks" => 28, "size" => 16]]]
        ],
        28 => [
            ErrorCorrection::LOW => ["total" => 1531, "ecc" => 30, "groups" => [["blocks" => 3, "size" => 117], ["blocks" => 10, "size" => 118]]],
            ErrorCorrection::MEDIUM => ["total" => 1193, "ecc" => 28, "groups" => [["blocks" => 3, "size" => 45], ["blocks" => 23, "size" => 46]]],
            ErrorCorrection::QUARTILE => ["total" => 871, "ecc" => 30, "groups" => [["blocks" => 4, "size" => 24], ["blocks" => 31, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 661, "ecc" => 30, "groups" => [["blocks" => 11, "size" => 15], ["blocks" => 31, "size" => 16]]]
        ],
        29 => [
            ErrorCorrection::LOW => ["total" => 1631, "ecc" => 30, "groups" => [["blocks" => 7, "size" => 116], ["blocks" => 7, "size" => 117]]],
            ErrorCorrection::MEDIUM => ["total" => 1267, "ecc" => 28, "groups" => [["blocks" => 21, "size" => 45], ["blocks" => 7, "size" => 46]]],
            ErrorCorrection::QUARTILE => ["total" => 911, "ecc" => 30, "groups" => [["blocks" => 1, "size" => 23], ["blocks" => 37, "size" => 24]]],
            ErrorCorrection::HIGH => ["total" => 701, "ecc" => 30, "groups" => [["blocks" => 19, "size" => 15], ["blocks" => 26, "size" => 16]]]
        ],
        30 => [
            ErrorCorrection::LOW => ["total" => 1735, "ecc" => 30, "groups" => [["blocks" => 5, "size" => 115], ["blocks" => 10, "size" => 116]]],
            ErrorCorrection::MEDIUM => ["total" => 1373, "ecc" => 28, "groups" => [["blocks" => 19, "size" => 47], ["blocks" => 10, "size" => 48]]],
            ErrorCorrection::QUARTILE => ["total" => 985, "ecc" => 30, "groups" => [["blocks" => 15, "size" => 24], ["blocks" => 25, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 745, "ecc" => 30, "groups" => [["blocks" => 23, "size" => 15], ["blocks" => 25, "size" => 16]]]
        ],
        31 => [
            ErrorCorrection::LOW => ["total" => 1843, "ecc" => 30, "groups" => [["blocks" => 13, "size" => 115], ["blocks" => 3, "size" => 116]]],
            ErrorCorrection::MEDIUM => ["total" => 1455, "ecc" => 28, "groups" => [["blocks" => 2, "size" => 46], ["blocks" => 29, "size" => 47]]],
            ErrorCorrection::QUARTILE => ["total" => 1033, "ecc" => 30, "groups" => [["blocks" => 42, "size" => 24], ["blocks" => 1, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 793, "ecc" => 30, "groups" => [["blocks" => 23, "size" => 15], ["blocks" => 28, "size" => 16]]]
        ],
        32 => [
            ErrorCorrection::LOW => ["total" => 1955, "ecc" => 30, "groups" => [["blocks" => 17, "size" => 115]]],
            ErrorCorrection::MEDIUM => ["total" => 1541, "ecc" => 28, "groups" => [["blocks" => 10, "size" => 46], ["blocks" => 23, "size" => 47]]],
            ErrorCorrection::QUARTILE => ["total" => 1115, "ecc" => 30, "groups" => [["blocks" => 10, "size" => 24], ["blocks" => 35, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 845, "ecc" => 30, "groups" => [["blocks" => 19, "size" => 15], ["blocks" => 35, "size" => 16]]]
        ],
        33 => [
            ErrorCorrection::LOW => ["total" => 2071, "ecc" => 30, "groups" => [["blocks" => 17, "size" => 115], ["blocks" => 1, "size" => 116]]],
            ErrorCorrection::MEDIUM => ["total" => 1631, "ecc" => 28, "groups" => [["blocks" => 14, "size" => 46], ["blocks" => 21, "size" => 47]]],
            ErrorCorrection::QUARTILE => ["total" => 1171, "ecc" => 30, "groups" => [["blocks" => 29, "size" => 24], ["blocks" => 19, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 901, "ecc" => 30, "groups" => [["blocks" => 11, "size" => 15], ["blocks" => 46, "size" => 16]]]
        ],
        34 => [
            ErrorCorrection::LOW => ["total" => 2191, "ecc" => 30, "groups" => [["blocks" => 13, "size" => 115], ["blocks" => 6, "size" => 116]]],
            ErrorCorrection::MEDIUM => ["total" => 1725, "ecc" => 28, "groups" => [["blocks" => 14, "size" => 46], ["blocks" => 23, "size" => 47]]],
            ErrorCorrection::QUARTILE => ["total" => 1231, "ecc" => 30, "groups" => [["blocks" => 44, "size" => 24], ["blocks" => 7, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 961, "ecc" => 30, "groups" => [["blocks" => 59, "size" => 16], ["blocks" => 1, "size" => 17]]]
        ],
        35 => [
            ErrorCorrection::LOW => ["total" => 2306, "ecc" => 30, "groups" => [["blocks" => 12, "size" => 121], ["blocks" => 7, "size" => 122]]],
            ErrorCorrection::MEDIUM => ["total" => 1812, "ecc" => 28, "groups" => [["blocks" => 12, "size" => 47], ["blocks" => 26, "size" => 48]]],
            ErrorCorrection::QUARTILE => ["total" => 1286, "ecc" => 30, "groups" => [["blocks" => 39, "size" => 24], ["blocks" => 14, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 986, "ecc" => 30, "groups" => [["blocks" => 22, "size" => 15], ["blocks" => 41, "size" => 16]]]
        ],
        36 => [
            ErrorCorrection::LOW => ["total" => 2434, "ecc" => 30, "groups" => [["blocks" => 6, "size" => 121], ["blocks" => 14, "size" => 122]]],
            ErrorCorrection::MEDIUM => ["total" => 1914, "ecc" => 28, "groups" => [["blocks" => 6, "size" => 47], ["blocks" => 34, "size" => 48]]],
            ErrorCorrection::QUARTILE => ["total" => 1354, "ecc" => 30, "groups" => [["blocks" => 46, "size" => 24], ["blocks" => 10, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 1054, "ecc" => 30, "groups" => [["blocks" => 2, "size" => 15], ["blocks" => 64, "size" => 16]]]
        ],
        37 => [
            ErrorCorrection::LOW => ["total" => 2566, "ecc" => 30, "groups" => [["blocks" => 17, "size" => 122], ["blocks" => 4, "size" => 123]]],
            ErrorCorrection::MEDIUM => ["total" => 1992, "ecc" => 28, "groups" => [["blocks" => 29, "size" => 46], ["blocks" => 14, "size" => 47]]],
            ErrorCorrection::QUARTILE => ["total" => 1426, "ecc" => 30, "groups" => [["blocks" => 49, "size" => 24], ["blocks" => 10, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 1096, "ecc" => 30, "groups" => [["blocks" => 24, "size" => 15], ["blocks" => 46, "size" => 16]]]
        ],
        38 => [
            ErrorCorrection::LOW => ["total" => 2702, "ecc" => 30, "groups" => [["blocks" => 4, "size" => 122], ["blocks" => 18, "size" => 123]]],
            ErrorCorrection::MEDIUM => ["total" => 2102, "ecc" => 28, "groups" => [["blocks" => 13, "size" => 46], ["blocks" => 32, "size" => 47]]],
            ErrorCorrection::QUARTILE => ["total" => 1502, "ecc" => 30, "groups" => [["blocks" => 48, "size" => 24], ["blocks" => 14, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 1142, "ecc" => 30, "groups" => [["blocks" => 42, "size" => 15], ["blocks" => 32, "size" => 16]]]
        ],
        39 => [
            ErrorCorrection::LOW => ["total" => 2812, "ecc" => 30, "groups" => [["blocks" => 20, "size" => 117], ["blocks" => 4, "size" => 118]]],
            ErrorCorrection::MEDIUM => ["total" => 2216, "ecc" => 28, "groups" => [["blocks" => 40, "size" => 47], ["blocks" => 7, "size" => 48]]],
            ErrorCorrection::QUARTILE => ["total" => 1582, "ecc" => 30, "groups" => [["blocks" => 43, "size" => 24], ["blocks" => 22, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 1222, "ecc" => 30, "groups" => [["blocks" => 10, "size" => 15], ["blocks" => 67, "size" => 16]]]
        ],
        40 => [
            ErrorCorrection::LOW => ["total" => 2956, "ecc" => 30, "groups" => [["blocks" => 19, "size" => 118], ["blocks" => 6, "size" => 119]]],
            ErrorCorrection::MEDIUM => ["total" => 2334, "ecc" => 28, "groups" => [["blocks" => 18, "size" => 47], ["blocks" => 31, "size" => 48]]],
            ErrorCorrection::QUARTILE => ["total" => 1666, "ecc" => 30, "groups" => [["blocks" => 34, "size" => 24], ["blocks" => 34, "size" => 25]]],
            ErrorCorrection::HIGH => ["total" => 1276, "ecc" => 30, "groups" => [["blocks" => 20, "size" => 15], ["blocks" => 61, "size" => 16]]]
        ]
    ];

}