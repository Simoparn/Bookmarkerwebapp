<?php

    //TODO: experimenting with bookmark folder generation
    //function generate_bookmark_folder($connection,$user_bookmark_tags_as_array){
    function generate_bookmark_folder($connection){
    //function generate_bookmark_folder($connection, $user_bookmark_tags, $user_bookmark_tags_as_array){





    /*Check if the folder has already been generated
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
    echo implode(' ',$all_user_bookmark_tags_thus_far);*/





/*
    $all_user_bookmark_tags_thus_far=array();
    $final_bookmark_folder_structure=array("top_level_folders"=>array(),"folder_location_relative_to_top"=>array(),"subfolders_relative_to_folder_location"=>array());
    $retrieve_unique_tags_for_user_query=$connection->prepare("SELECT DISTINCT tags from tagsofbookmarks INNER JOIN bookmarksofusers ON tagsofbookmarks.tags_id=bookmarksofusers.tags_id AND bookmarksofusers.username = ?");
    $retrieve_unique_tags_for_user_query->bind_param("s", $_SESSION["username"]);
    
    if($retrieve_unique_tags_for_user_query->execute()){
        $retrieve_unique_tags_for_user_query->store_result();
        $retrieve_unique_tags_for_user_query->bind_result($unique_tags_for_user);
        //echo $retrieve_unique_tags_for_user_query->num_rows();
        while($retrieve_unique_tags_for_user_query->fetch()){
            $unique_tags_as_array=explode(" ",$unique_tags_for_user);
            echo "<br><br> unique tags for the bookmark:".$unique_tags_for_user;
            echo "<br> top level tag: ".$unique_tags_as_array[0];
            echo "<br> all top level folders thus far:";
            foreach($final_bookmark_folder_structure["top_level_folders"] as $top_level_key=>$top_level_value){
                echo " ".$top_level_key." => ".$top_level_value." ";
            
            }
            echo "<br> all subfolders thus far:";
            foreach($final_bookmark_folder_structure["subfolders_relative_to_folder_location"] as $subfolder_key=>$subfolder_value){
                echo " ".$subfolder_key." => ".$subfolder_value." ";
            
            }


            foreach($unique_tags_as_array as $unique_tag_as_array_key=>$unique_tag_as_array_value){
                
                //save the top-level folders
                if($unique_tag_as_array_key==0){
                    if(in_array($unique_tags_as_array[$unique_tag_as_array_key],$final_bookmark_folder_structure["top_level_folders"])==false){
                        array_push($final_bookmark_folder_structure["top_level_folders"],$unique_tags_as_array[0]);
                        $top_level_folder_key=array_search($unique_tags_as_array[0],$final_bookmark_folder_structure["top_level_folders"]);
                    }
                }
                //otherwise check for subfolders
                else{
                    if(in_array($unique_tags_for_user,$final_bookmark_folder_structure["folder_location_relative_to_top"]) == false){
                        array_push($final_bookmark_folder_structure["folder_location_relative_to_top"],$unique_tags_for_user);
                        //Then create the subfolders for the folder path on each horizontal non-top level
                        $folder_location_key=array_search($unique_tags_for_user, $final_bookmark_folder_structure["folder_location_relative_to_top"]);
                        //echo "<br><br>final folder structure before retrieving folder location key for ".$unique_tags_for_user." <br>:";
                        //print_r($final_bookmark_folder_structure);
                        foreach($final_bookmark_folder_structure["folder_location_relative_to_top"] as $location_debug_key => $location_debug_value){
                            if($location_debug_key == $folder_location_key){
                                echo "<br><br>top-level folder key \"".$top_level_folder_key."\" and folder location before creating subfolders:<br> ".$location_debug_value;
                                array_push($final_bookmark_folder_structure['subfolders_relative_to_folder_location'], $unique_tags_as_array[$unique_tag_as_array_key]);
                                //echo "<br>created subfolder:".$final_bookmark_folder_structure["subfolders_relative_to_folder_location"];
                            }

                           
                        }
                        
                    }
                }
        


            //echo "<br> All user bookmark tags thus far in iteration: ".print_r($all_user_bookmark_tags_thus_far);
            }


        }

        echo "<br> ALL TOP LEVEL FOLDERS IN THE END:";
        foreach($final_bookmark_folder_structure["top_level_folders"] as $top_level_key=>$top_level_value){
            echo " ".$top_level_key." => ".$top_level_value." ";
            
            //echo "<br folder locations for the top level folder".$top_level_value.": ";

            //for($i=0; $i<count($final_bookmark_folder_structure["folder_location_relative_to_top"]); $i++){
            //        echo " ".$final_bookmark_folder_structure[$top_level_key][$i];
            //    
            //        //echo " ".$location_relative_key." => ".$location_relative_value.": ";
            //}
        }
        
        echo "<br>FINAL FOLDER STRUCTURE: ";
        print_r($final_bookmark_folder_structure);
        //echo "<br>Iterating through final folder structure: ";
        //foreach($final_bookmark_folder_structure as $final_bookmark_folder_key=>$final_bookmark_folder_value){
        //    print_r($final_bookmark_folder_value);
        //}
        $retrieve_unique_tags_for_user_query->free_result();
        
    }

    
*/


    //subarrays: folder path (unique list of tags) and the subfolder in the folder path 
    $final_bookmark_folder_structure=array("folder_location"=>array("subfolder_in_folder_location"=>array()));
    $retrieve_unique_tags_for_user_query=$connection->prepare("SELECT DISTINCT tags from tagsofbookmarks INNER JOIN bookmarksofusers ON tagsofbookmarks.tags_id=bookmarksofusers.tags_id AND bookmarksofusers.username = ? ORDER BY tags ASC");
    $retrieve_unique_tags_for_user_query->bind_param("s", $_SESSION["username"]);
    if($retrieve_unique_tags_for_user_query->execute()){
        $retrieve_unique_tags_for_user_query->store_result();
        $retrieve_unique_tags_for_user_query->bind_result($unique_tags_for_user);
        
        $cached_unique_tags=null;
        $cached_unique_tags_as_array=null;

        $tags_iteration=0;
        while($retrieve_unique_tags_for_user_query->fetch()){
            $unique_tags_as_array=explode(" ",$unique_tags_for_user);
            echo "<br><br> unique tags for the bookmark:".$unique_tags_for_user;

            if($cached_unique_tags != null && $cached_unique_tags_as_array != null){
                echo "<br>comparing \"".$unique_tags_for_user."\" and cached \"".$cached_unique_tags."\"";
                if($cached_unique_tags== substr($unique_tags_for_user,0,strlen($cached_unique_tags))){
                    echo "<br>cached tags (folder path) are fully included in current tags, counting the ending tags next";
                    $current_and_cached_tags_count_difference=count($unique_tags_as_array)-count($cached_unique_tags_as_array);
                    if($current_and_cached_tags_count_difference==0){
                        echo "<br> cached and current tags match and are of equal length, create only the cached folder path, no subfolders are needed";
                        array_push($final_bookmark_folder_structure[$tags_iteration][$tags_iteration],$cached_unique_tags);
                        //array_push($final_bookmark_folder_structure[$tags_iteration][$tags_iteration],$cached_unique_tags_as_array);
                    }      
                    else if($current_and_cached_tags_count_difference > 0){
                        echo "<br>cached tags count is ".$current_and_cached_tags_count_difference." less than current tags count, subfolders are needed";
                    }
                    else{
                        echo "<br>cached tags count is ".$current_and_cached_tags_count_difference*(-1)." more than current tags count, subfolders are not needed";
                    }
                }
                else{
                    echo "<br>cached tags are not included in the current tags, it is safe to generate both folder paths";
                    //array_push($final_bookmark_folder_structure[$tags_iteration][$tags_iteration],$cached_unique_tags);
                    //array_push($final_bookmark_folder_structure[$tags_iteration+1][$tags_iteration+1],$unique_tags);
                    
                }
            }
            $cached_unique_tags=$unique_tags_for_user;
            $cached_unique_tags_as_array=explode(" ",$unique_tags_for_user);

            $tags_iteration=$tags_iteration+1;

        }
        $retrieve_unique_tags_for_user_query->free_result();
    }


        echo "<br>FINAL FOLDER STRUCTURE: ";
        print_r($final_bookmark_folder_structure);


    /*$unique_tags_for_user=array_unique($user_bookmark_tags_as_array);
    foreach($unique_tags_for_user as $unique_tags_key=>$unique_tags_value){
        echo $unique_tags_value;
    }*/



    }

?>