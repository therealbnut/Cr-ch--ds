<?php
    include("config.php");
    
    $method = $_POST["method"];
    
    switch($method){
        case "LocationAutocomplete" :
        
        $location = $_POST["location"];
        
        $responeObj = array();
        
        mysql_connect($HOST, $USERNAME, $PASSWORD)or die("cannot connect");
        mysql_select_db($DATABASE)or die("cannot select DB");
        
        $sql = "SELECT * FROM `locations` WHERE `location_string` LIKE '%".$location."%'";
        
        $result = mysql_query($sql);
                                                        
        while($row = mysql_fetch_assoc($result)){
            $thisRow = new LocationResponseObj();
            $thisRow->abs_id = $row["abs_loc_id"];    
            $thisRow->abs_location_string = $row["location_string"];
            $thisRow->db_id = $row["id"];
            
            $responeObj[] = $thisRow;
        }           
        
        echo json_encode($responeObj);
        break;   
        
        case "GetDataForSpecifiedABSLocID":
              $LocID = $_POST["ABSLocID"];
              $PayLoad = new StatsPayload();
              $soapClient = new SoapClient($SOAPURL_WSDL, array("trace" => 1));
              
              //Get the stats
              $Stats = "";
              foreach($DATASTATSSELECTION_HOUSEHOLDS as $key=>$value){
                  $Stats .= "<Dimension id=\"DATAITEM\">".$key."</Dimension>";
              }
              foreach($DATASTATSSELECTION_FAMILIES as $key=>$value){
                  $Stats .= "<Dimension id=\"DATAITEM\">".$key."</Dimension>";
              }
              foreach($DATASTATSSELECTION_INDIGEN as $key=>$value){
                  $Stats .= "<Dimension id=\"DATAITEM\">".$key."</Dimension>";
              }
              foreach($DATASTATSSELECTION_OCCUPATION as $key=>$value){
                  $Stats .= "<Dimension id=\"DATAITEM\">".$key."</Dimension>";
              }
              foreach($DATASTATSSELECTION_POPULATIONFEMALES as $key=>$value){
                  $Stats .= "<Dimension id=\"DATAITEM\">".$key."</Dimension>";
              }
              foreach($DATASTATSSELECTION_POPULATIONMALES as $key=>$value){
                  $Stats .= "<Dimension id=\"DATAITEM\">".$key."</Dimension>";
              }
              
              $xml_params = "<GetGenericData xmlns=\"http://stats.oecd.org/OECDStatWS/SDMX/\">
      <QueryMessage><message:QueryMessage xmlns=\"http://www.SDMX.org/resources/SDMXML/schemas/v2_0/query\" xmlns:message=\"http://www.SDMX.org/resources/SDMXML/schemas/v2_0/message\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.SDMX.org/resources/SDMXML/schemas/v2_0/query http://www.sdmx.org/docs/2_0/SDMXQuery.xsd http://www.SDMX.org/resources/SDMXML/schemas/v2_0/message http://www.sdmx.org/docs/2_0/SDMXMessage.xsd\">
                    <Header xmlns=\"http://www.SDMX.org/resources/SDMXML/schemas/v2_0/message\">
                        <ID>none</ID>
                        <Test>false</Test>
                        <Truncated>false</Truncated>
                        <Prepared>2013-06-01T16:29:30</Prepared>
                        <Sender id=\"YourID\">
                            <Name xml:lang=\"en\">Your English Name</Name>
                        </Sender>
                        <Receiver id=\"ABS\">
                            <Name xml:lang=\"en\">Australian Bureau of Statistics</Name>
                            <Name xml:lang=\"fr\">Australian Bureau of Statistics</Name>
                        </Receiver>
                    </Header>
                    <Query xmlns=\"http://www.SDMX.org/resources/SDMXML/schemas/v2_0/message\">
                        <DataWhere xmlns=\"http://www.SDMX.org/resources/SDMXML/schemas/v2_0/query\">
                            <And>
                                <DataSet>NRP7</DataSet>
                                <Dimension id=\"FREQUENCY\">A</Dimension>
                                <Attribute id=\"TIME_FORMAT\">P1Y</Attribute>
                                <Time>
                                    <StartTime>2006</StartTime>
                                    <EndTime>2006</EndTime>
                                </Time>
                                <Or>
                                    ".$Stats."
                                </Or>
                                <Or>
                                    <Dimension id=\"ASGC_2008\">".$LocID."</Dimension>
                                </Or>
                            </And>
                        </DataWhere>
                    </Query>
                </message:QueryMessage>
                </QueryMessage>
    </GetGenericData>";
              
              $soapVar = new SoapVar($xml_params, XSD_ANYXML, null, null, null);
              
              try{
                $data = $soapClient->GetGenericData($soapVar);        
              }catch(Exception $e){
                  echo $soapClient->__getLastRequest();
              }
              
              $xmlDoc = new DOMDocument();
              $xmlDoc->loadXML($data->GetGenericDataResult->any);
              
              $SeriesNodes = $xmlDoc->getElementsByTagName("Series");
              
              foreach($SeriesNodes as $key=>$SeriesNode){
                
                $SeriesKey = $SeriesNode->firstChild->firstChild;
                $DataItemID = $SeriesKey->getAttribute("value");
                
                $PayLoad->HouseholdsData->ChartLabel = "Households";
                $PayLoad->HouseholdsData->MeasureType = "PEOPLECOUNT";
                foreach($DATASTATSSELECTION_HOUSEHOLDS as $key=>$value){
                    if($key == $DataItemID){
                        $ThisSeries = new StatsResponseObj();
                        $Label = $DATASTATSSELECTION_HOUSEHOLDS[$DataItemID];
                        $ThisSeries->friendly_label = $Label;
                        $ThisSeries->data_item_id = $DataItemID;

                        $SeriesValue = $SeriesNode->lastChild->lastChild;

                        $DataItemValue = $SeriesValue->getAttribute("value");

                        $ThisSeries->value = $DataItemValue;
                        $PayLoad->HouseholdsData->DataArray[] = $ThisSeries;
                    }
                }
                $PayLoad->FamiliesData->ChartLabel = "Families";
                $PayLoad->FamiliesData->MeasureType = "PEOPLECOUNT";
                foreach($DATASTATSSELECTION_FAMILIES as $key=>$value){
                    if($key == $DataItemID){
                        $ThisSeries = new StatsResponseObj();
                        $Label = $DATASTATSSELECTION_FAMILIES[$DataItemID];
                        $ThisSeries->friendly_label = $Label;
                        $ThisSeries->data_item_id = $DataItemID;

                        $SeriesValue = $SeriesNode->lastChild->lastChild;

                        $DataItemValue = $SeriesValue->getAttribute("value");

                        $ThisSeries->value = $DataItemValue;
                        $PayLoad->FamiliesData->DataArray[] = $ThisSeries;
                    }
                }
                $PayLoad->IndigeneousData->ChartLabel = "Indigenous";
                $PayLoad->IndigeneousData->MeasureType = "PERCENT";
                $IdgPop = 0;
                foreach($DATASTATSSELECTION_INDIGEN as $key=>$value){
                    if($key == $DataItemID){
                        $ThisSeries = new StatsResponseObj();
                        $Label = $DATASTATSSELECTION_INDIGEN[$DataItemID];
                        $ThisSeries->friendly_label = $Label;
                        $ThisSeries->data_item_id = $DataItemID;

                        $SeriesValue = $SeriesNode->lastChild->lastChild;
                        $IdgPop = intval($SeriesValue);

                        $DataItemValue = $SeriesValue->getAttribute("value");

                        $ThisSeries->value = $DataItemValue;
                        $PayLoad->IndigeneousData->DataArray[] = $ThisSeries;
                    }else if(($key == "NonIndig")&&(count($PayLoad->IndigeneousData->DataArray) == 1)){
                        $ThisSeries = new StatsResponseObj();
                        $Label = "Non-indigeneous population";
                        $ThisSeries->friendly_label = $Label;
                        $ThisSeries->data_item_id = 0;

                        $SeriesValue = 100 - $IdgPop;

                        $DataItemValue = $SeriesValue;

                        $ThisSeries->value = $DataItemValue;
                        $PayLoad->IndigeneousData->DataArray[] = $ThisSeries;
                    }
                }
                
                
                
                $PayLoad->OccupationalData->ChartLabel = "Occupations";
                $PayLoad->OccupationalData->MeasureType = "PERCENT";
                foreach($DATASTATSSELECTION_OCCUPATION as $key=>$value){
                    if($key == $DataItemID){
                        $ThisSeries = new StatsResponseObj();
                        $Label = $DATASTATSSELECTION_OCCUPATION[$DataItemID];
                        $ThisSeries->friendly_label = $Label;
                        $ThisSeries->data_item_id = $DataItemID;

                        $SeriesValue = $SeriesNode->lastChild->lastChild;

                        $DataItemValue = $SeriesValue->getAttribute("value");

                        $ThisSeries->value = $DataItemValue;
                        $PayLoad->OccupationalData->DataArray[] = $ThisSeries;
                    }
                }
                $PayLoad->PopulationDataFemales->ChartLabel = "Female Population";
                $PayLoad->PopulationDataFemales->MeasureType = "PEOPLECOUNT";
                foreach($DATASTATSSELECTION_POPULATIONFEMALES as $key=>$value){
                    if($key == $DataItemID){
                        $ThisSeries = new StatsResponseObj();
                        $Label = $DATASTATSSELECTION_POPULATIONFEMALES[$DataItemID];
                        $ThisSeries->friendly_label = $Label;
                        $ThisSeries->data_item_id = $DataItemID;

                        $SeriesValue = $SeriesNode->lastChild->lastChild;

                        $DataItemValue = $SeriesValue->getAttribute("value");

                        $ThisSeries->value = $DataItemValue;
                        $PayLoad->PopulationDataFemales->DataArray[] = $ThisSeries;
                    }
                }
                $PayLoad->PopulationDataMales->ChartLabel = "Male Population";
                $PayLoad->PopulationDataMales->MeasureType = "PEOPLECOUNT";
                foreach($DATASTATSSELECTION_POPULATIONMALES as $key=>$value){
                    if($key == $DataItemID){
                        $ThisSeries = new StatsResponseObj();
                        $Label = $DATASTATSSELECTION_POPULATIONMALES[$DataItemID];
                        $ThisSeries->friendly_label = $Label;
                        $ThisSeries->data_item_id = $DataItemID;

                        $SeriesValue = $SeriesNode->lastChild->lastChild;

                        $DataItemValue = $SeriesValue->getAttribute("value");

                        $ThisSeries->value = $DataItemValue;
                        
                        $PayLoad->PopulationDataMales->DataArray[] = $ThisSeries;
                    }
                }
                             
               
              }
              
              echo json_encode($PayLoad);
        break;     
    }
    
    
class LocationResponseObj{
    public $abs_id;
    public $abs_location_string;
    public $db_id;
}

class StatsResponseObj{
    public $data_item_id;
    public $friendly_label;
    public $value;    
}

class StatSection{
    public $DataArray;
    public $ChartLabel;
    public $MeasureType;
    
    function __construct(){
        $this->DataArray = array();
    }
}

class StatsPayload{
    public $HouseholdsData;
    public $FamiliesData;
    public $IndigeneousData;
    public $OccupationalData;
    public $PopulationDataFemales;
    public $PopulationDataMales;
    
    function __construct(){
        $this->HouseholdsData = new StatSection();
        $this->FamiliesData = new StatSection();
        $this->IndigeneousData = new StatSection();
        $this->OccupationalData = new StatSection();
        $this->PopulationDataFemales = new StatSection();
        $this->PopulationDataMales = new StatSection();
    }
}
?>