<style>
    h1{
        text-align: center;
    }
.evaluation-box {
    width:70%;
    height:400px;
    display:flex;
    justify-content: center;
    align-items: center;
    background-color: lightgray;
    margin:auto;
    border-radius:7px;
    flex-wrap: wrap;
}
.evaluation-box .evaluate-points {
    display:flex;
    flex-direction: row;
    width:80%;
    justify-content: space-around;
}
.res-block {
    width:200px;
    font-size:23px;
    border:1px solid black;
    text-align: center;
}

.evaluate-percentage{
    display:flex;
    flex-direction: column;
    width:300px;
    border: 1px solid black;
    justify-content: center;
    align-items: center;
}
.res-blockPercentage {
    display:flex;
    flex-direction: column;
    text-align: center;
    font-size: 28px;
}
h4{
    font-size:30px;
    text-align: center;
}

.correctAnswers {
    background-color: greenyellow;
}
.badAnswers {
    background-color: red;
}
.back-to-quiz-btn{
    font-size:20px;
    text-decoration: underline;

}
</style>

<?php
use App\Libraries\WorkWithDatabaseLibrary;
use App\Libraries\QuestionsLibrary;
$WorkWithDBLibrary = new WorkWithDatabaseLibrary();
$QuestionLibrary = new QuestionsLibrary();

?>

<h1>Tvé výsledky: </h1>
<div class="evaluation-box">
    <div class="evaluate-points">
        <div class="res-block correctAnswers">
            <h4>Správné</h4>
            <?= $WorkWithDBLibrary->getPoints("correct");?>
        </div>
        <div class="res-block badAnswers">
            <h4>Špatné</h4>
            <?= $WorkWithDBLibrary->getPoints("bad");?>
        </div>
    </div>

    <div class="evaluate-percentage">
        <div class="res-blockPercentage success-rate">
                <h4>Úspěšnost</h4>
                <?= round($WorkWithDBLibrary->getPoints("correct") / $QuestionLibrary->getTotalCountOfWholeQuizQuestions() * 100,1);?> %
        </div>
    </div>
</div>
<a class= "back-to-quiz-btn" href = "<?=base_url('/'); ?>">Vrátit se zpět</a>