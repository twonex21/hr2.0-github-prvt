<?php
function smarty_function_secure_button($params, &$smarty){
    $output = "";
    $linkType = $params['linkType'];
    $class = $params['class'];
    $name =$params['name'];
    $value = $params['value'];
    $permission = $params['permission'];
    
    

    switch ($linkType){
        case 'delete' :
            if($permission == 0 || $permission == 1 || $permission == 2) 
                $output = "<input type='submit' name='".$name."' value='".$value."' class='".$class."' disabled>";
            else if($permission == 3) {
//                $output = "<a href='".$linkValue."' class='".$className."'>".$linkName."</a>";
                $output = "<input type='submit' name='".$name."' value='".$value."' class='".$class."'>";
            }
            break;
        case 'add':
            if($permission == 0 || $permission == 1 ) $output = "<input type='submit' name='".$name."' value='".$value."' class='".$class."' disabled>";
            else if($permission == 3 || $permission == 2) $output = "<input type='submit' name='".$name."' value='".$value."' class='".$class."'>";
            break;
        case 'edit':
            if($permission == 0) $output = "<input type='submit' name='".$name."' value='".$value."' class='".$class."' disabled>";
            else if($permission == 3 || $permission == 2 || $permission == 1) $output = "<input type='submit' name='".$name."' value='".$value."' class='".$class."'>";
            break;           
    }

    return $output;
}
?>