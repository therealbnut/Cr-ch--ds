<?php
    $HOST = "115.146.86.126";
    $USERNAME = "govhack";
    $PASSWORD = "doritos";
    $DATABASE = "govhack";
    $SOAPURL_GETGENDATA = "http://stats.oecd.org/OECDStatWS/SDMX/GetGenericData";
    $SOAPURL_WSDL = "http://stat.abs.gov.au/sdmxws/sdmx.asmx?WSDL";
    $DATASTATSSELECTION_HOUSEHOLDS = array(
                            "283" => "Family households",
                            "284" => "Group households",
                            "285" => "Lone person households"
                            );
    $DATASTATSSELECTION_FAMILIES = array(
                            "277" => "Other families",
                            "276" => "One parent families with non-dependent children only",
                            "275" => "One parent families with children under 15 and/or dependent students",
                            "274" => "Couple families without children",
                            "273" => "Couple families with non-dependent children only",
                            "272" => " Couple families with children under 15 and/or dependent students"
                            );
    $DATASTATSSELECTION_BIRTHS = array(
                            "201" => "Deaths",
                            "200" => "Births"
                            );
    $DATASTATSSELECTION_INDIGEN = array(
                            "214" => "Indigeneous population",
                            "NonIndig" => "Non-Indigeneous population"
                            );
    $DATASTATSSELECTION_OCCUPATION = array(
                            "293" => "Inadequately Described and Not Stated",
                            "294" => "Labourers",
                            "295" => "Machinery Operators and Drivers",
                            "298" => "Sales Workers",
                            "291" => "Clerical and Administrative Workers",
                            "292" => "Community and Personal Service Workers",
                            "299" => "Technicians and Trades Workers",
                            "297" => "Professionals",
                            "296" => "Managers" 
                            );
    $DATASTATSSELECTION_POPULATIONFEMALES = array(
                            "232" => "Females aged 85 and over",
                            "231" => "Females aged 80 to 84",
                            "230" => "Females aged 75 to 79",
                            "229" => "Females aged 70 to 74",
                            "228" => "Females aged 65 to 69",
                            "227" => "Females aged 60 to 64",
                            "226" => "Females aged 55 to 59",
                            "225" => "Females aged 50 to 54",
                            "223" => "Females aged 45 to 49",
                            "222" => "Females aged 40 to 44",
                            "221" => "Females aged 35 to 39",
                            "220" => "Females aged 30 to 34",
                            "219" => "Females aged 25 to 29",
                            "218" => "Females aged 20 to 24",
                            "217" => "Females aged 15 to 19",
                            "216" => "Females aged 10 to 14",
                            "224" => "Females aged 5 to 9",
                            "215" => "Females aged 0 to 4"
                            );
    $DATASTATSSELECTION_POPULATIONMALES = array(
                            "250" => "Males aged 85 and over",
                            "249" => "Males aged 80 to 84",
                            "248" => "Males aged 75 to 79",
                            "247" => "Males aged 70 to 74",
                            "246" => "Males aged 65 to 69",
                            "245" => "Males aged 60 to 64",
                            "244" => "Males aged 55 to 59",
                            "243" => "Males aged 50 to 54",
                            "241" => "Males aged 45 to 49",
                            "240" => "Males aged 40 to 44",
                            "239" => "Males aged 35 to 39",
                            "238" => "Males aged 30 to 34",
                            "237" => "Males aged 25 to 29",
                            "236" => "Males aged 20 to 24",
                            "235" => "Males aged 15 to 19",
                            "234" => "Males aged 10 to 14",
                            "242" => "Males aged 5 to 9",
                            "233" => "Males aged 0 to 4"
                            );
?>