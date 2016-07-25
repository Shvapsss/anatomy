<?php

foreach ($results as $i => $result) {
    echo $this->renderPartial('li_result_text', array('result' => $result));
}
for ($i; $i < 7; $i++) {
    echo $this->renderPartial('li_result_text');
}