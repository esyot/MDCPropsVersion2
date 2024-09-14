<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Custom Polygon Map</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="stylesheet" href="{{ asset('asset/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/fontawesome.min.css') }}">
    <script src="{{ asset('asset/js/tailwind.min.js') }}"></script>
    <script src="{{ asset('asset/js/htmx.min.js') }}"></script>
    <link rel="icon" href="{{ asset('asset/logo/MDC-logo-clipped.png') }}" type="image/png">

    <style>
        .map {
            width: 100%;
            height: 100%;
            display: flex;
            margin-top: 12px;
            justify-content: center;
            align-items: center;
            background-color: #89CFF0;
            padding-bottom: 10px;
        }

        svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        .municipality {
            fill: #cecece;
            stroke: #808080;
            stroke-width: 3;
            cursor: pointer;
            transition: transform 0.3s ease;
            /* Add a transition for smooth scaling */
        }

        .municipality:hover {
            fill: #f39c12;
            transform: scale(1.01);
        }

        .active {
            fill: #f39c12;

        }

        .transformed-text {
            transform: rotate(180deg) scaleX(-1);
            transform-origin: 1500px 1200px;
        }

        .click-through {
            pointer-events: none;
        }
    </style>
</head>

<body>
    <div class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50">


        <div class="flex flex-col justify-center bg-white p-4 w-[600px] rounded">
            <div>
                <h1 class="text-2xl font-bold">Choose a destination</h1>
            </div>

            <div class="flex justify-center">


                <svg version="1.0" class="map" width="300.000000pt" height="250.000000pt"
                    viewBox="0 0 300.000000 250.000000" preserveAspectRatio="xMidYMid meet">
                    <metadata>
                        Created by potrace 1.10, written by Peter Selinger 2001-2011
                    </metadata>
                    <g transform="translate(0.000000,250.000000) scale(0.100000,-0.100000)" fill="#000000"
                        stroke="none">
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 1"
                            points=" 2286,2200 2360,2179 2285,2210 2286,2200"></polygon>

                        <polygon fill="#cecece" class="municipality" data-name="Polygon 2"
                            points=" 2080,2155 2095,2130 2110,2155 2095,2180 2080,2155"></polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 3"
                            points=" 1490,2100 1475,2096 1397,2030 1509,2054 1499,2110 1490,2100"></polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 4"
                            points=" 1665,2068 1595,2056 1542,2038 1553,2005 1664,2006 1684,2020 1740,2072 1665,2068">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 5"
                            points=" 2641,2051 2624,2009 2620,1975 2640,2009 2641,2051"></polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 6"
                            points=" 1762,2038 1789,2022 1808,2032 1781,2048 1762,2038"></polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 7"
                            points=" 2056,2005 2019,1995 1999,1967 2098,1973 2119,2006 2056,2005"></polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 8"
                            points=" 1460,1993 1433,1971 1437,1943 1510,1956 1520,1990 1460,1993"></polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 9"
                            points=" 1793,1968 1750,1946 1680,1955 1655,1961 1655,1811 1667,1649 1782,1678 2020,1759 2048,1791 1970,1894 1871,1965 1840,1975 1793,1968">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 10"
                            points=" 1050,1951 1085,1922 1064,1960 1050,1951"></polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 11"
                            points=" 1580,1953 1545,1935 1455,1920 1421,1930 1362,1883 1331,1835 1352,1813 1394,1784 1440,1760 1510,1727 1594,1695 1635,1682 1633,1823 1616,1959 1580,1953">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 12"
                            points=" 2746,1919 2706,1900 2690,1885 2676,1863 2658,1840 2639,1855 2609,1881 2583,1877 2582,1850 2559,1828 2524,1829 2527,1772 2531,1732 2600,1640 2614,1665 2624,1680 2624,1712 2667,1744 2704,1735 2728,1720 2766,1780 2754,1839 2776,1882 2808,1911 2746,1919">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 13"
                            points=" 2042,1918 2025,1877 2033,1850 2054,1835 2109,1829 2099,1881 2042,1918">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 14"
                            points=" 2100,1801 2108,1750 2155,1690 2140,1600 2146,1549 2142,1440 2129,1372 2246,1354 2410,1363 2515,1385 2610,1440 2621,1460 2626,1443 2630,1410 2655,1414 2645,1502 2626,1554 2616,1576 2600,1596 2571,1616 2524,1655 2483,1699 2436,1684 2381,1665 2311,1705 2250,1745 2213,1770 2178,1792 2140,1803 2100,1801">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 15"
                            points=" 1295,1765 1227,1681 1350,1621 1420,1563 1485,1498 1565,1474 1610,1582 1588,1676 1382,1766 1327,1790 1295,1765">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 16"
                            points=" 1892,1700 1710,1609 1758,1545 2058,1594 2136,1684 1892,1700"></polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 17"
                            points=" 1144,1695 1116,1637 1086,1525 1066,1448 1110,1435 1172,1411 1368,1398 1362,1431 1381,1507 1408,1568 1331,1600 1252,1618 1225,1640 1198,1677 1144,1695">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 18"
                            points=" 1628,1567 1572,1452 1483,1479 1396,1493 1381,1434 1380,1380 1386,1281 1415,1288 1469,1275 1616,1266 1673,1289 1708,1327 1756,1366 1795,1398 1771,1449 1700,1585 1659,1613 1628,1567">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 19"
                            points=" 2056,1575 1966,1555 1903,1538 1872,1534 1764,1518 1873,1340 1987,1309 2035,1350 2080,1414 2110,1472 2140,1507 2101,1590 2056,1575">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 20"
                            points=" 723,1533 725,1444 781,1468 814,1519 723,1533"></polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 21"
                            points=" 796,1438 797,1398 844,1396 831,1450 796,1438"></polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 22"
                            points=" 1020,1414 978,1396 969,1302 989,1243 1010,1200 1050,1241 1159,1352 1180,1380 1020,1414">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 23"
                            points=" 857,1382 807,1349 750,1320 698,1303 742,1216 790,1210 850,1201 910,1189 938,1209 958,1278 947,1365 920,1397 857,1382">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 24"
                            points=" 1228,1373 1200,1361 1138,1300 1052,1213 1028,1178 1049,1156 1163,1136 1275,1139 1351,1363 1228,1373">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 25"
                            points=" 2473,1359 2426,1248 2415,1147 2448,1102 2575,1102 2664,1187 2674,1228 2625,1269 2603,1279 2614,1303 2607,1348 2556,1369 2473,1359">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 26"
                            points=" 1766,1353 1722,1319 1686,1278 1636,1258 1731,1159 1851,1126 1896,1174 1887,1301 1825,1358 1766,1353">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 27"
                            points=" 2077,1344 2076,1241 2108,1107 2140,1065 2231,1090 2341,1120 2390,1196 2405,1289 2397,1331 2260,1334 2115,1344 2077,1344">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 28"
                            points=" 502,1308 505,1194 480,1166 666,1012 706,1074 745,1151 714,1235 666,1303 644,1276 607,1250 549,1295 499,1340 502,1308">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 29"
                            points=" 2000,1287 1945,1210 1895,1130 1878,1053 1911,968 2107,938 2135,1027 2101,1070 2055,1206 2050,1285 2000,1287">
                        </polygon>
                        <polygon title="Carmen" fill="#cecece" class="municipality" data-name="Carmen"
                            points=" 1426,1271 1393,1257 1337,1200 1308,1149 1332,1126 1398,1057 1440,956 1454,891 1463,860 1481,831 1554,742 1606,720 1650,753 1665,830 1685,912 1725,990 1760,1045 1766,1088 1733,1139 1666,1194 1573,1230 1478,1255 1426,1271">
                        </polygon>


                        <polygon fill="#cecece" class="municipality" data-name="Polygon 31"
                            points=" 366,1232 388,1193 412,1152 419,1140 430,1156 439,1166 489,1213 449,1228 366,1232">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 32"
                            points=" 2742,1167 2707,1117 2800,1202 2742,1167"></polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 33"
                            points=" 200,1160 174,1105 308,1158 285,1190 200,1160"></polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 34"
                            points=" 803,1193 775,1160 754,1119 700,1010 813,880 849,931 884,1064 862,1173 831,1199 803,1193">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 35"
                            points=" 944,1189 899,999 1004,932 1028,958 1105,982 1195,1032 1150,1120 1026,1160 969,1199 944,1189">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 36"
                            points=" 382,1052 301,784 381,796 630,990 640,1002 500,1115 466,1140 382,1052">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 37"
                            points=" 1253,1123 1240,1087 1220,1035 1150,909 1163,900 1215,880 1344,855 1441,856 1411,916 1409,944 1364,1069 1309,1114 1283,1118 1253,1123">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 38"
                            points=" 291,1093 296,1036 360,1064 373,1083 344,1104 291,1093"></polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 39"
                            points=" 2627,1098 2633,1055 2644,1025 2651,1050 2646,1110 2627,1098"></polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 40"
                            points=" 2295,1090 2186,1045 2202,1010 2264,991 2545,918 2668,924 2668,966 2597,1012 2295,1090">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 41"
                            points=" 1787,1079 1776,1044 1752,987 1700,872 1686,792 1720,755 1791,770 1903,810 1975,850 2034,902 1996,910 1900,945 1869,969 1848,992 1864,1037 1851,1079 1787,1079">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 42"
                            points=" 581,938 531,888 559,866 623,810 700,774 766,806 774,895 650,980 581,938">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 43"
                            points=" 2147,950 2166,875 2270,721 2355,754 2424,820 2441,775 2487,714 2524,901 2437,913 2304,944 2209,975 2147,950">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 44"
                            points=" 863,938 826,865 769,787 745,720 872,666 1014,765 1075,820 1135,906 1101,966 1040,917 1023,905 963,927 909,956 863,938">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 45"
                            points=" 2691,920 2606,895 2554,888 2537,816 2583,712 2680,741 2709,776 2742,871 2735,939 2691,920">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 46"
                            points=" 2075,900 2022,858 1989,823 2050,725 2148,605 2170,620 2240,690 2254,718 2171,840 2120,902 2075,900">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 47"
                            points=" 465,851 385,781 336,730 372,730 465,654 476,615 536,618 609,606 640,608 660,665 663,740 605,791 503,870 465,851">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 48"
                            points=" 1146,839 1149,782 1231,528 1253,490 1370,595 1399,619 1461,666 1504,700 1527,751 1496,799 1279,839 1210,855 1146,839">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 49"
                            points=" 1942,809 1897,780 1848,753 1829,725 1970,440 1998,426 2050,477 2068,491 2153,499 2158,543 2124,600 2084,632 1973,813 1942,809">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 50"
                            points=" 1034,768 992,692 972,626 1029,582 1188,606 1156,716 1088,808 1034,768">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 51"
                            points=" 1721,739 1695,702 1706,515 1714,352 1868,392 1950,441 1877,602 1721,739">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 52"
                            points=" 682,676 669,562 766,551 870,547 949,562 951,639 907,639 814,646 737,693 710,720 682,676">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 53"
                            points=" 1560,701 1529,577 1516,445 1516,401 1573,362 1651,345 1685,369 1689,537 1621,709 1560,701">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 54"
                            points=" 1490,661 1403,610 1390,592 1296,497 1308,454 1342,382 1374,343 1453,361 1495,408 1499,493 1508,680 1490,661">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 55"
                            points=" 505,598 572,419 616,502 604,588 505,598"></polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 56"
                            points=" 954,533 930,497 977,456 1076,420 1223,460 1214,516 1198,566 1089,565 979,565 954,533">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 57"
                            points=" 630,477 630,412 683,403 751,420 773,488 778,530 727,530 653,536 630,477">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 58"
                            points=" 784,458 763,385 799,368 883,385 905,459 856,530 805,530 784,458"></polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 59"
                            points=" 434,480 366,451 330,428 360,387 411,313 454,289 487,305 560,376 481,479 434,480">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 60"
                            points=" 914,448 906,378 920,340 1000,335 1100,320 1135,365 1135,410 1070,408 970,438 914,448">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 61"
                            points=" 1212,440 1165,420 1150,364 1150,308 1188,313 1273,330 1320,379 1297,438 1212,440">
                        </polygon>
                        <polygon fill="#cecece" class="municipality" data-name="Polygon 62"
                            points=" 226,358 173,213 274,205 380,240 400,275 295,420 226,358"></polygon>

                        <!-- labels -->

                        <text x="1500" y="1350" font-family="Arial" font-size="36" font-weight="bold" fill="black"
                            class="transformed-text click-through">
                            Carmen
                        </text>
                        <text x="1230" y="1420" font-family="Arial" font-size="36" font-weight="bold" fill="black"
                            class="transformed-text click-through">
                            Butuan
                        </text>
                        <text x="1280" y="1720" font-family="Arial" font-size="36" font-weight="bold" fill="black"
                            class="transformed-text click-through">
                            Bilar
                        </text>

                        <text x="1020" y="1720" font-family="Arial" font-size="36" font-weight="bold" fill="black"
                            class="transformed-text click-through">
                            Sevilla
                        </text>

                        <text x="1020" y="1900" font-family="Arial" font-size="36" font-weight="bold" fill="black"
                            class="transformed-text click-through">
                            Loboc
                        </text>

                        <text x="1130" y="1150" font-family="Arial" font-size="36" font-weight="bold" fill="black"
                            class="transformed-text click-through">
                            Sagbayan
                        </text>
                        <text x="1000" y="1080" font-family="Arial" font-size="36" font-weight="bold" fill="black"
                            class="transformed-text click-through">
                            Clarin
                        </text>

                        <text x="950" y="1340" font-family="Arial" font-size="36" font-weight="bold" fill="black"
                            class="transformed-text click-through">
                            Catigbian
                        </text>

                        <text x="850" y="1600" font-family="Arial" font-size="36" font-weight="bold" fill="black"
                            class="transformed-text click-through">
                            Balilihan
                        </text>

                        <text x="780" y="1120" font-family="Arial" font-size="36" font-weight="bold" fill="black"
                            class="transformed-text click-through">
                            Tubigon
                        </text>
                        <text x="580" y="1250" font-family="Arial" font-size="36" font-weight="bold" fill="black"
                            class="transformed-text click-through">
                            Calape
                        </text>
                        <text x="400" y="1450" font-family="Arial" font-size="36" font-weight="bold" fill="black"
                            class="transformed-text click-through">
                            Loon
                        </text>

                        <text x="450" y="1680" font-family="Arial" font-size="36" font-weight="bold" fill="black"
                            class="transformed-text click-through">
                            Maribojoc
                        </text>
                        <text x="700" y="1380" font-family="Arial" font-size="36" font-weight="bold" fill="black"
                            class="transformed-text click-through">
                            San Isidro
                        </text>

                        <text x="580" y="1520" font-family="Arial" font-size="36" font-weight="bold" fill="black"
                            class="transformed-text click-through">
                            Antequera
                        </text>
                </svg>
            </div>
            <div class="flex justify-between">
                <div class="mt-2">
                    <label for="municipality">Municipality:</label>
                    <input type="text" name="municipality" class="block p-2 border border-gray-300 rounded">
                </div>

                <div class="mt-2">
                    <label for="price">Price:</label>
                    <input type="text" name="price" class="block p-2 border border-gray-300 rounded">
                </div>

            </div>

        </div>

    </div>


    <script>
        // Function to handle click events
        function handlePolygonClick(event) {
            // Log the name of the clicked polygon
            const regionName = event.target.getAttribute('data-name');
            console.log("You clicked on: " + regionName);

            // Remove 'active' class from all polygons
            document.querySelectorAll('.municipality').forEach(polygon => {
                polygon.classList.remove('active');
            });

            // Add 'active' class to the clicked polygon
            event.target.classList.add('active');

            // Log additional details about the clicked polygon
            console.log("Polygon points: " + event.target.getAttribute('points'));
            console.log("Current fill color: " + window.getComputedStyle(event.target).fill);
        }

        // Add event listeners to all polygons
        document.querySelectorAll('.municipality').forEach(polygon => {
            polygon.addEventListener('click', handlePolygonClick);
        });
    </script>

</body>

</html>