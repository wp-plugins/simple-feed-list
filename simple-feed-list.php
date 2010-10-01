<?php
/*
Plugin Name: Simple Feed List
Plugin URI: http://www.artiss.co.uk/simple-feed-list
Description: Displays an RSS feed as a list
Version: 2.0
Author: David Artiss
Author URI: http://www.artiss.co.uk
*/
define('simple_feed_list_version','2.0');
function simple_feed_list($feed_url,$add_paras,$desc_flag="") {
    echo "<!-- Simple Feed List v".simple_feed_list_version." | http://www.artiss.co.uk/simple-feed-list -->\n";
    $check_failure=false;
    $list_limit=strtolower(get_feed_parameters($add_paras,"limit"));
    if ($list_limit=="") {
        $list_limit=strtolower($add_paras);
    } else {
        $desc_flag=strtolower(get_feed_parameters($add_paras,"desc"));
        $more=strtolower(get_feed_parameters($add_paras,"more"));
        $target=strtolower(get_feed_parameters($add_paras,"target"));
        if ($target=="") {$target="_blank";}
        $nofollow=strtolower(get_feed_parameters($add_paras,"nofollow"));
        if ($nofollow=="yes") {$nofollow=" rel=\"nofollow\"";} else {$nofollow="";}
    }
    // Validate the list limit
    if ($list_limit=="") {
        $list_limit=20;
    } else {
        if (($list_limit<1)or($list_limit>20)) {$check_failure=report_feed_error("Invalid list limit. It must be between 1 and 20.","Simple Feed List");}
    }
    // If a description flag is specified, ensure it is Yes or No
    if (is_numeric($desc_flag)) {
        $desc_len=$desc_flag;
        $desc_flag="yes";
    } else {
        $desc_len=0;
        if (($desc_flag!="")&&($desc_flag!="yes")&&($desc_flag!="no")) {$check_failure=report_feed_error("Invalid description flag. It must be blank, No, Yes or a length.","Simple Feed List");}
    }
    if ($check_failure!==true) {
        // Fetch in the contents of the RSS file using WordPress' in-built fetch_rss function
        @include_once(ABSPATH.WPINC.'/rss.php');
        @$array=fetch_rss($feed_url);
        if ($array=="") {
            $check_failure=report_feed_error("The supplied feed was invalid.","Simple Feed List");
        } else {
            $items=array_slice($array->items,0,$list_limit);
            // First, get the link to the list
            $list_link=$array->channel['link'];
            // Process each item and display the appropriate details
            foreach ($items as $item) {
                $title=$item[title];
                $link=$item[link];
                $desc=$item[description];
                if (($desc_len>0)&&(strlen($desc)>$desc_len)) {$desc=substr($desc,0,strrpos(substr($desc,0,$desc_len)," "))." [&#8230;]";}
                // Write out the appropriate information for each feed item
                if ($list_limit!=1) {echo "<li>";}
                if ($link!="") {echo "<a href=\"".$link."\" target=\"".$target."\"".$nofollow.">";}
                echo str_replace("&amp;","&",$title);
                if ($link!="") {echo "</a>";}
                if ($desc_flag=="yes") {echo "<br/>".strip_tags(htmlspecialchars_decode($desc));}
                if ($list_limit!=1) {echo "</li>";}
                echo "\n";
            }
            if (($list_link!="")&&($more!="no")) {echo "<li><a href=\"".$list_link."\" target=\"".$target."\"".$nofollow.">More...</a></li>\n";}
        }
    }
    echo "<!-- End of Simple Feed List -->\n";
    return;
}
// Function to extract parameters from an input string (1.0)
function get_feed_parameters($input,$para) {
    $start=strpos(strtolower($input),$para."=");
    $content="";
    if ($start!==false) {
        $start=$start+strlen($para)+1;
        $end=strpos(strtolower($input),"&",$start);
        if ($end!==false) {$end=$end-1;} else {$end=strlen($input);}
        $content=substr($input,$start,$end-$start+1);
    }
    return $content;
}
// Function to report an error (1.2)
function report_feed_error($errorin,$plugin_name) {
    echo "<p style=\"color: #f00; font-weight: bold;\">".$plugin_name.": ".__($errorin)."</p>\n";
    return true;
}