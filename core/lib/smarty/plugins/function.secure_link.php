<?php
function smarty_function_secure_link($params, &$smarty){
    $output = "";
    $linkName = $params['linkName'];
    $linkValue = $params['linkValue'];
    $linkType = $params['linkType'];
    $class1 = $params['class1'];
    $class2 = $params['class2'];
    $permission = $params['permission'];

    switch ($linkType){
        case 'delete' :
            if($permission == 0 || $permission == 1 || $permission == 2) $output = "<span class='".$class2."'>".$linkName."</span>";
            else if($permission == 3) {
                $output = "<a href='".$linkValue."' class='".$class1."'>".$linkName."</a>";
            }
            break;
        case 'add':
            if($permission == 0 || $permission == 1 ) $output = "<span class='".$class2."'>".$linkName."</span>";
            else if($permission == 3 || $permission == 2) $output = "<a href='".$linkValue."' class='".$class1."'>".$linkName."</a>";
            break;
        case 'edit':
            if($permission == 0) $output = "<span class='".$class2."'>".$linkName."</span>";
            else if($permission == 3 || $permission == 2 || $permission == 1) $output = "<a href='".$linkValue."' class='".$class1."'>".$linkName."</a>";
            break;
    }

    return $output;
}
?>
