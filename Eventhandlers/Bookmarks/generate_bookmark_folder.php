<?php

    //TODO: experimenting with bookmark folder generation
    function generate_bookmark_folder($user_bookmark_tags, $user_bookmark_tags_as_array, $all_user_bookmark_tags_thus_far){

    //Check if the folder has already been generated
    if(in_array($user_bookmark_tags, $all_user_bookmark_tags_thus_far) == false){
        echo "<br>".$user_bookmark_tags." is not in \"".implode(" ",$all_user_bookmark_tags_thus_far)."\", creating the folder is permitted";
        foreach($user_bookmark_tags_as_array as $user_bookmark_tag_key => $user_bookmark_tag_value){
                        
            if($user_bookmark_tag_key < count($user_bookmark_tags_as_array)-1){
                echo "<li><span class=\"caret\">$user_bookmark_tag_value</span>";
                echo "<ul class=\"nested\">".$user_bookmark_tags_as_array[$user_bookmark_tag_key+1]."</ul>";
                echo "</li>";
            }
        
        }
        array_push($all_user_bookmark_tags_thus_far,$user_bookmark_tags);
        
    }
    echo implode(' ',$all_user_bookmark_tags_thus_far);

}


?>