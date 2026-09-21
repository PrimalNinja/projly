<?php

/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Blowfish 4 PHP v0.0.7-dev |
// | (BLOWFISH encryption algorithm implementation) |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001 Bjoern Kalkbrenner <terminar@cyberphoria.org> |
// | Most recent version available on http://www.cyberphoria.org/ |
// +----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License as published by |
// | the Free Software Foundation; either version 2 of the License, or |
// | (at your option) any later version. |
// | |
// | This program is distributed in the hope that it will be useful, |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the |
// | GNU General Public License for more details. |
// | |
// | You should have received a copy of the GNU General Public License |
// | along with this program; if not, write to the Free Software |
// | Foundation, Inc., 59 Temple Place - Suite 330, |
// | Boston, MA 02111-1307, USA |
// +----------------------------------------------------------------------+
// | Based on _THE BLOWFISH ENCRYPTION ALGORITHM_ by Bruce Schneier, |
// | Revised code--3/20/94 |
// | |
// | Authors: Jim Conger, 5/96, (Converted to C++ class) |
// | Bjoern Kalkbrenner <terminar@cyberphoria.org>, 5/2001 |
// | (Converted to PHP class) |
// | |
// | add() function stolen from Chris Monson (chris@bouncingchairs.net), |
// | GPL'ed SHA implementation v1.0 * |
// +----------------------------------------------------------------------+
// | Changelog: |
// | v0.0.7-dev 2001/06/01: Code cleanup, formation and comments!!! |
// | v0.0.6-dev 2001/05/31: First working implementation written in PHP |
// +----------------------------------------------------------------------+
// | Example of using Blow-4-PHP: |
// | |
// | $blowfish = new Blowfish("testkey",7); |
// | $data = "testdata"; |
// | $data_len = strlen($data); |
// | $blowfish->Encrypt($data,$data_len); |
// | $blowfish->Decrypt($data,$data_len); |
// | |
// | Notes: |
// | Like other implementations, this code is not optimized for speed! |
// | The slowest part of all is the initialisation with the key of the |
// | sboxes, because Encipher() is called 4*256 in the Blowfish() |
// | function. Some mini-tests showed the following function-runtime: |
// | time useless machine description |
// | --------- --------------------------------------------------------- |
// | 7-8 secs PII-400 linux workstation under heavy load |
// | 4-5 secs PIII-600 linux server |
// | 4-5 secs webhosting provider (heavy load till up to12 secs!) |
// | |
// +----------------------------------------------------------------------+
//
// $Id$ (cvs not used yet)

class Blowfish
{

