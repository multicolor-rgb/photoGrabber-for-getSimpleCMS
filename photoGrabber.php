<?php


 
# get correct id for plugin
$thisfile=basename(__FILE__, ".php");
 
# register plugin
register_plugin(
    $thisfile, //Plugin id
    'PhotoDownloader',  //Plugin name
    '1.0',      //Plugin version
    'Multicolor',  //Plugin author
    'http://paypal.me/multicol0r', //author website
    'Get free commercial use photos direct to cms', //Plugin description
    'pages', //page type - on which admin tab to display
    'photoGrabber'  //main function (administration)
);

# add a link in the admin tab 'theme'
add_action('pages-sidebar','createSideMenu',array($thisfile,'PhotoGrabber 📷'));
 
# functions

function photoGrabber(){
include GSPLUGINPATH.'photoGrabber/browser.inc.php';
echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="box-sizing:border-box;display:grid; width:100%;grid-template-columns:1fr auto; border-radius:5px;padding:10px;background:#fafafa;border:solid 1px #ddd;margin-top:20px;">
            <p style="margin:0;padding:0;"> Do you like using my plugin? Buy me a ☕ </p>
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="KFZ9MCBUKB7GL">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" border="0">
            <img alt="" src="https://www.paypal.com/en_PL/i/scr/pixel.gif" width="1" height="1" border="0" sjeufj9v4="">
        </form>';

};


add_action('edit-extras','getOnGrabber');


function getOnGrabber(){
include(GSPLUGINPATH.'photoGrabber/formEdit.inc.php');
} 


;?>