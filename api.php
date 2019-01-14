<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once('class-survey.php');
if (!headers_sent()) {
    header('Content-Type: application/json; charset=utf-8');
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
} else {
    // обработка ошибки или уведомление разработчикам
}


$response = Array();
$request = Array();

if (count($_POST) || isset($_POST['action'])) {
    $request = $_POST;
} 

if (count($_GET) || isset($_GET['action'])) {
    $request = array_merge($request,$_GET);
}

if (count($request) && isset($request['survey'])) {
    switch($request['action']) {
        case 'isAvailable': 
            if (isset($request['survey'])) {
                $survey = json_decode($request['survey']);
                $surveyInstance = new Survey($survey);
                $response['isAvailable'] = $surveyInstance->isAvailableSurvay();
            }
        break;
        case 'addSurveyAnswer':
            if (isset($request['survey'])) {
                $survey = json_decode($request['survey']);
				$response['survey'] = $survey;
                $surveyInstance = new Survey($survey);
				$id = $surveyInstance->AddItem();
				switch ($id) {
					case -1:
						$response['Error'] = Array('status'=>-3, 'message'=>"Не заполнены обязательные параметры запроса.");
						break;
					case -2:
						$response['Error'] = Array('status'=>-4, 'message'=>"Ошибка при сохранении результатов.");
						break;
					case -3:
						$response['Error'] = Array('status'=>-5, 'message'=>"Ответ уже сохранен.");
						break;
					default: $response['itemId'] = $id; break;
				}
            }
        break;
        default: 
            $response['Error1'] = Array('status'=>-2, 'message'=>"Метод не найден.");
        break;
    }
} else {
    $response['Error'] = Array('status'=>-1, 'message'=>"Некорректные параметры запроса.");
}

echo json_encode($response);