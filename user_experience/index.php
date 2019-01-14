<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Опрос</title>

	  <link rel="shortcut icon" href="./../../favicon.png">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.blue-orange.min.css">
    <link rel="stylesheet" href="survey.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>

	  <script type="text/javascript" src="survey.js"></script>
  </head>
  <body>
    <!-- Always shows a header, even in smaller screens. -->
	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
		<header class="mdl-layout__header">
			<div class="mdl-layout-icon"><img src="/img/icon-logo.png" class="logo" alt="АО &quot;Новосибирскэнергосбыт&quot; "></div>
			<div class="mdl-layout__header-row">
			  <!-- Title -->
			  <span class="mdl-layout-title">Новосибирскэнергосбыт</span>
			  <!-- Add spacer, to align navigation to the right -->
			  <div class="mdl-layout-spacer"></div>
			  <!-- Navigation. We hide it in small screens. -->
			  <!-- <nav class="mdl-navigation mdl-layout--large-screen-only">
				<a class="mdl-navigation__link" href="">Link</a>
				<a class="mdl-navigation__link" href="">Link</a>
				<a class="mdl-navigation__link" href="">Link</a>
				<a class="mdl-navigation__link" href="">Link</a>
			  </nav> -->
			</div>
		</header>
		<main class="mdl-layout__content">
			<div class="page-content">

<div class="mdl-grid">
	<div class="mdl-cell mdl-cell--3-col"></div>
	<div class="mdl-cell mdl-cell--6-col">

			<!-- MDL Progress Bar with Indeterminate Progress -->
			<div id="p2" class="mdl-progress mdl-js-progress mdl-progress__indeterminate"></div>

			<div class="demo-card-wide mdl-card mdl-shadow--2dp hidden" id="error1">
				<div class="mdl-card__title mdl-card--expand">
					<h2 class="mdl-card__title-text">Опрос не доступен.</h2>
			 	</div>
				<div class="mdl-card__supporting-text">
					<p id="errorMsg"></p>
				</div>
			</div>

			<div class="demo-card-wide mdl-card mdl-shadow--2dp hidden" id="saveResult">
				<div class="mdl-card__title mdl-card--expand">
					<h2 class="mdl-card__title-text">Спасибо за Вашу оценку!</h2>
			 	</div>
				<div class="mdl-card__supporting-text">
					<p id="errorMsg"></p>
				</div>
			</div>

			<div class="demo-card-wide mdl-card mdl-shadow--2dp hidden" id="question1">
			  <div class="mdl-card__title mdl-card--expand">
				<h2 class="mdl-card__title-text">Вы недавно обращались в АО "Новосибирскэнергосбыт".
Оцените пожалуйста, насколько легко вам было получить услуги или купить товар?</h2>
			  </div>
			  <div class="mdl-card__supporting-text">
				<p>Где 1 - трудно, 5 - очень легко.</p>
				<form id="survey" action="/survey/api.php">
				  <div class="mdl-textfield mdl-js-textfield">
  					<input class="mdl-slider mdl-js-slider" type="range" id="s1" min="1" max="5" value="0" step="1" value="3" oninput = "showMessage(this.value)" onchange = "showMessage(this.value)">
					  <ul class='legend'>
						<li>1</li>
						<li>2</li>
						<li>3</li>
						<li>4</li>
						<li>5</li>
					  </ul>
				  </div>
					<div class="mdl-textfield mdl-js-textfield">Ваша оценка: <span id="s1-value"></span></div>
					<input type="hidden" name="action" value="addSurveyAnswer" />
					<input type="hidden" name="survey" value="" id="surveyValue" />
				</form>
			  </div>
			  <div class="mdl-card__actions">
				<a class="mdl-button mdl-button--raised mdl-js-button mdl-js-ripple-effect mdl-button--accent mdl-button--right" onclick="saveResult()">
				  Ответить
				</a>
			  </div>
			</div>
	</div>
	<div class="mdl-cell mdl-cell--3-col"></div>
</div>
			</div>
		</main>
		<footer class="mdl-mini-footer">
		  <div class="mdl-mini-footer__left-section">
			<ul class="mdl-mini-footer__link-list">
				<li><a href="/">Новосибирскэнергосбыт</a></li>
			</ul>
		  </div>
		</footer>
	</div>
  </body>
</html>