    public $P;
    public $S;

/**
 * initial P array
 *
 * recalculated initial pboxes from hex to integer because php
 * doesn't like hex-values (hex will be recognized as double)
 *
 * array-size [18]
 */
    public $Init_P = array(
        608135816, -2052912941, 320440878, 57701188,
        -1542899678, 698298832, 137296536, -330404727,
        1160258022, 953160567, -1101764913, 887688300,
        -1062458953, -914599715, 1065670069, -1253635817,
        -1843997223, -1988494565,
    );

/**
 * initial S array
 *
 * recalculated initial sboxes from hex to integer because php
 * doesn't like hex-values (hex will be recognized as double)
 *
 * array-size [4][256]
 */
    public $Init_S = array(
        array(
            -785314906, -1730169428, 805139163, -803545161,
            -1193168915, 1780907670, -1166241723, -248741991,
            614570311, -1282315017, 134345442, -2054226922,
            1667834072, 1901547113, -1537671517, -191677058,
            227898511, 1921955416, 1904987480, -2112533778,
            2069144605, -1034266187, -1674521287, 720527379,
            -976113629, 677414384, -901678824, -1193592593,
            -1904616272, 1614419982, 1822297739, -1340175810,
            -686458943, -1120842969, 2024746970, 1432378464,
            -430627341, -1437226092, 1464375394, 1676153920,
            1439316330, 715854006, -1261675468, 289532110,
            -1588296017, 2087905683, -1276242927, 1668267050,
            732546397, 1947742710, -832815594, -1685613794,
            -1344882125, 1814351708, 2050118529, 680887927,
            999245976, 1800124847, -994056165, 1713906067,
            1641548236, -81679983, 1216130144, 1575780402,
            -276538019, -377129551, -601480446, -345695352,
            596196993, -745100091, 258830323, -2081144263,
            772490370, -1534844924, 1774776394, -1642095778,
            566650946, -152474470, 1728879713, -1412200208,
            1783734482, -665571480, -1777359064, -1420741725,
            1861159788, 326777828, -1170476976, 2130389656,
            -1578015459, 967770486, 1724537150, -2109534584,
            -1930525159, 1164943284, 2105845187, 998989502,
            -529566248, -2050940813, 1075463327, 1455516326,
            1322494562, 910128902, 469688178, 1117454909,
            936433444, -804646328, -619713837, 1240580251,
            122909385, -2137449605, 634681816, -152510729,
            -469872614, -1233564613, -1754472259, 79693498,
            -1045868618, 1084186820, 1583128258, 426386531,
            1761308591, 1047286709, 322548459, 995290223,
            1845252383, -1691314900, -863943356, -1352745719,
            -1092366332, -567063811, 1712269319, 422464435,
            -1060394921, 1170764815, -771006663, -1177289765,
            1434042557, 442511882, -694091578, 1076654713,
            1738483198, -81812532, -1901729288, -617471240,
            1014306527, -43947243, 793779912, -1392160085,
            842905082, -48003232, 1395751752, 1040244610,
            -1638115397, -898659168, 445077038, -552113701,
            -717051658, 679411651, -1402522938, -1940957837,
            1767581616, -1144366904, -503340195, -1192226400,
            284835224, -48135240, 1258075500, 768725851,
            -1705778055, -1225243291, -762426948, 1274779536,
            -505548070, -1530167757, 1660621633, -823867672,
            -283063590, 913787905, -797008130, 737222580,
            -1780753843, -1366257256, -357724559, 1804850592,
            -795946544, -1345903136, -1908647121, -1904896841,
            -1879645445, -233690268, -2004305902, -1878134756,
            1336762016, 1754252060, -774901359, -1280786003,
            791618072, -1106372745, -361419266, -1962795103,
            -442446833, -1250986776, 413987798, -829824359,
            -1264037920, -49028937, 2093235073, -760370983,
            375366246, -2137688315, -1815317740, 555357303,
            -424861595, 2008414854, -950779147, -73583153,
            -338841844, 2067696032, -700376109, -1373733303,
            2428461, 544322398, 577241275, 1471733935,
            610547355, -267798242, 1432588573, 1507829418,
            2025931657, -648391809, 545086370, 48609733,
            -2094660746, 1653985193, 298326376, 1316178497,
            -1287180854, 2064951626, 458293330, -1705826027,
            -703637697, -1130641692, 727753846, -2115603456,
            146436021, 1461446943, -224990101, 705550613,
            -1235000031, -407242314, -13368018, -981117340,
            1404054877, -1449160799, 146425753, 1854211946,
        ),
        array(
            1266315497, -1246549692, -613086930, -1004984797,
            -1385257296, 1235738493, -1662099272, -1880247706,
            -324367247, 1771706367, 1449415276, -1028546847,
            422970021, 1963543593, -1604775104, -468174274,
            1062508698, 1531092325, 1804592342, -1711849514,
            -1580033017, -269995787, 1294809318, -265986623,
            1289560198, -2072974554, 1669523910, 35572830,
            157838143, 1052438473, 1016535060, 1802137761,
            1753167236, 1386275462, -1214491899, -1437595849,
            1040679964, 2145300060, -1904392980, 1461121720,
            -1338320329, -263189491, -266592508, 33600511,
            -1374882534, 1018524850, 629373528, -603381315,
            -779021319, 2091462646, -1808644237, 586499841,
            988145025, 935516892, -927631820, -1695294041,
            -1455136442, 265290510, -322386114, -1535828415,
            -499593831, 1005194799, 847297441, 406762289,
            1314163512, 1332590856, 1866599683, -167115585,
            750260880, 613907577, 1450815602, -1129346641,
            -560302305, -644675568, -1282691566, -590397650,
            1427272223, 778793252, 1343938022, -1618686585,
            2052605720, 1946737175, -1130390852, -380928628,
            -327488454, -612033030, 1661551462, -1000029230,
            -283371449, 840292616, -582796489, 616741398,
            312560963, 711312465, 1351876610, 322626781,
            1910503582, 271666773, -2119403562, 1594956187,
            70604529, -677132437, 1007753275, 1495573769,
            -225450259, -1745748998, -1631928532, 504708206,
            -2031925904, -353800271, -2045878774, 1514023603,
            1998579484, 1312622330, 694541497, -1712906993,
            -2143385130, 1382467621, 776784248, -1676627094,
            -971698502, -1797068168, -1510196141, 503983604,
            -218673497, 907881277, 423175695, 432175456,
            1378068232, -149744970, -340918674, -356311194,
            -474200683, -1501837181, -1317062703, 26017576,
            -1020076561, -1100195163, 1700274565, 1756076034,
            -288447217, -617638597, 720338349, 1533947780,
            354530856, 688349552, -321042571, 1637815568,
            332179504, -345916010, 53804574, -1442618417,
            -1250730864, 1282449977, -711025141, -877994476,
            -288586052, 1617046695, -1666491221, -1292663698,
            1686838959, 431878346, -1608291911, 1700445008,
            1080580658, 1009431731, 832498133, -1071531785,
            -1688990951, -2023776103, -1778935426, 1648197032,
            -130578278, -1746719369, 300782431, 375919233,
            238389289, -941219882, -1763778655, 2019080857,
            1475708069, 455242339, -1685863425, 448939670,
            -843904277, 1395535956, -1881585436, 1841049896,
            1491858159, 885456874, -30872223, -293847949,
            1565136089, -396052509, 1108368660, 540939232,
            1173283510, -1549095958, -613658859, -87339056,
            -951913406, -278217803, 1699691293, 1103962373,
            -669091426, -2038084153, -464828566, 1031889488,
            -815619598, 1535977030, -58162272, -1043876189,
            2132092099, 1774941330, 1199868427, 1452454533,
            157007616, -1390851939, 342012276, 595725824,
            1480756522, 206960106, 497939518, 591360097,
            863170706, -1919713727, -698356495, 1814182875,
            2094937945, -873565088, 1082520231, -831049106,
            -1509457788, 435703966, -386934699, 1641649973,
            -1452693590, -989067582, 1510255612, -2146710820,
            -1639679442, -1018874748, -36346107, 236887753,
            -613164077, 274041037, 1734335097, -479771840,
            -976997275, 1899903192, 1026095262, -244449504,
            356393447, -1884275382, -421290197, -612127241,
        ),
        array(
            -381855128, -1803468553, -162781668, -1805047500,
            1091903735, 1979897079, -1124832466, -727580568,
            -737663887, 857797738, 1136121015, 1342202287,
            507115054, -1759230650, 337727348, -1081374656,
            1301675037, -1766485585, 1895095763, 1721773893,
            -1078195732, 62756741, 2142006736, 835421444,
            -1762973773, 1442658625, -635090970, -1412822374,
            676362277, 1392781812, 170690266, -373920261,
            1759253602, -683120384, 1745797284, 664899054,
            1329594018, -393761396, -1249058810, 2062866102,
            -1429332356, -751345684, -830954599, 1080764994,
            553557557, -638351943, -298199125, 991055499,
            499776247, 1265440854, 648242737, -354183246,
            980351604, -581221582, 1749149687, -898096901,
            -83167922, -654396521, 1161844396, -1169648345,
            1431517754, 545492359, -26498633, -795437749,
            1437099964, -1592419752, -861329053, -1713251533,
            -1507177898, 1060185593, 1593081372, -1876348548,
            -34019326, 69676912, -2135222948, 86519011,
            -1782508216, -456757982, 1220612927, -955283748,
            133810670, 1090789135, 1078426020, 1569222167,
            845107691, -711212847, -222510705, 1091646820,
            628848692, 1613405280, -537335645, 526609435,
            236106946, 48312990, -1352249391, -892239595,
            1797494240, 859738849, 992217954, -289490654,
            -2051890674, -424014439, -562951028, 765654824,
            -804095931, -1783130883, 1685915746, -405998096,
            1414112111, -2021832454, -1013056217, -214004450,
            172450625, -1724973196, 980381355, -185008841,
            -1475158944, -1578377736, -1726226100, -613520627,
            -964995824, 1835478071, 660984891, -590288892,
            -248967737, -872349789, -1254551662, 1762651403,
            1719377915, -824476260, -1601057013, -652910941,
            -1156370552, 1364962596, 2073328063, 1983633131,
            926494387, -871278215, -2144935273, -198299347,
            1749200295, -966120645, 309677260, 2016342300,
            1779581495, -1215147545, 111262694, 1274766160,
            443224088, 298511866, 1025883608, -488520759,
            1145181785, 168956806, -653464466, -710153686,
            1689216846, -628709281, -1094719096, 1692713982,
            -1648590761, -252198778, 1618508792, 1610833997,
            -771914938, -164094032, 2001055236, -684262196,
            -2092799181, -266425487, -1333771897, 1006657119,
            2006996926, -1108824540, 1430667929, -1084739999,
            1314452623, -220332638, -193663176, -2021016126,
            1399257539, -927756684, -1267338667, 1190975929,
            2062231137, -1960976508, -2073424263, -1856006686,
            1181637006, 548689776, -1932175983, -922558900,
            -1190417183, -1149106736, 296247880, 1970579870,
            -1216407114, -525738999, 1714227617, -1003338189,
            -396747006, 166772364, 1251581989, 493813264,
            448347421, 195405023, -1584991729, 677966185,
            -591930749, 1463355134, -1578971493, 1338867538,
            1343315457, -1492745222, -1610435132, 233230375,
            -1694987225, 2000651841, -1017099258, 1638401717,
            -266896856, -1057650976, 6314154, 819756386,
            300326615, 590932579, 1405279636, -1027467724,
            -1144263082, -1866680610, -335774303, -833020554,
            1862657033, 1266418056, 963775037, 2089974820,
            -2031914401, 1917689273, 448879540, -744572676,
            -313240200, 150775221, -667058989, 1303187396,
            508620638, -1318983944, -1568336679, 1817252668,
            1876281319, 1457606340, 908771278, -574175177,
            -677760460, -1838972398, 1729034894, 1080033504,
        ),
        array(
            976866871, -738527793, -1413318857, 1522871579,
            1555064734, 1336096578, -746444992, -1715692610,
            -720269667, -1089506539, -701686658, -956251013,
            -1215554709, 564236357, -1301368386, 1781952180,
            1464380207, -1131123079, -962365742, 1699332808,
            1393555694, 1183702653, -713881059, 1288719814,
            691649499, -1447410096, -1399511320, -1101077756,
            -1577396752, 1781354906, 1676643554, -1702433246,
            -1064713544, 1126444790, -1524759638, -1661808476,
            -2084544070, -1679201715, -1880812208, -1167828010,
            673620729, -1489356063, 1269405062, -279616791,
            -953159725, -145557542, 1057255273, 2012875353,
            -2132498155, -2018474495, -1693849939, 993977747,
            -376373926, -1640704105, 753973209, 36408145,
            -1764381638, 25011837, -774947114, 2088578344,
            530523599, -1376601957, 1524020338, 1518925132,
            -534139791, -535190042, 1202760957, -309069157,
            -388774771, 674977740, -120232407, 2031300136,
            2019492241, -311074731, -141160892, -472686964,
            352677332, -1997247046, 60907813, 90501309,
            -1007968747, 1016092578, -1759044884, -1455814870,
            457141659, 509813237, -174299397, 652014361,
            1966332200, -1319764491, 55981186, -1967506245,
            676427537, -1039476232, -1412673177, -861040033,
            1307055953, 942726286, 933058658, -1826555503,
            -361066302, -79791154, 1361170020, 2001714738,
            -1464409218, -1020707514, 1222529897, 1679025792,
            -1565652976, -580013532, 1770335741, 151462246,
            -1281735158, 1682292957, 1483529935, 471910574,
            1539241949, 458788160, -858652289, 1807016891,
            -576558466, 978976581, 1043663428, -1129001515,
            1927990952, -94075717, -1922690386, -1086558393,
            -761535389, 1412390302, -1362987237, -162634896,
            1947078029, -413461673, -126740879, -1353482915,
            1077988104, 1320477388, 886195818, 18198404,
            -508558296, -1785185763, 112762804, -831610808,
            1866414978, 891333506, 18488651, 661792760,
            1628790961, -409780260, -1153795797, 876946877,
            -1601685023, 1372485963, 791857591, -1608533303,
            -534984578, -1127755274, -822013501, -1578587449,
            445679433, -732971622, -790962485, -720709064,
            54117162, -963561881, -1913048708, -525259953,
            -140617289, 1140177722, -220915201, 668550556,
            -1080614356, 367459370, 261225585, -1684794075,
            -85617823, -826893077, -1029151655, 314222801,
            -1228863650, -486184436, 282218597, -888953790,
            -521376242, 379116347, 1285071038, 846784868,
            -1625320142, -523005217, -744475605, -1989021154,
            453669953, 1268987020, -977374944, -1015663912,
            -550133875, -1684459730, -435458233, 266596637,
            -447948204, 517658769, -832407089, -851542417,
            370717030, -47440635, -2070949179, -151313767,
            -182193321, -1506642397, -1817692879, 1456262402,
            -1393524382, 1517677493, 1846949527, -1999473716,
            -560569710, -2118563376, 1280348187, 1908823572,
            -423180355, 846861322, 1172426758, -1007518822,
            -911584259, 1655181056, -1155153950, 901632758,
            1897031941, -1308360158, -1228157060, -847864789,
            1393639104, 373351379, 950779232, 625454576,
            -1170726756, -146354570, 2007998917, 544563296,
            -2050228658, -1964470824, 2058025392, 1291430526,
            424198748, 50039436, 29584100, -689184263,
            -1865090967, -1503863136, 1057563949, -1039604065,
            -1219600078, -831004069, 1469046755, 985887462,
        ),
    );

/**
 * php-safe add-function
 *
 * PHP does confusing things when using hex > 0x7FFFFFFF
 * (like bitmask 0xFF000000). While searching the net for a solution,
 * i found the SHA implementation created by Chris Monson who described
 * the same problem. He found a solution for it, this function is "stolen"
 * from him, thanks for this work!!!
 * Description:
 * Add simply adds two numbers. It is provided for compatibility on
 * platforms that only support a 31 bit add (there are a few, apparently)
 *
 * @param    a value a
 * @param b value b
 * @return php-safe a+b
 */
    public function add($a, $b)
    {
        $ma = ($a >> 16) & 0xffff;
        $la = ($a) & 0xffff;
        $mb = ($b >> 16) & 0xffff;
        $lb = ($b) & 0xffff;

        $ls = $la + $lb;
// Carry
        if ($ls > 0xffff) {
            $ma += 1;
            $ls &= 0xffff;
        }

// MS add
        $ms = $ma + $mb;
        $ms &= 0xffff;

// Works because the bitwise operators are 32 bit
        $result = ($ms << 16) | $ls;
        return ($result);
    }

/**
 * mathematical function to return changed r-block in X()
 *
 * This function was the big problem converting the code to
 * php. It is called from X() which is called in Encipher and Decipher
 *
 * @param    x     Key to crypt the data
 */
    public function F($x)
    {

//just split $x into bytes and it use as index for the sboxes
        $S0 = $this->S[0][($x >> 24) & 0xff];
        $S1 = $this->S[1][($x >> 16) & 0xff];
        $S2 = $this->S[2][($x >> 8) & 0xff];
        $S3 = $this->S[3][$x & 0xff];

        $tmp = 0;
        $tmp = $this->add($S0, $S1) ^ $S2;

        return ($this->add($tmp, $S3));
    }

/**
 * mathematical function to return to change the l- and rblock
 * in some implementation, called ROUND()
 *
 * called in Encipher and Decipher
 *
 * @param    l     left side of block
 * @param r right side of block
 * @param i current index in the p-array
 */
    public function X(&$l, &$r, $i)
    {
        $l ^= $this->F($r) ^ $this->P[$i];
    }

/**
 * constructs the encryption sieve
 *
 * This function is really slow because the key is used
 * to shift the p- and sboxes which takes some time
 *
 * @param    szKey     Key to crypt the data
 * @param keybytes Length of szKey-string
 */
    public function Blowfish($szKey, $keybytes)
    {

// first of all, fill arrays from data tables
        $this->P = $this->Init_P;
        $this->S = $this->Init_S;

// just a little trick to get our byte-array from string
        for ($kp = 0; $kp < $keybytes; $kp++) {
            $arr = unpack("Ckey", $szKey[$kp]);
            $key[$kp] = $arr["key"];
        }

        $keybytes = $kp;

        $j = 0;
        for ($i = 0; $i < 18; ++$i) {
            $temp = ($key[$j] << 24) +
                ($key[($j + 1) % ($keybytes)] << 16) +
                ($key[($j + 2) % ($keybytes)] << 8) +
                ($key[($j + 3) % ($keybytes)]);
            $data = $temp;

            $this->P[$i] ^= $data;
            $j = ($j + 4) % $keybytes;
        }

        $datal = 0;
        $datar = 0;

        for ($i = 0; $i < 18; $i += 2) {
            $this->Encipher($datal, $datar);
            $this->P[$i] = $datal;
            $this->P[$i + 1] = $datar;
        }

        for ($i = 0; $i < 4; ++$i) {
            for ($j = 0; $j < 256; $j += 2) {
                $this->Encipher($datal, $datar);
                $this->S[$i][$j] = $datal;
                $this->S[$i][$j + 1] = $datar;
            }
        }

    } // end constructor

/**
 * the low level (private) encryption function
 *
 * this function is used in Encrypt() while jumping through the
 * data-blocks.
 *
 * @param $Xl left side of block
 * @param $Xr right side of block
 */
    public function Encipher(&$xl, &$xr)
    {

        $Xl = $xl;
        $Xr = $xr;

        $Xl ^= $this->P[0];

        $this->X($Xr, $Xl, 1);
        $this->X($Xl, $Xr, 2);
        $this->X($Xr, $Xl, 3);
        $this->X($Xl, $Xr, 4);
        $this->X($Xr, $Xl, 5);
        $this->X($Xl, $Xr, 6);
        $this->X($Xr, $Xl, 7);
        $this->X($Xl, $Xr, 8);
        $this->X($Xr, $Xl, 9);
        $this->X($Xl, $Xr, 10);
        $this->X($Xr, $Xl, 11);
        $this->X($Xl, $Xr, 12);
        $this->X($Xr, $Xl, 13);
        $this->X($Xl, $Xr, 14);
        $this->X($Xr, $Xl, 15);
        $this->X($Xl, $Xr, 16);
        $Xr ^= $this->P[17];

        $xr = $Xl;
        $xl = $Xr;

    }

/**
 * the low level (private) decryption function
 *
 * this function is used in Decrypt() while jumping through the
 * data-blocks.
 *
 * @param $xl left side of block
 * @param $xr right side of block
 */
    public function Decipher(&$xl, &$xr)
    {
        $Xl = $xl;
        $Xr = $xr;

        $Xl ^= $this->P[17];
        $this->X($Xr, $Xl, 16);
        $this->X($Xl, $Xr, 15);
        $this->X($Xr, $Xl, 14);
        $this->X($Xl, $Xr, 13);
        $this->X($Xr, $Xl, 12);
        $this->X($Xl, $Xr, 11);
        $this->X($Xr, $Xl, 10);
        $this->X($Xl, $Xr, 9);
        $this->X($Xr, $Xl, 8);
        $this->X($Xl, $Xr, 7);
        $this->X($Xr, $Xl, 6);
        $this->X($Xl, $Xr, 5);
        $this->X($Xr, $Xl, 4);
        $this->X($Xl, $Xr, 3);
        $this->X($Xr, $Xl, 2);
        $this->X($Xl, $Xr, 1);
        $Xr ^= $this->P[0];

        $xl = $Xr;
        $xr = $Xl;
    }

/**
 * "converts" a data string to left and right tiled block
 *
 * this function is used in in Encrypt() and Decrypt() to convert
 * the data "string" to data "blocks"
 *
 * @param $bl left side of block
 * @param $br right side of block
 * @param $p data string
 * @param $idx index of current char working on
 */
    public function BytesToBlock(&$bl, &$br, &$p, $idx)
    {
//just a little trick to do get our bytearray from string
        for ($i = 0; $i < 8; $i++) {
            $arr = unpack("Ckey", $p[$idx + $i]);
            $bytes[$i] = $arr["key"];
        }

        $bl = ($bytes[3] << 24) | ($bytes[2] << 16) | ($bytes[1] << 8) | $bytes[0];
        $br = ($bytes[7] << 24) | ($bytes[6] << 16) | ($bytes[5] << 8) | $bytes[4];
    }

/**
 * "converts" a data block back to data string
 *
 * this function is used in in Encrypt() and Decrypt() to convert
 * the data "blocks" back to data "string"
 *
 * @param $p data string the blocks are written back to
 * @param $bl left side of block
 * @param $br right side of block
 * @param $idx index of last char working on
 */
    public function BlockToBytes(&$p, &$bl, &$br, &$idx)
    {
        $p[$idx++] = chr($bl);
        $bl >>= 8;
        $p[$idx++] = chr($bl);
        $bl >>= 8;
        $p[$idx++] = chr($bl);
        $bl >>= 8;
        $p[$idx++] = chr($bl);

        $p[$idx++] = chr($br);
        $br >>= 8;
        $p[$idx++] = chr($br);
        $br >>= 8;
        $p[$idx++] = chr($br);
        $br >>= 8;
        $p[$idx++] = chr($br);
    }

/**
 * encrypt bytes by shuffling through blocks
 *
 * the data variable will be changed directly, the original uncrypted
 * string will be lost!
 * THE DATA MUST BE A MULTIPLE OF 8 !!!
 *
 * @param $data data string which will be crypted
 * @param $n length of data string
 */
    public function Encrypt(&$data, $n)
    {

        $idx = 0;
        $blockl = 0;
        $blockr = 0;

//FIXME: return error-code or something like that
        if ($n % 8) {
            die("Length of data not % 8<br>");
        }

        for (; $n >= 8; $n -= 8) {
            $this->BytesToBlock($blockl, $blockr, $data, $idx);
            $this->Encipher($blockl, $blockr);

            $this->BlockToBytes($data, $blockl, $blockr, $idx);
        }

    }

/**
 * decrypt bytes by shuffling through blocks
 *
 * the data variable will be changed directly, the original crypted
 * string will be lost!
 * THE DATA MUST BE A MULTIPLE OF 8 !!!
 *
 * @param $data data string which will be decrypted
 * @param $n length of data string
 */
    public function Decrypt(&$data, &$n)
    {

        $idx = 0;
        $blockl = 0;
        $blockr = 0;

//FIXME: return error-code or something like that
        if ($n % 8) {
            die("Length of data not % 8<br>");
        }

        for (; $n >= 8; $n -= 8) {
            $this->BytesToBlock($blockl, $blockr, $data, $idx);
            $this->Decipher($blockl, $blockr);
            $this->BlockToBytes($data, $blockl, $blockr, $idx);
        }
    }

} // end class Blowfish

