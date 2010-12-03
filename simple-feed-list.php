<?php
/*
Plugin Name: Simple Feed List
Plugin URI: http://www.artiss.co.uk/simple-feed-list
Description: Displays an RSS feed as a list
Version: 2.3.1
Author: David Artiss
Author URI: http://www.artiss.co.uk
*/
define('simple_feed_list_version','2.3.1');
add_shortcode('feedlist','simple_feed_list_sc');

// Shortcode to return a feed list
function simple_feed_list_sc($paras="",$content="") {
    extract(shortcode_atts(array('listurl'=>'','limit'=>'','desc'=>'','more'=>'','target'=>'','nofollow'=>'','title'=>'','cache'=>''),$paras));
    return get_feed_code($listurl,$limit,$desc,$more,$target,$nofollow,$title,$cache,'sc');
}

// Function to return a feed list
function simple_feed_list($feed_url,$add_paras,$desc_flag="") {
    // Get input parameters
    $list_limit=get_feed_parameters($add_paras,"limit");
    if ($list_limit=="") {
        $list_limit=$add_paras;
    } else {
        $desc_flag=get_feed_parameters($add_paras,"desc");
        $more=get_feed_parameters($add_paras,"more");
        $target=get_feed_parameters($add_paras,"target");
        $nofollow=get_feed_parameters($add_paras,"nofollow");
        $title=get_feed_parameters($add_paras,"title");
        $cache=get_feed_parameters($add_paras,"cache");
    }
    // Call the function to generate the feed code
    echo get_feed_code($feed_url,$list_limit,$desc_flag,$more,$target,$nofollow,$title,$cache);
    return;
}

// Produce code for feed list
function get_feed_code($feed_url,$list_limit="20",$desc_flag="",$more="",$target="_blank",$nofollow="",$title_len="",$cache="1",$source="fun") {  
    $code="<!-- Simple Feed List v".simple_feed_list_version." | http://www.artiss.co.uk/simple-feed-list -->\n";
    $code.="<!-- Requested file ".$feed_url." via ";
    if ($source="sc") {$code.="shortcode";} else {$code.="PHP function";}
    $code.=" -->\n";
    // Ensure all passed parameters (except URL) are lower case
    $list_limit=strtolower($list_limit);
    $desc_flag=strtolower($desc_flag);
    $more=strtolower($more);
    $target=strtolower($target);
    $nofollow=strtolower($nofollow);
    $title_len=strtolower($title_len);
    $cache=strtolower($cache);

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
    
    // Set MagpieRSS Cache
    if ($cache!="no") {
        $cache_time=$cache*3600;
        define('MAGPIE_CACHE_AGE',$cache_time);
        define('MAGPIE_CACHE_ON',true);
    } else {
        define('MAGPIE_CACHE_ON',false);
    }

    if ($check_failure!==true) {
        // Fetch in the contents of the RSS file using WordPress' in-built fetch_rss function
        @include_once(ABSPATH.WPINC.'/rss.php');
        @$array=fetch_rss($feed_url);

        //echo print_r($array);

        // If no feed returned, write out an error
        if ($array=="") {
            $check_failure=1;
            $code.=report_feed_error("The supplied feed could not be fetched and/or parsed.","Simple Feed List");
        } else {
            // If a feed is returned, slice up the results into an array
            $items=array_slice($array->items,0,$list_limit);

            // First, get the link to the list
            $list_link=$array->channel['link'];
            if ($list_link=="") {$list_link=$array->channel['link_self'];}
            $link_direct=$array->channel['link_'];

            $code.="<!-- Reading ".$array->feed_type;
            if ($array->feed_version!="") {$code.=" v".$array->feed_version;}
            $code.=" format feed -->\n";

            if (($list_limit!=1)&&($source="sc")) {$code.="<ul>\n";}

            // Process each item
            foreach ($items as $item) {

                // Extract out the required feed details
                $title=$item[title];
                if (($title_len>0)&&(strlen($title)>$title_len)) {$title=substr($title,0,strrpos(substr($title,0,$title_len)," "))."&#8230;";}
                $link=$item[link];
                if (substr($link,0,1)=="/") {
                    if ($link_direct!="") {
                        if (substr($link_direct,-1,1)=="/") {$link=substr($link,1);}
                        $link=$link_direct.$link;
                    } else {
                        $link="";
                    }
                }
                if ($desc_flag=="yes") {
                    $desc=$item[description];
                    if (($desc_len>0)&&(strlen($desc)>$desc_len)) {$desc=substr($desc,0,strrpos(substr($desc,0,$desc_len)," "))." [&#8230;]";}
                }

                // Write out the appropriate information for each feed item
                if ($list_limit!=1) {$code.="<li>";}
                if ($link!="") {$code.="<a href=\"".$link."\" target=\"".$target."\"".$nofollow.">";}
                $code.=str_replace("&amp;","&",$title);
                if ($link!="") {$code.="</a>";}
                if ($desc_flag=="yes") {$code.="<br/>".strip_tags(htmlspecialchars_decode($desc));}
                if ($list_limit!=1) {$code.="</li>";}
                $code.="\n";
            }

            // Display "More" information
            if (($list_link!="")&&($more!="no")&&($list_limit!=1)) {$code.="<li><a href=\"".$list_link."\" target=\"".$target."\"".$nofollow.">".__('More')."...</a></li>\n";}
            if (($list_limit!=1)&&($source="sc")) {$code.="</ul>\n";}
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