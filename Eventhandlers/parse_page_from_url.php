<?php


//Instead of basename for  Windows/Linux-compatibility
function mb_basename($path) {
    if (preg_match('@^.*[\\\\/]([^\\\\/]+)$@s', $path, $matches)) {
        return $matches[1];
    } else if (preg_match('@^([^\\\\/]+)$@s', $path, $matches)) {
        return $matches[1];
    }
    return '';
  }


if(str_contains($_SERVER['QUERY_STRING'], '&')){
    //explode/implode are needed because the query variable hashes can have a slash, which is
    // when mb_basename believes some part of the hash value to be the entire QUERY_STRING
    $onlypagevariable=strstr(mb_basename(implode(explode("/",$_SERVER['QUERY_STRING']))),'&',true);
    //echo $onlypagevariable;
    $currentpage=rawurldecode(substr(strstr($onlypagevariable,'=',false),1)); 
    
  }
  else{
    $currentpage=rawurldecode(substr(strstr($_SERVER['QUERY_STRING'],'=',false),1));
  }


  ?>