/**
 * Вывод значения выбранного пользователем
 * @param {string} val 
 */
function setAnswerValue(val) {
	document.querySelector("#s1-value").innerHTML = val;
}

/**
 * Parse query string to array
 * @param {string} query 
 */
function parse_query_string(query) {
  var vars = query.split("&");
  var query_string = {};
  for (var i = 0; i < vars.length; i++) {
		var pair = vars[i].split("=");
		var key = decodeURIComponent(pair[0]);
		var value = decodeURIComponent(pair[1]);
		// If first entry with this name
		if (typeof query_string[key] === "undefined") {
			query_string[key] = decodeURIComponent(value);
			// If second entry with this name
		} else if (typeof query_string[key] === "string") {
			var arr = [query_string[key], decodeURIComponent(value)];
			query_string[key] = arr;
			// If third or later entry with this name
		} else {
			query_string[key].push(decodeURIComponent(value));
		}
  }
  return query_string;
}

/**
 * Get user identity from query string
 */
function getClientId() {
	if (parse_query_string(window.location.search.substring(1)).clientid == undefined) {
		return "";
	}
	return parse_query_string(window.location.search.substring(1)).clientid;
}

/**
 * Подготовка обекта для запроса к Api, обработка результата
 */
function saveResult() {
	var sliderValue, surveyValue, response;

	sliderValue = document.querySelector("#s1-value").innerHTML;
	surveyValue = {
	  "system": "SMS",
	  "abId": getClientId(),
	  "question": 90,
	  "answer": sliderValue
	}
	document.querySelector("#surveyValue").innerHTML = surveyValue;

	response = JSON.parse(saveRequest(surveyValue));
	if (response.itemId) {
		let q1 = document.querySelector('#question1')
		q1.setAttribute('class', q1.getAttribute('class') + ' hidden');
		let classA = document.querySelector('#saveResult').getAttribute('class').replace(new RegExp('(?:^|\\s)'+ 'hidden' + '(?:\\s|$)'), ' ');
		document.querySelector('#saveResult').setAttribute('class', classA);
	}
}

/**
 * Request to Api
 * @param {object} surveyValue 
 */
function saveRequest(surveyValue) {
	// Создаём новый объект XMLHttpRequest
	var xhr = new XMLHttpRequest();

	// Конфигурируем его: GET-запрос на URL 'phones.json'
	xhr.open('POST', '/survey/api.php?action=addSurveyAnswer', false);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	
	// Отсылаем запрос
	xhr.send('survey=' + JSON.stringify(surveyValue));
	
	// Если код ответа сервера не 200, то это ошибка
	if (xhr.status != 200) {
	  // обработать ошибку
	  return false;
	} else {
	  // вывести результат
	  return xhr.responseText;
	}
}

/**
 * Проверка доступности опроса
 * @param {string} clientId 
 */
function isAvailableSurvey(clientId) {
	// 1. Создаём новый объект XMLHttpRequest
	var xhr = new XMLHttpRequest();

	// 2. Конфигурируем его: GET-запрос на URL 'phones.json'
	xhr.open('POST', '/survey/api.php?action=isAvailable', false);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	
	// 3. Отсылаем запрос
	xhr.send('survey={"system":"SMS","abId":"' + clientId + '"}');
	
	// 4. Если код ответа сервера не 200, то это ошибка
	if (xhr.status != 200) {
	  // обработать ошибку
	  // alert( xhr.status + ': ' + xhr.statusText ); // пример вывода: 404: Not Found
	  return false;
	} else {
	  // вывести результат
	  // alert( xhr.responseText ); // responseText -- текст ответа.
	  return xhr.responseText;
	}
}

/**
 * Обработчик результата проверки доступности опроса
 */
function checkAvailableSurve() {
	var response, preloder, error;

	response = JSON.parse(isAvailableSurvey(getClientId()));
	error = document.querySelector('#error1');
	preloder = document.querySelector('#p2');

	preloder.setAttribute('class', preloder.getAttribute('class') + ' hidden');

	if (!response) {
		let classA = error.getAttribute('class').replace(new RegExp('(?:^|\\s)'+ 'hidden' + '(?:\\s|$)'), ' ');
		error.setAttribute('class', classA);
		document.querySelector('#errorMsg').innerText = 'Ошибака на сервере.';
		return false;
	}
	if (response.Error) {
		let classA = error.getAttribute('class').replace(new RegExp('(?:^|\\s)'+ 'hidden' + '(?:\\s|$)'), ' ');
		error.setAttribute('class', classA);
		document.querySelector('#errorMsg').innerText = response.Error.message;
		return false;
	}
	if (!response.isAvailable) {
		let classA = error.getAttribute('class').replace(new RegExp('(?:^|\\s)'+ 'hidden' + '(?:\\s|$)'), ' ');
		error.setAttribute('class', classA);
		document.querySelector('#errorMsg').innerText = "Вы уже проходили этот опрос.";
		return false;
	}

	return response.isAvailable;
}


document.addEventListener('DOMContentLoaded', function() {
	if (checkAvailableSurve()) {
		let classA = document.querySelector('#question1').getAttribute('class').replace(new RegExp('(?:^|\\s)'+ 'hidden' + '(?:\\s|$)'), ' ');
		document.querySelector('#question1').setAttribute('class', classA);
		setAnswerValue(document.querySelector('#s1').value);
	}
});