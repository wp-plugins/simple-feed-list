<?php
/*
Plugin Name: Simple Feed List
Plugin URI: http://www.artiss.co.uk/simple-feed-list
Description: Displays an RSS feed as a list
Version: 2.2.2
Author: David Artiss
Author URI: http://www.artiss.co.uk
*/
define('simple_feed_list_version','2.2.2');
add_shortcode('feedlist','simple_feed_list_sc');

// Shortcode to return a feed list
function simple_feed_list_sc($paras="",$content="") {
    extract(shortcode_atts(array('listurl'=>'','limit'=>'','desc'=>'','more'=>'','target'=>'','nofollow'=>'','title'=>''),$paras));
    if ($limit!=1) {
        return "<ul>\n".get_feed_code($listurl,$limit,$desc,$more,$target,$nofollow,$title)."</ul>\n";
    } else {
        return get_feed_code($listurl,$limit,$desc,$more,$target,$nofollow,$title);
    }
}

// Function to return a feed list
function simple_feed_list($feed_url,$add_paras,$desc_flag="") {
    // Get input parameters
    $list_limit=strtolower(get_feed_parameters($add_paras,"limit"));
    if ($list_limit=="") {
        $list_limit=strtolower($add_paras);
    } else {
        $desc_flag=strtolower(get_feed_parameters($add_paras,"desc"));
        $more=strtolower(get_feed_parameters($add_paras,"more"));
        $target=strtolower(get_feed_parameters($add_paras,"target"));
        $nofollow=strtolower(get_feed_parameters($add_paras,"nofollow"));
        $title=strtolower(get_feed_parameters($add_paras,"title"));
    }
    // Call the function to generate the feed code
    echo get_feed_code($feed_url,$list_limit,$desc_flag,$more,$target,$nofollow,$title);
    return;
}

// Produce code for feed list
function get_feed_code($feed_url,$list_limit="20",$desc_flag="",$more="",$target="_blank",$nofollow="",$title_len="") {
    $code="<!-- Simple Feed List v".simple_feed_list_version." | http://www.artiss.co.uk/simple-feed-list -->\n";
    // Set up default values and validate the list limit
    $check_failure=false;
    $feed_url=str_replace('&amp;','&',$feed_url);
    if ($target=="") {$target="_blank";}
    if ($nofollow=="yes") {$nofollow=" rel=\"nofollow\"";} else {$nofollow="";}
    if (($list_limit<1)or($list_limit>20)) {$check_failure=1; $code.=report_feed_error("Invalid list limit. It must be between 1 and 20.","Simple Feed List");}

    // If a description flag is specified, ensure it is Yes or No
    if (is_numeric($desc_flag)) {
        $desc_len=$desc_flag;
        $desc_flag="yes";
    } else {
        $desc_len=0;
        if (($desc_flag!="")&&($desc_flag!="yes")&&($desc_flag!="no")) {$check_failure=1; $code.=report_feed_error("Invalid description flag. It must be blank, No, Yes or a length.","Simple Feed List");}
    }

    if ($check_failure!==true) {
        // Fetch in the contents of the RSS file using WordPress' in-built fetch_rss function
        @include_once(ABSPATH.WPINC.'/rss.php');
        @$array=fetch_rss($feed_url);
        // If no feed returned, write out an error
        if ($array=="") {
            $check_failure=1;
            $code.=report_feed_error("The supplied feed could not be fetched and/or parsed.","Simple Feed List");
        } else {
            // If a feed is returned, slice up the results into an array
            $items=array_slice($array->items,0,$list_limit);

            // First, get the link to the list
            $list_link=$array->channel['link'];

            // Process each item
            foreach ($items as $item) {

                // Extract out the required feed details
                $title=$item[title];
                $link=$item[link];
                $desc=$item[description];
                if (($title_len>0)&&(strlen($title)>$title_len)) {$title=substr($title,0,strrpos(substr($title,0,$title_len)," "))."&#8230;";}
                if (($desc_len>0)&&(strlen($desc)>$desc_len)) {$desc=substr($desc,0,strrpos(substr($desc,0,$desc_len)," "))." [&#8230;]";}

                // Write out the appropriate information for each feed item
                if ($list_limit!=1) {$code.="<li>";}
                if ($link!="") {$code.="<a href=\"".$link."\" target=\"".$target."\"".$nofollow.">";}
                $code.=str_replace("&amp;","&",$title);
                if ($link!="") {$code.="</a>";}
                if ($desc_flag=="yes") {$code.="<br/>".strip_tags(htmlspecialchars_decode($desc));}
                if ($list_limit!=1) {$code.="</li>";}
                $code.="\n";
            }
            if (($list_link!="")&&($more!="no")&&($list_limit!=1)) {$code.="<li><a href=\"".$list_link."\" target=\"".$target."\"".$nofollow.">More...</a></li>\n";}
        }
    }
    $code.="<!-- End of Simple Feed List -->\n";
    return $code;
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

// Function to report an error (1.2) (MOD)
function report_feed_error($errorin,$plugin_name) {
    return "<p style=\"color: #f00; font-weight: bold;\">".$plugin_name.": ".__($errorin)."</p>\n";
}