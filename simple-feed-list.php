<?php
/*
Plugin Name: Simple Feed List
Plugin URI: http://www.artiss.co.uk/simple-feed-list
Description: Displays a sidebar list taken from an RSS feed
Version: 1.4
Author: David Artiss
Author URI: http://www.artiss.co.uk
*/

function simple_feed_list($feed_url,$list_limit,$desc_flag) {

    $check_failure=0;

    // If no list limit has been specified, set it to 5. Then validate the limit (must be between 1 and 20)

    if ($list_limit=="") {$list_limit=20;}
    if (($list_limit<1)or($list_limit>20)) {
            echo "<li style=\"color: #f00; font-weight: bold;\">Simple Feed List Plugin : Invalid list limit. It must be between 1 and 20.</li>\n";
            $check_failure=1;
    }

    // If a description flag is specified, ensure it is Yes or No

    if (is_numeric($desc_flag)) {
        $desc_len=$desc_flag;
        $desc_flag="YES";
    } else {
        $desc_len=0;
        $desc_flag=strtoupper($desc_flag);
        if (($desc_flag!="")&&($desc_flag!="YES")&&($desc_flag!="NO")) {
                echo "<li style=\"color: #f00; font-weight: bold;\">Simple Feed List Plugin : Invalid description flag. It must be blank, No, Yes or a length.</li>\n";
                $check_failure=1;
        }
    }

    if ($check_failure==0) {

        // Fetch in the contents of the XML file

        $handle = fopen($feed_url,"rb");
        $array = '';
        while (!feof($handle)) {
            $array .= fread($handle,8192);
        }
        fclose($handle);

        // Check that the file is in RSS format

        if ((strpos($array,"<rss")===false)&&($check_failure==0)) {
            echo "<li style=\"color: #f00; font-weight: bold;\">Simple Feed List Plugin : The supplied file does not seem to be in the RSS format.</li>\n";
            $check_failure=1;
        }
    }

    if ($check_failure==0) {

        // First, get the link to the list

        $link_start=strpos($array,"<link>");
        $link_end=strpos($array,"</link>");
        $link_length=$link_end+7-$link_start;
        $list_link=strip_cdata(substr($array, $link_start+6, $link_length-13));

        // Read through the file and extract the appropriate sections

        $i=0;
        while ($i<$list_limit) {
            $item_start=strpos($array,"<item>");
            if ($item_start===false) {
                $i=$list_limit; 
            } else {
                $item_strip="";
                $title="";
                $link="";
                $desc="";

                $item_end=strpos($array,"</item>");
                $item_length=$item_end+7-$item_start;
                $item_strip=substr($array,$item_start,$item_length);

                $title_start=strpos($item_strip,"<title>");
                $title_end=strpos($item_strip,"</title>");
                $title_length=$title_end+8-$title_start;
                $title=strip_cdata(substr($item_strip, $title_start+7, $title_length-15));

                $link_start=strpos($item_strip,"<link>");
                $link_end=strpos($item_strip,"</link>");
                $link_length=$link_end+7-$link_start;
                $link=strip_cdata(substr($item_strip, $link_start+6, $link_length-13));

                $desc_start=strpos($item_strip,"<description>");
                $desc_end=strpos($item_strip,"</description>");
                $desc_length=$desc_end+14-$desc_start;
                $desc=strip_cdata(substr($item_strip, $desc_start+13, $desc_length-27));

                if (($desc_len>0)&&(strlen($desc)>$desc_len)) {$desc=substr($desc,0,$desc_len)."...";}

                // Write out the appropriate information if there are items to purchase

                if ($list_limit!=1) {echo "<li>";}
                if ($link!="") {echo "<a href=\"".$link."\" target=\"_blank\">";}
                echo str_replace("&amp;","&",$title);
                if ($link!="") {echo "</a>";}
                if ($desc_flag=="YES") {echo "<br/>".strip_tags(htmlspecialchars_decode($desc));}
                if ($list_limit!=1) {echo "</li>";}
                echo "\n";
                $i++;

                // Remove the current software record from the array

                $array=substr($array,$item_end+7);
            }
        }

        if ($list_link!="") {echo "<li><a href=\"".$list_link."\" target=\"_blank\">More...</a></li>\n";}

    }
}

function strip_cdata($strip_data) {

    if (strpos($strip_data,"<![CDATA[")!==false) {
        $cdata_start=strpos($strip_data,"<![CDATA[");
        $cdata_end=strpos($strip_data,"]]>");
        $cdata_length=$cdata_end+3-$cdata_start;
        $strip_data=substr($strip_data, $cdata_start+9, $cdata_length-12);
    }
    return $strip_data;
}
?>