/**
 * demo for testing the blowfish-class
 *
 * the crypted data will be written to the file "fish.dat"
 */
function blowfishDemo()
{

    $key = "very long password";
    $keylen = strlen($key);

    $data = "Just a short sentence 32chr longJust a short sentence 32chr longJust a short sentence 32chr longJust a short sentence 32chr long";
    $len = 128;

    echo ("<head><title>Blowfish-4-PHP demo</title></head><body>");

    echo ("Fishinit with '$key'...<br>");
    $blowfish = new Blowfish($key, $keylen);

    echo ("Feeding fish with data...<br>");
    $start_t = time();
    $blowfish->Encrypt($data, $len);
    $used = time() - $start_t;
    echo ("used time: " . $used . "<br>");

    $fp = fopen("fish.dat", "w");
    fwrite($fp, $data);
    fclose($fp);
    echo ("Crypted data written to fish.dat<br>");

    $blowdec = new Blowfish($key, $keylen);
    echo ("Feeding second fish...<br>");

    $fp = fopen("fish.dat", "r");
    $crypted = fread($fp, filesize("fish.dat"));
    fclose($fp);

    $start_t = time();
    $blowdec->Decrypt($crypted, $len);
    $used = time() - $start_t;
    echo ("used time: " . $used . "<br>");

    echo ("$crypted<br>");

    echo ("</body>");

}; //blowfish_demo();
