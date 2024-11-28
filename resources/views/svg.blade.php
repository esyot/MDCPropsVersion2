<style>
    .map {
        width: 100%;
        height: 400px;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #65b6dd;
        padding-bottom: 10px;
    }

    svg {
        width: 100%;
        height: 100%;
        display: block;
    }

    .municipality {
        fill: #f5dbbd;
        cursor: pointer;
        filter: drop-shadow(0px 5px 5px rgba(0, 0, 0, 0.5));
    }

    .municipality:hover {
        fill: #39c668;
    }

    .active {
        fill: #39c668;
    }

    .transformed-text {
        transform: rotate(180deg) scaleX(-1);
        transform-origin: 1500px 1200px;
    }

    .click-through {
        pointer-events: none;
    }

    text {
        font-size: 3rem;
        fill: black;
    }

    .modal {
        position: fixed;
        inset: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 800px;
    }

    .form-section {
        margin-bottom: 20px;
    }

    .fields-section {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .fields-section>div {
        flex: 1;
        margin-right: 10px;
    }

    .fields-section>div:last-child {
        margin-right: 0;
    }
</style>
<svg class="map" width="300.000000pt" height="250.000000pt" viewBox="0 0 300.000000 250.000000" class="z-50">

    <g transform="translate(0.000000,250.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
        <!-- <polygon class="municipality" data-name="Region 1" fill="#cecece"
                            points=" 2220,2236 2300,2200 2278,2223 2238,2235 2220,2236"></polygon> -->
        <!-- <polygon class="municipality" data-name="Region 2" fill="#cecece"
                            points=" 2024,2195 2040,2155 2040,2210 2024,2195"></polygon> -->
        <!-- <polygon class="municipality" data-name="Region 3" fill="#cecece"
                            points=" 1430,2129 1412,2124 1330,2069 1353,2054 1396,2060 1454,2118 1430,2129"></polygon> -->
        <!-- <polygon class="municipality" data-name="Region 4" fill="#cecece"
                            points=" 1603,2095 1563,2082 1499,2029 1671,2079 1684,2102 1603,2095">
                        </polygon> -->
        <!-- <polygon class="municipality" data-name="Region 5" fill="#cecece"
                            points=" 2579,2073 2572,2010 2577,2017 2586,2047 2579,2073"></polygon> -->
        <!-- <polygon class="municipality" data-name="Region 6" fill="#cecece"
                            points=" 1707,2074 1731,2050 1734,2074 1707,2074"></polygon> -->
        <!-- <polygon class="municipality" data-name="Region 7" fill="#cecece"
                            points=" 2000,2035 1959,2020 1930,2005 1938,1989 1975,1985 2039,2001 2060,2035 2000,2035">
                        </polygon> -->
        <polygon class="municipality" data-name="Getafe" fill="#cecece"
            points=" 1410,2023 1370,1975 1353,1956 1280,1860 1338,1810 1576,1713 1573,1851 1565,1985 1535,1982 1490,1965 1390,1951 1401,1956 1448,1979 1410,2023">
        </polygon>
        <polygon class="municipality" data-name="Talibon" fill="#cecece"
            points=" 1728,1993 1690,1968 1638,1981 1596,1937 1599,1783 1677,1686 1880,1761 1910,1922 1811,1997 1775,2006 1728,1993">
        </polygon>
        <polygon class="municipality" data-name="Bien Unido" fill="#cecece"
            points=" 1941,1955 1907,1943 1938,1921 1987,1870 2103,1861 2174,1913 1941,1955"></polygon>
        <polygon class="municipality" data-name="P. Carlos Garcia" fill="#cecece"
            points=" 2685,1944 2643,1920 2630,1905 2620,1890 2601,1869 2581,1879 2533,1904 2521,1879 2496,1855 2461,1856 2468,1794 2486,1791 2476,1765 2474,1705 2506,1663 2552,1694 2565,1710 2564,1742 2605,1773 2646,1763 2712,1778 2701,1837 2686,1879 2724,1912 2763,1946 2738,1957 2685,1944">
        </polygon>
        <polygon class="municipality" data-name="Ubay" fill="#cecece"
            points=" 2052,1828 2040,1824 2025,1820 2038,1780 2077,1772 2090,1635 2089,1568 2086,1480 2113,1388 2347,1388 2405,1404 2546,1456 2561,1480 2565,1468 2569,1441 2594,1439 2597,1512 2569,1581 2555,1603 2540,1625 2512,1646 2463,1685 2382,1715 2245,1740 2192,1775 2149,1801 2111,1820 2085,1830 2052,1828">
        </polygon>
        <polygon class="municipality" data-name="Buenavista" fill="#cecece"
            points=" 1235,1794 1183,1751 1167,1712 1281,1650 1360,1596 1406,1536 1494,1518 1511,1526 1529,1529 1545,1578 1556,1650 1552,1687 1455,1735 1318,1796 1268,1820 1235,1794">
        </polygon>
        <!-- <polygon class="municipality" data-name="Region 14" fill="#cecece"
                            points=" 2402,1773 2430,1769 2414,1790 2402,1773"></polygon> -->
        <polygon class="municipality" data-name="Trinidad" fill="#cecece"
            points=" 1825,1724 1671,1656 1665,1607 1701,1575 2003,1623 2076,1714 1825,1724"></polygon>
        <polygon class="municipality" data-name="Inabanga" fill="#cecece"
            points=" 1072,1718 1054,1655 1024,1544 1007,1476 1050,1470 1113,1444 1221,1416 1300,1464 1319,1530 1338,1622 1265,1629 1190,1649 1158,1673 1140,1705 1111,1730 1072,1718">
        </polygon>
        <polygon class="municipality" data-name="Danao" fill="#cecece"
            points=" 1568,1592 1512,1482 1427,1508 1342,1522 1332,1450 1330,1415 1318,1354 1351,1314 1414,1296 1497,1277 1561,1296 1613,1317 1652,1354 1698,1394 1706,1484 1650,1585 1600,1640 1568,1592">
        </polygon>
        <polygon class="municipality" data-name="San Miguel" fill="#cecece"
            points=" 1999,1605 1815,1565 1702,1542 1887,1300 1916,1325 1969,1372 2020,1442 2050,1498 2064,1578 2026,1610 1999,1605">
        </polygon>
        <!-- <polygon class="municipality" data-name="Region 19" fill="#cecece"
                            points=" 663,1563 674,1460 723,1498 748,1547 663,1563"></polygon> -->
        <!-- <polygon class="municipality" data-name="Region 20" fill="#cecece"
                            points=" 731,1462 740,1425 788,1447 731,1462"></polygon> -->
        <polygon class="municipality" data-name="Clarin" fill="#cecece"
            points=" 970,1445 930,1426 900,1385 909,1332 929,1273 986,1263 1061,1345 1118,1410 970,1445">
        </polygon>
        <polygon class="municipality" data-name="Tubigon" fill="#cecece"
            points=" 810,1416 780,1400 757,1387 643,1335 683,1243 726,1236 813,1212 853,1217 890,1242 895,1336 885,1425 854,1428 810,1416">
        </polygon>
        <polygon class="municipality" data-name="Sagbayan" fill="#cecece"
            points=" 1179,1403 982,1213 1201,1166 1274,1249 1267,1399 1179,1403">
        </polygon>
        <polygon class="municipality" data-name="Mabini" fill="#cecece"
            points=" 2439,1393 2366,1279 2355,1174 2385,1130 2538,1136 2570,1114 2596,1096 2588,1139 2589,1171 2606,1223 2612,1250 2618,1267 2570,1296 2542,1309 2550,1340 2551,1376 2555,1391 2439,1393">
        </polygon>
        <polygon class="municipality" data-name="Dagohoy" fill="#cecece"
            points=" 1715,1381 1665,1345 1620,1305 1573,1283 1720,1156 1773,1127 1797,1156 1851,1218 1880,1263 1766,1388 1715,1381">
        </polygon>
        <polygon class="municipality" data-name="Alicia" fill="#cecece"
            points=" 2021,1366 2016,1264 2067,1108 2155,1111 2235,1140 2279,1148 2321,1161 2335,1244 2351,1337 2231,1359 2069,1370 2034,1380 2021,1366">
        </polygon>
        <polygon class="municipality" data-name="Calape" fill="#cecece"
            points=" 445,1350 451,1306 461,1268 445,1218 419,1187 463,1146 603,1040 650,1107 692,1181 612,1315 583,1300 485,1328 445,1350">
        </polygon>
        <polygon class="municipality" data-name="Pilar" fill="#cecece"
            points=" 1923,1305 1895,1261 1874,1219 1860,1197 1839,1164 1824,1025 1935,954 2060,975 2039,1096 1995,1141 1995,1228 1970,1318 1923,1305">
        </polygon>
        <polygon class="municipality" data-name="Carmen" fill="#cecece"
            points=" 1335,1288 1275,1222 1258,1162 1283,1143 1338,1085 1380,987 1395,920 1405,889 1422,859 1495,767 1517,744 1561,750 1590,780 1604,852 1624,934 1659,1004 1710,1137 1677,1165 1605,1220 1497,1260 1396,1278 1335,1288">
        </polygon>
        <!-- <polygon class="municipality" data-name="Region 30" fill="#cecece"
                            points=" 310,1257 330,1220 350,1183 370,1180 383,1190 413,1215 413,1246 375,1260 310,1257">
                        </polygon> -->
        <!-- <polygon class="municipality" data-name="Region 31" fill="#cecece"
                            points=" 2688,1195 2695,1180 2733,1240 2688,1195"></polygon> -->
        <!-- <polygon class="municipality" data-name="Region 32" fill="#cecece"
                            points=" 141,1188 149,1120 250,1189 185,1230 141,1188"></polygon> -->
        <polygon class="municipality" data-name="San Isidro" fill="#cecece"
            points=" 743,1216 720,1195 685,1136 644,1054 690,970 755,924 795,974 825,1098 802,1203 743,1216">
        </polygon>
        <polygon class="municipality" data-name="Catigbian" fill="#cecece"
            points=" 858,1188 834,1136 840,1064 880,986 970,984 1049,1009 1125,1042 1088,1150 970,1185 905,1220 858,1188">
        </polygon>
        <polygon class="municipality" data-name="Loon" fill="#cecece"
            points=" 330,1087 237,816 306,811 523,985 582,1025 553,1048 476,1113 414,1162 330,1087">
        </polygon>
        <polygon class="municipality" data-name="Butuan" fill="#cecece"
            points=" 1187,1143 1180,1104 1155,1054 1130,1022 1115,990 1095,948 1117,922 1163,902 1281,880 1381,883 1384,903 1360,938 1348,969 1359,1011 1243,1138 1230,1139 1187,1143">
        </polygon>
        <!-- <polygon class="municipality" data-name="Region 37" fill="#cecece"
                            points=" 221,1111 224,1075 297,1090 312,1110 315,1120 221,1111">
                        </polygon> -->
        <polygon class="municipality" data-name="Candijay" fill="#cecece"
            points=" 2221,1115 2120,1053 2140,1040 2208,1016 2338,966 2517,945 2629,964 2607,995 2537,1040 2338,1125 2221,1115">
        </polygon>
        <polygon class="municipality" data-name="Seirra Bullones" fill="#cecece"
            points=" 1738,1113 1720,1066 1698,1021 1640,889 1620,793 1628,770 1657,781 1721,795 1855,842 1919,883 1974,925 1925,941 1840,971 1809,995 1788,1023 1804,1066 1785,1108 1757,1119 1738,1113">
        </polygon>
        <polygon class="municipality" data-name="Antequera" fill="#cecece"
            points=" 526,963 469,915 492,900 557,843 637,800 703,830 712,922 596,1010 526,963">
        </polygon>
        <polygon class="municipality" data-name="Guindulman" fill="#cecece"
            points=" 2100,998 2085,970 2070,950 2140,859 2210,753 2310,796 2362,850 2381,805 2430,746 2444,795 2466,911 2391,934 2244,970 2100,998">
        </polygon>
        <polygon class="municipality" data-name="Balilihan" fill="#cecece"
            points=" 807,964 761,882 708,811 684,757 803,693 895,713 945,777 1025,850 1074,874 1079,906 1073,951 1067,991 1032,994 980,949 973,930 853,983 832,1002 807,964">
        </polygon>
        <polygon class="municipality" data-name="Anda" fill="#cecece"
            points=" 2670,965 2545,923 2494,918 2477,841 2460,747 2572,745 2640,789 2680,892 2692,968 2670,965">
        </polygon>
        <polygon class="municipality" data-name="Duero" fill="#cecece"
            points=" 2015,926 1957,883 1929,853 1980,772 2030,682 2086,636 2114,654 2182,720 2194,748 2111,869 2060,931 2015,926">
        </polygon>
        <polygon class="municipality" data-name="Maribojoc" fill="#cecece"
            points=" 409,882 329,813 276,760 312,760 408,678 425,647 483,737 515,853 442,900 409,882">
        </polygon>
        <polygon class="municipality" data-name="Bilar" fill="#cecece"
            points=" 1086,868 1085,820 1157,610 1176,542 1190,508 1230,540 1302,611 1345,650 1398,689 1459,735 1474,763 1427,835 1222,866 1155,880 1086,868">
        </polygon>
        <polygon class="municipality" data-name="Sevilla" fill="#cecece"
            points=" 980,808 960,766 935,724 924,621 1095,608 1123,651 1091,760 1031,838 980,808">
        </polygon>
        <polygon class="municipality" data-name="Jagna" fill="#cecece"
            points=" 1844,811 1783,776 1770,752 1866,577 1910,470 1985,491 2047,522 2098,563 2056,630 2028,654 1902,840 1844,811">
        </polygon>
        <polygon class="municipality" data-name="Cortes" fill="#cecece"
            points=" 536,778 505,713 479,670 470,660 493,650 544,635 580,635 600,690 601,770 576,785 536,778">
        </polygon>
        <polygon class="municipality" data-name="Garcia Hernandez" fill="#cecece"
            points=" 1667,768 1635,731 1646,540 1663,376 1797,414 1873,444 1890,468 1753,753 1667,768">
        </polygon>
        <polygon class="municipality" data-name="Corella" fill="#cecece"
            points=" 643,738 600,636 670,580 728,580 739,627 713,690 677,721 643,738"></polygon>
        <polygon class="municipality" data-name="Valencia" fill="#cecece"
            points=" 1484,722 1466,558 1459,402 1502,395 1578,377 1621,380 1617,718 1484,722">
        </polygon>
        <polygon class="municipality" data-name="Dimiao" fill="#cecece"
            points=" 1430,691 1343,640 1330,623 1265,546 1222,514 1249,483 1282,413 1383,387 1436,433 1440,519 1448,710 1430,691">
        </polygon>
        <polygon class="municipality" data-name="Sikatuna" fill="#cecece"
            points=" 855,670 803,661 761,623 753,584 815,572 896,608 882,679 855,670"></polygon>
        <polygon class="municipality" data-name="Tagbilaran City" fill="#cecece"
            points=" 437,623 430,576 471,488 550,448 557,532 536,620 437,623">
        </polygon>
        <polygon class="municipality" data-name="Loboc" fill="#cecece"
            points=" 894,562 867,527 909,489 1011,450 1170,501 1126,593 1061,588 964,588 894,562">
        </polygon>
        <polygon class="municipality" data-name="Baclayon" fill="#cecece"
            points=" 572,504 646,426 692,448 707,555 634,563 573,568 572,504">
        </polygon>
        <polygon class="municipality" data-name="Alborquerque" fill="#cecece"
            points=" 740,541 725,487 755,388 815,393 830,435 845,494 794,560 740,541"></polygon>
        <polygon class="municipality" data-name="Dauis" fill="#cecece"
            points=" 340,495 293,471 270,456 313,397 365,325 425,330 459,468 340,495"></polygon>
        <polygon class="municipality" data-name="Loay" fill="#cecece"
            points=" 855,479 846,411 845,365 885,364 1001,355 1076,348 1074,391 1072,435 1012,432 914,464 855,479">
        </polygon>
        <polygon class="municipality" data-name="Lila" fill="#cecece"
            points=" 1143,464 1090,439 1090,388 1090,338 1138,344 1260,404 1237,468 1143,464">
        </polygon>
        <polygon class="municipality" data-name="Panglao" fill="#cecece"
            points=" 175,396 123,236 224,234 323,272 324,335 231,440 175,396">
        </polygon>

        <!-- labels -->

        <text x="1450" y="1350" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Carmen
        </text>
        <text x="1450" y="1000" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Danao
        </text>
        <text x="1180" y="1420" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Butuan
        </text>
        <text x="1230" y="1700" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Bilar
        </text>
        <text x="970" y="1720" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Sevilla
        </text>
        <text x="970" y="1900" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Loboc
        </text>
        <text x="1080" y="1150" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Sagbayan
        </text>
        <text x="950" y="1040" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Clarin
        </text>
        <text x="1100" y="870" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Inabanga
        </text>
        <text x="1270" y="720" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Buenavista
        </text>
        <text x="1390" y="540" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Getafe
        </text>
        <text x="1800" y="740" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Trinidad
        </text>
        <text x="1800" y="940" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            San Miguel
        </text>
        <text x="1700" y="540" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Talibon
        </text>
        <text x="1900" y="500" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Bien Unido
        </text>
        <text x="2400" y="600" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            P. Carlos Garcia
        </text>
        <text x="2280" y="860" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Ubay
        </text>
        <text x="2140" y="1180" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Alicia
        </text>
        <text x="1900" y="1280" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Pilar
        </text>
        <text x="1680" y="1140" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Dagohoy
        </text>
        <text x="2440" y="1180" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Mabini
        </text>
        <text x="2290" y="1380" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Candijay
        </text>
        <text x="2200" y="1530" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Guindulman
        </text>
        <text x="2020" y="1630" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Duero
        </text>
        <text x="1880" y="1790" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Jagna
        </text>
        <text x="2540" y="1550" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Anda
        </text>
        <text x="900" y="1320" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Catigbian
        </text>
        <text x="800" y="1580" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Balilihan
        </text>
        <text x="730" y="1120" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Tubigon
        </text>
        <text x="510" y="1230" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Calape
        </text>
        <text x="350" y="1420" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Loon
        </text>
        <text x="320" y="1630" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Maribojoc
        </text>
        <text x="490" y="1700" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Cortes
        </text>
        <text x="610" y="1750" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Corella
        </text>
        <text x="560" y="1900" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Baclayon
        </text>

        <text x="650" y="1960" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Alborquerque
        </text>
        <text x="900" y="2000" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Loay
        </text>
        <text x="1150" y="2000" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Lila
        </text>
        <text x="1300" y="1920" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Dimiao
        </text>
        <text x="1470" y="1890" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Valencia
        </text>
        <text x="170" y="1830" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            <tspan x="1700" y="1830">Garcia</tspan>
            <tspan x="1680" y="1870">Hernandez</tspan>
        </text>

        <text x="170" y="1500" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            <tspan x="1700" y="1500">Sierra</tspan>
            <tspan x="1680" y="1540">Bullones</tspan>
        </text>
        <text x="320" y="1970" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Dauis
        </text>
        <text x="160" y="2100" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Panglao
        </text>
        <text x="360" y="1870" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Tagbilaran
        </text>
        <text x="750" y="1790" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Sikatuna
        </text>
        <text x="650" y="1340" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            San Isidro
        </text>
        <text x="530" y="1520" font-family="Arial" font-size="36" fill="black" class="transformed-text click-through">
            Antequera
        </text>

</svg>