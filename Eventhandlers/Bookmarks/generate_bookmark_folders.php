<?php

    //TODO: experimenting with bookmark folder generation
    //function generate_bookmark_folder($connection,$user_bookmark_tags_as_array){
    function generate_bookmark_folders($connection){
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




    /*while($get_users_bookmarks_query->fetch()){
        echo "user bookmark urls: ".gettype($user_bookmark_urls);
    }*/


    

    //subarrays: folder path (unique list of tags) and the subfolder in the folder path 
    $final_bookmark_folder_structure=array();
    $retrieve_unique_tags_for_user_query=$connection->prepare("SELECT DISTINCT tags from tagsofbookmarks INNER JOIN bookmarksofusers ON tagsofbookmarks.tags_id=bookmarksofusers.tags_id AND bookmarksofusers.username = ? ORDER BY tags ASC");
    $retrieve_unique_tags_for_user_query->bind_param("s", $_SESSION["username"]);
    if($retrieve_unique_tags_for_user_query->execute()){
        $retrieve_unique_tags_for_user_query->store_result();
        $retrieve_unique_tags_for_user_query->bind_result($unique_folder_location_for_user);
        
        $all_unique_user_folder_locations_as_array=array();

        while($retrieve_unique_tags_for_user_query->fetch()){
            echo "<br>unique folder:".$unique_folder_location_for_user;
            array_push($all_unique_user_folder_locations_as_array,$unique_folder_location_for_user);

        }
        //echo "<br><br>all unique folders for user: ";
        //var_dump($all_unique_user_folder_locations_as_array);

        

        //$cached_user_folder_location_value=null;

        foreach($all_unique_user_folder_locations_as_array as $user_folder_location_key=>$user_folder_location_value){
                //echo "<br>subfolder in unique folder location ".$user_folder_location_as_array_key.": ".$user_folder_value;
  
            $user_folders_as_array=explode(" ",$user_folder_location_value);

            foreach($user_folders_as_array as $user_folder_key => $user_folder_value){
                //check top-level folders for each path
                if($user_folder_key==0){
                
                    
                    if(array_key_exists($user_folder_value, $final_bookmark_folder_structure) == false){
                        echo "<br><br> top level folder key ".$user_folder_value." was not in: ";
                        print_r($final_bookmark_folder_structure);
                        echo ", push and continue for level 2 folder";
                        //array_push($final_bookmark_folder_structure, array($user_folder_value=>array()));
                        $final_bookmark_folder_structure[$user_folder_value]=array();
                    
                        
                    }
                   
                }
                //2nd level folders
                if($user_folder_key==1){
                    

                    if(array_key_exists($user_folder_value, $final_bookmark_folder_structure[$cached_top_level_folder_value]) == false){
                        echo "<br>the level 2 key $user_folder_value doesn't exist for final folder structure, creating keys to level 2:";
                        print_r($final_bookmark_folder_structure);
                        $final_bookmark_folder_structure[$cached_top_level_folder_value][$user_folder_value]=array();
                        echo "<br>level 2 folders: ";
                        print_r($final_bookmark_folder_structure[$cached_top_level_folder_value]);
                        
                    }
                }

                
                //3rd level folders
                if($user_folder_key==2){

                    if(array_key_exists($user_folder_value, $final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value]) == false){
                        echo "<br>the level 3 key $user_folder_value doesn't exist for final folder structure, creating keys to level 3:";
                        print_r($final_bookmark_folder_structure);  
                        $final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$user_folder_value]=array();
                        echo "<br>level 3 folders: ";
                        print_r($final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value]);
                        
                    }
                }


                //4th level folders
                if($user_folder_key==3){

                    if(array_key_exists($user_folder_value, $final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value]) == false){
                        echo "<br>the level 4 key $user_folder_value doesn't exist for final folder structure, creating keys to level 4:";
                        print_r($final_bookmark_folder_structure);  
                        $final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$user_folder_value]=array();
                        echo "<br>level 4 folders: ";
                        print_r($final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value]);
                    
                    }
                }


                

                //5th level folders
                if($user_folder_key==4){

                    if(array_key_exists($user_folder_value, $final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value]) == false){
                        echo "<br>the level 5 key $user_folder_value doesn't exist for final folder structure, creating keys to level 5:";
                        print_r($final_bookmark_folder_structure);  
                        $final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value][$user_folder_value]=array();
                        echo "<br>level 5 folders: ";
                        print_r($final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value]);
                    
                    }
                }


                //6th level folders
                if($user_folder_key==5){

                    if(array_key_exists($user_folder_value, $final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value][$cached_fifth_level_folder_value]) == false){
                        echo "<br>the level 6 key $user_folder_value doesn't exist for final folder structure, creating keys to level 6:";
                        print_r($final_bookmark_folder_structure);  
                        $final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value][$cached_fifth_level_folder_value][$user_folder_value]=array();
                        echo "<br>level 6 folders: ";
                        print_r($final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value][$cached_fifth_level_folder_value]);
                    
                    }
                }

                
                //7th level folders
                if($user_folder_key==6){

                    if(array_key_exists($user_folder_value, $final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value][$cached_fifth_level_folder_value][$cached_sixth_level_folder_value]) == false){
                        echo "<br>the level 7 key $user_folder_value doesn't exist for final folder structure, creating keys to level 7:";
                        print_r($final_bookmark_folder_structure);  
                        $final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value][$cached_fifth_level_folder_value][$cached_sixth_level_folder_value][$user_folder_value]=array();
                        echo "<br>level 7 folders: ";
                        print_r($final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value][$cached_fifth_level_folder_value][$cached_sixth_level_folder_value]);
                    
                    }
                }

                //8th level folders
                if($user_folder_key==7){

                    if(array_key_exists($user_folder_value, $final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value][$cached_fifth_level_folder_value][$cached_sixth_level_folder_value][$cached_seventh_level_folder_value]) == false){
                        echo "<br>the level 8 key $user_folder_value doesn't exist for final folder structure, creating keys to level 8:";
                        print_r($final_bookmark_folder_structure);  
                        $final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value][$cached_fifth_level_folder_value][$cached_sixth_level_folder_value][$cached_seventh_level_folder_value][$user_folder_value]=array();
                        echo "<br>level 8 folders: ";
                        print_r($final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value][$cached_fifth_level_folder_value][$cached_sixth_level_folder_value][$cached_seventh_level_folder_value]);
                    
                    }
                }

                //9th level folders
                if($user_folder_key==8){

                    if(array_key_exists($user_folder_value, $final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value][$cached_fifth_level_folder_value][$cached_sixth_level_folder_value][$cached_seventh_level_folder_value][$cached_eight_level_folder_value]) == false){
                        echo "<br>the level 9 key $user_folder_value doesn't exist for final folder structure, creating keys to level 9:";
                        print_r($final_bookmark_folder_structure);  
                        $final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value][$cached_fifth_level_folder_value][$cached_sixth_level_folder_value][$cached_seventh_level_folder_value][$cached_eight_level_folder_value][$user_folder_value]=array();
                        echo "<br>level 9 folders: ";
                        print_r($final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value][$cached_fifth_level_folder_value][$cached_sixth_level_folder_value][$cached_seventh_level_folder_value][$cached_eight_level_folder_value]);
                    
                    }
                }


                //10th level folders
                if($user_folder_key==9){

                    if(array_key_exists($user_folder_value, $final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value][$cached_fifth_level_folder_value][$cached_sixth_level_folder_value][$cached_seventh_level_folder_value][$cached_eight_level_folder_value][$cached_ninth_level_folder_value]) == false){
                        echo "<br>the level 10 key $user_folder_value doesn't exist for final folder structure, creating keys to level 10:";
                        print_r($final_bookmark_folder_structure);  
                        $final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value][$cached_fifth_level_folder_value][$cached_sixth_level_folder_value][$cached_seventh_level_folder_value][$cached_eight_level_folder_value][$cached_ninth_level_folder_value][$user_folder_value]=array();
                        echo "<br>level 10 folders: ";
                        print_r($final_bookmark_folder_structure[$cached_top_level_folder_value][$cached_second_level_folder_value][$cached_third_level_folder_value][$cached_fourth_level_folder_value][$cached_fifth_level_folder_value][$cached_sixth_level_folder_value][$cached_seventh_level_folder_value][$cached_eight_level_folder_value][$cached_ninth_level_folder_value]);
                    
                    }
                }

                
                if($user_folder_key==0){
                    $cached_top_level_folder_value=$user_folder_value;
                }
                if($user_folder_key==1){
                    $cached_second_level_folder_value=$user_folder_value;
                }
                if($user_folder_key==2){
                    $cached_third_level_folder_value=$user_folder_value;
                }
                if($user_folder_key==3){
                    $cached_fourth_level_folder_value=$user_folder_value;
                }
                if($user_folder_key==4){
                    $cached_fifth_level_folder_value=$user_folder_value;
                }
                if($user_folder_key==5){
                    $cached_sixth_level_folder_value=$user_folder_value;
                }
                if($user_folder_key==6){
                    $cached_seventh_level_folder_value=$user_folder_value;
                }
                if($user_folder_key==7){
                    $cached_eight_level_folder_value=$user_folder_value;
                }
                if($user_folder_key==8){
                    $cached_ninth_level_folder_value=$user_folder_value;
                }
                if($user_folder_key==9){
                    $cached_tenth_level_folder_value=$user_folder_value;
                }

            }
        }

        $retrieve_unique_tags_for_user_query->free_result();
    }

    
        echo "<br><br>FINAL FOLDER STRUCTURE: ";
        //foreach($final_bookmark_folder_structure as $structure_key=>$structure_value){
            print_r($final_bookmark_folder_structure);
        //}

        foreach($final_bookmark_folder_structure as $top_level_key=>$top_level_value){
            echo "<br><br>first-level folder for index $top_level_key: ";
                    print_r($top_level_value);
                    foreach($top_level_value as $second_level_key=>$second_level_value){
                        echo "<br>second level folder: ";
                        print_r($second_level_value);
                            foreach($second_level_value as $third_level_key=>$third_level_value){
                                echo "<br>third level folder:";
                                print_r($third_level_value);
                                foreach($third_level_value as $fourth_level_key=>$fourth_level_value){
                                    echo "<br>fourth level folder:";
                                    print_r($fourth_level_value);
                                    foreach($fourth_level_value as $fifth_level_key=>$fifth_level_value){
                                        echo "<br>fifth level folder:";
                                        print_r($fifth_level_value);
                                        
                                    }
                                }
                            }
                    }
        }

        return $final_bookmark_folder_structure;


        


    /*$unique_tags_for_user=array_unique($user_bookmark_tags_as_array);
    foreach($unique_tags_for_user as $unique_tags_key=>$unique_tags_value){
        echo $unique_tags_value;
    }*/



    }

?>