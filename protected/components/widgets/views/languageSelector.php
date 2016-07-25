<?php
/**
 * Widget from 
 * http://habrahabr.ru/post/139689/
 */
?>

<div id="language-select">
<?php
        // Render options as dropDownList
        echo CHtml::form();
        
        foreach($languages as $key=>$lang) {
            
                echo CHtml::hiddenField(
                        $key,
                        LanguageSelector::createMultilanguageReturnUrl($key)
                );
        }
        
        echo CHtml::dropDownList('language', $currentLang, $languages,
                array(
                        'submit'=>'',
                )
        );
        
        echo CHtml::endForm();
?>
</div>