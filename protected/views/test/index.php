
<div class="row">
    <div id="tests" class="span4">
        <div class="list_head">
            <b>Тесты</b>
            <i class="fa fa-plus"></i>
        </div>
        <ul class="main_list" id="tests_list">
            <?php
            if ($tests) {
                foreach ($tests as $test) {
                    echo $this->renderPartial('li_test', array('model' => $test));
                }
            }
            ?>
        </ul>
    </div>
    <div id="questions" class="span3">
        <div class="list_head">
            <b>Вопросы</b>
            <i class="fa fa-plus"></i>
        </div>
        <ul class="main_list" id="questions_list">
        </ul>
    </div>
    <div id="answers" class="span5">
        <div class="list_head">
            <b>Ответы</b>
            <i class="fa fa-plus"></i>
            <i class="fa fa-bars" data-toggle="modal" data-target="#resultsTextModal"></i>
        </div>
        <ul class="main_list">
        </ul>
    </div>
</div>
<div id="message"></div>

<!-- Modal -->
<div class="modal hide fade" id="resultsTextModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="test/result-text" id="resultsText" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Сообщения по результатам прохождения тестов</h4>
                </div>
                <div class="modal-body">
                    <?php echo $this->renderPartial('li_results', array('results' => $results)); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>

















<?php 

?>