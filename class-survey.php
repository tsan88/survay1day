<?
class Survey {
    /** Коифиг */
    /** идентификатор инфобловка для сохранения результатов */
    private $iBlockId = 52;
    /** Ограничения для опросов по источнику данных ['источник' => ['опрос' => значение]] */
    private $stopCount = Array(
        "SS" => Array('CES'=>384, 'NPS'=> 206),
        "SN" => Array('CES'=>384, 'NPS'=> 206),
        "SMS" => Array('CES'=>384, 'NPS'=> 206)
    );

    /** поля  */
    public $serveyName = null;
    public $system = null;
    public $abId = null;
    public $divId = null;
    public $question = null;
    public $answer = null;
    public $discription = null;
 
    public $isError = false;
    public $errorMsg = '';
    
    /**
     * конструктор инстанса
     *
     * @param [type] $surveyItem
     */
    function __construct($surveyItem) {
        if (is_object($surveyItem)) {

            if (isset($surveyItem->system)) {
                $this->system = $surveyItem->system;
            }
            if (isset($surveyItem->abId)) {
                $this->abId = $surveyItem->abId;
            }
            if (isset($surveyItem->divId)) {
                $this->divId = $surveyItem->divId;
            }
            if (isset($surveyItem->question)) {
                $this->question = $surveyItem->question;
            }
            if (isset($surveyItem->answer)) {
                $this->answer = $surveyItem->answer;
            }
            if (isset($surveyItem->discription)) {
                $this->discription = $surveyItem->discription;
            }
        }
        $iblock = CModule::IncludeModule("iblock");
    }

    /**
     * проверка доступности опроса для пользователя
    *
    * @return boolean
    */
    function isAvailableSurvay() {
        $isAvailableSurvay = false;
 
        if ($this->system != null && $this->abId != null) {
            // construct survay period dates
            $beginPeriodDate = '01.'.date('m.Y').' 00:00:00';
            $endPeriodMonth = (int)date('m')+1;
            $endPeriodDate = '01.'.$endPeriodMonth.'.'.date('Y').' 00:00:00';
            if ($endPeriodMonth > 12) {
                $endPeriodDate = '01.01.'.((int)date('Y')+1).' 00:00:00';
            }
    
            // Answers count by period, system,
            $arFilter = Array("IBLOCK_ID"=>$this->iBlockId, ">=DATE_CREATE"=>$beginPeriodDate, "<DATE_CREATE"=>$endPeriodDate, "PROPERTY_SYSTEM_NAME"=>$this->system);
            $cntAll = CIBlockElement::GetList(array(),$arFilter,array(),false,array('ID', 'NAME'));
			// Answers count by period, system, abId
            $arFilter = Array("IBLOCK_ID"=>$this->iBlockId, ">=DATE_CREATE"=>$beginPeriodDate, "<DATE_CREATE"=>$endPeriodDate, "PROPERTY_SYSTEM_NAME"=>$this->system, "PROPERTY_ABID"=>$this->abId);
            $cntAbId = CIBlockElement::GetList(array(),$arFilter,array(),false,array('ID', 'NAME'));

            if (!$this->stopCount[$this->system] || $cntAbId) {
                $isAvailableSurvay = false;
            } else {
                switch ($this->system) {
                    case 'SS': 
                    case 'SN': 
                    if ($cntAll < $this->stopCount[$this->system]['NPS']) {
                        $isAvailableSurvay = true;
                    }
                    break;
                    case 'SMS': 
                    if ($ccntAllnt < $this->stopCount[$this->system]['CES']) {
                        $isAvailableSurvay = true;
                    }
                    break;
                }
            }
        }
 
        return $isAvailableSurvay;
    }
 
    /**
     * Добавление записи в инфоблок результатов опроса
     *
     * @return void
     */
    function AddItem() {
		if (!isset($this->system)
			|| !isset($this->abId)
			|| !isset($this->question)
			|| !isset($this->answer)) {
			return -1;
		}

		if (!$this->isAvailableSurvay()) {
			return -3;
		}
		switch($this->system) {
			case 'SS':
			case 'SN':
				$this->question = 91; break;
			case 'SMS':
				$this->question = 90; break;
			break;
			default:
			break;
		}

        $el = new CIBlockElement;
    
        $PROP = array( 
            "SYSTEM_NAME" =>  $this->system,
            "ABID" =>  $this->abId,
            "DIVID" =>  $this->divId,
			"QUESTION" => Array('VALUE'=>$this->question), // [90,91]
            "ANSWER" =>  $this->answer,
            "DESCRIPTION" =>  $this->discription
        );
        $arLoadProductArray = Array(
            "IBLOCK_ID"      => $this->iBlockId,
            "PROPERTY_VALUES"=> $PROP,
            "NAME" => $this->abId,
            "ACTIVE"         => "Y",            // активен
			//"PREVIEW_TEXT" => $_POST["desc"],
            "ACTIVE_FROM" => date('d.m.Y H:i:s'),
        );

        if ($IBLOCK_ELEMENT_ID = $el->Add($arLoadProductArray)) {
            return $IBLOCK_ELEMENT_ID;
        }
        else {
            error_log("Error while add item. Error: ".$el->LAST_ERROR);
			return -2;
        }
    }
 
}