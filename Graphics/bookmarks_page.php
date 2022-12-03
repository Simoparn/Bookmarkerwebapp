
<div id="pagecontent">
<p>
<span class="paragraphtitle">Import your bookmarks</span>
<form method="post" enctype="multipart/form-data" action=".\Eventhandlers\handle_load_bookmarks.php">
<label for="bookmarks_file">Load your browser bookmarks file (.html) from your computer.</label>
<input type="file" id="bookmarks_file" name="bookmarks_file" value="browse files">
<input type="submit" value="Upload the bookmarks file">
</form>
</p>
<p>
<span class="paragraphtitle">Bookmarks exported to database</span>
<?php
    //TODO: Experiment to check the structure for conversion to database
    require './vendor/autoload.php';
    use Shaarli\NetscapeBookmarkParser\NetscapeBookmarkParser;

    $filenames=scandir('./uploads/');
    $filename=$filenames[2];
    //echo "FILENAME HERE: ".$filename;
    $parser = new NetscapeBookmarkParser();
    $bookmarks_panel_items = $parser->parseFile('./uploads/'.$filename);

    echo "<table>";
    foreach($bookmarks_panel_items as $bookmark_key=>$bookmark_value){
        
        //order when printing highest level item values: bookmark, image header, url, tags (as an array), description, creation date, publicity (possible values atleast public)
        foreach($bookmark_value as $bmv_key=>$bmv_value){
            echo "<tr>";
            //folder structure of each bookmark is described with the tags array
            if($bmv_key != "tags"){
                echo "<td>$bmv_key</td><td>$bmv_value</td>";
            }
            else{
                foreach($bmv_value as $bmv_value_key => $bmv_value_value){
                    echo "<tr><td>$bmv_value_key</td><td>$bmv_value_value</td></tr>";
                }
            }
            echo "</tr>";
        }
        
    }

    echo "</table>";
?>
</p>
</div>








