<style>
.container{
	display:flex;
	justify-content: center;
	width:60%;
	height:50vh;
	background-color:aquamarine;
	border-radius: 10px;
}
.container .quizApp {
	width:100%;
	display:flex;
	justify-content: center;
	flex-wrap: wrap;
	flex-direction: column;
}

.container .quizApp .display_question {
	text-align: center;
	font-size: 25px;
	font-weight: bold;
}
.btn-option{
	width:48%;
	height:150px;
	margin:5px 0px;
}

.div-lock-buttons{
	display:flex;
	justify-content: center;
	padding:30px;
}
</style>

<div class="container">
<?php
use App\Libraries\QuestionsLibrary;
use App\Libraries\OptionsLibrary;
use App\Libraries\AnswersLibrary;

$QuestionLibrary = new QuestionsLibrary();
$OptionLibrary = new OptionsLibrary();
$AnswerLibrary = new AnswersLibrary();

/**/ //tehle kus kodu se nam stara o ulozeni random otazek do pole
/*
$q_index = 0;
$random_questions = array();
foreach($QuestionLibrary->getRandomQuestionId('questions') as $question) {  //funkce mi prati pozadovane pole random otazek s prislusnym id
	$random_questions[$q_index] = $question;
	echo $random_questions[$q_index];
	$q_index++;

}*/

$current_question_id = $QuestionLibrary->getCurrentQuestionId();

$user_id = session()->get('id');  //user id musim potom taky nahradit se session()->get('id')
?>

	<div class="quizApp">
	<div class="questionNums"><?=$QuestionLibrary->getCurrentIndexOfQuestion(session()->get('id_current_quiz_user'), session()->get('id')) ?> / <?= $QuestionLibrary->getTotalCountOfWholeQuizQuestions()?></div>
		<div class="display_question"><?= $QuestionLibrary->getQuestion('questions',$current_question_id)?></div>
			<form action="<?=base_url("QuizController/checkAnswer/$current_question_id") ?>" method="POST">
					<button type="submit" class="btn-option btn btn-secondary" name="myAnswer" value="<?= $OptionLibrary->getOption('currentOptionA');?>"> <?= $OptionLibrary->getOption('currentOptionA');?></button>
					<button type="submit" class="btn-option btn btn-secondary" name="myAnswer" value="<?= $OptionLibrary->getOption('currentOptionB');?>"> <?= $OptionLibrary->getOption('currentOptionB');?></button>
					<button type="submit" class="btn-option btn btn-secondary" name="myAnswer" value="<?= $OptionLibrary->getOption('currentOptionC');?>"> <?= $OptionLibrary->getOption('currentOptionC');?></button>
					<button type="submit" class="btn-option btn btn-secondary" name="myAnswer" value="<?= $OptionLibrary->getOption('currentOptionD');?>"> <?= $OptionLibrary->getOption('currentOptionD');?></button>
				</form>
		</div>
</div>

<div class="div-lock-buttons">
	<?php 
	//zobrazi se pouze kdyz je odpoved zamnknuta -> potom musim kliknout na tlacitko dalsi a zobrazi se dalsi odpoved
	$lock = $AnswerLibrary->getLockVariable(session()->get('id_current_quiz_user')); // to 1 musim nahradit session()->get('id_current_quiz');
	if ($lock == 1): ?>
	<form action="<?=base_url("QuizController/MoveToNextQuestion") ?>" method="POST">
	<button class="btn-next btn btn-warning" disabled>Zobrazit Předešlou otázku</button>
	<button type="submit" class="btn-next btn btn-warning">Zobrazit další otázku</button>
	</form>
	<?php endif ?>
</div>

<?php if (session()->has('ok')) : ?>
		<div class="alert alert-success">
			<?= session('ok') ?>
		</div>
	<?php endif ?>

	<?php if(session()->has('bad')): ?>
		<div class = "alert alert-danger">
			<?= session('bad'); ?>
		</div>
		<div class = "alert alert-primary">
			<h5>Správná odpověď je:</h5>
			<?= session('correctAnswer'); ?>
		</div>
	<?php endif ?>



</body>
</html>
