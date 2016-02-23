<?php
include_once("inc/my_connect.php");
include_once("inc/control.php");
include_once("inc/constants.php");
include_once("inc/functions.php");

$help_page = "video";

if(
$_GET['act'] != "view" && 
$_GET['act'] != "default"
) $_GET['act'] = "default";

$body = "";

if($_GET['act'] == "view")
{
$body .= '
<script language="javascript" type="text/javascript">
<!--//

// RICH MEDIA PROJECT : JAVASCRIPT PHP FLV PLAYER & MEDIA LIST v2.0.0

// DETECT FLASH PLAYER VERSION FOR FULLSCREEN EXPERIENCE (from Flash Player v9.0.28) 
detectFlashPlayer=true;
// fullScreenFunction : if false, fullscreen mode won\'t be available
fullScreenFunction=true;


// PHP SCRIPT PATH 
// Path of the PHP provider script; path relative to the "RMP_PlayerMedia.swf" file or absolute path (movies should be located in the same folder)
phpPath="/vm/inc/flvprovider.php";
// movieName without extension; see "bandwidth detection" section if you want to use movies with different bitrates.
// If you are using the component in list mode, movieName should be set to ""
movieName="'.$_GET['movie'].'";


// BEHAVIOURS
bufferTime=1;
volume=70;
// If autostart=false, the movie will be paused at start.
autoStart=false;
// endBehaviour : behaviour when the movie ends.
// endBehaviour="Stop" | endBehaviour="Loop" | endBehaviour="List_NextMedia"
endBehaviour="List_NextMedia";
// endFullScreenBackToNormal : if true, return from full screen to normal mode when the movie ends
endFullScreenBackToNormal=true;
// endJavascriptFunction : your custom javascript function that will be called at the end of the movie
endJavascriptFunction="";
// CloseConnect: If true, the Nestream connection will be closed when stop button is pressed.
stopCloseConnect=true;


// BANDWIDTH DETECTION
// Set differentVersions to true to enable bandwidth detection
differentVersions=false;
// Define the different bitrates values in kbps (audio+video bitrate); 
// When the detected bandwidth equals of exceeds these values, the associated movie will be selected
version_hi_value=800;
version_medium_value=400;
version_low_value=200;
// If differentVersions=false: upload a single version of a movie to your server : [movieName].flv
// if differentVersions=true: upload multiple versions (ex: low, med, hi) of a movie to your server :
// [movieName][version_hi_extension].flv
// [movieName][version_medium_extension].flv
// [movieName][version_low_extension].flv
// Set the movie name extension that is associated with each different bitrate version
// Use format of "_text" for each version name;  this value will be appended to the movieName
version_hi_extension="_800";
version_medium_extension="_400";
version_low_extension="_200";


// PICTURES
// Set pictures parameters to true to display a picture at start (if autostart=false) and when the movie is stopped.
// The picture has to be a jpeg file and must be named exactly the same as the movieName parameter.
// If movieName="myVideo", the player will try to load myVideo.jpg file.
pictures=false;
// The pictures must be located in the pictures folder and path listed here.
picturesFolder="/myFlash/pictures";
//Set a background color for the picture
picturesBackgroundColor="#000000";
picturesKeepAspectRatio=false;
// Set "picturesKeepAspectRatioFullScreen" to true to prevent distortion during full screen mode
picturesKeepAspectRatioFullScreen=true;


// CLICK ON SCREEN
// Set  following to true to use hand cursor to indicate the screen is clickable
clickOnScreen_useHandCursor=true;
// set singleClickPlayPause to true to pause or resume the playback with a single click anywhere on the screen 
singleClickPlayPause=true;
// Set to true to show a play button on screen, 
screenPlayButton=true;
// Set the alpha transparency (low value is most transparent)
screenPlayButtonAlpha=80;
// set doubleClickListener to true to go full screen with a double click
doubleClickListener=true;


// VIDEO QUALITY
// Smoothing and bestQuality options 
// Note: enabling these options will increase CPU usage.  Users with low-powered computers
// or that have multiple applications open may have viewing problems.  Use these with caution.
bestQuality=false;
smoothing=true;
smoothingFullScreen=false;
deblocking=0;


// PLAYER\'S PROPERTIES
playerWidth=630;
// playerHeight includes the size of the control bar (15 pixels) when in NORMAL MODE
playerHeight=400;
// autoSize="Movie" or autoSize="Off". Set autoSize to "Movie" to make player fit the 
// movie and to keep the aspect ratio.
autoSize="Off";
// autoSizeFullScreen="Movie" maintains the movie\'s aspect ratio during full screen mode;
// autoSizeFullScreen="Off" allows the movie to distort and fill the screen in full screen mode;
autoSizeFullScreen="Movie";

// NORMAL MODE
// Player parameters: colors and positions
// playerHeight includes the size of the control bar (15 pixels)
playerColor="#222222";
barColor="#FFFFFF";
playbarColor="#E2F9D9";
screenBorder=true;
screenBorderColor="#121212";
buttonsColor="#FFFFFF";
timeColor="#FFFFFF";
backgroundColor="#000000";
backgroundAlpha=100;
//controlsY : space between the bottom of the screen and the control bar.
controlsY=10;

// FULL SCREEN MODE
// Enables you to change player controls colors, dimensions, and positions during full screen mode
// ControlsYFullScreen, y position from the bottom of the screen
controlsYFullScreen=15;
// Set controlsWidthFullScreen=0 to  fit with the width of the screen
controlsWidthFullScreen=500;
playerColorFullScreen="#222222";
barColorFullScreen="#FFFFFF";
playbarColorFullScreen="#E2F9D9";
buttonsColorFullScreen="#FFFFFF";
timeColorFullScreen="#FFFFFF";
backgroundColorFullScreen="#000000";
backgroundAlphaFullScreen=100;

// CONTROLS
// controls="Hide" | controls="Autohide" | controls="Show"
controls="Show";
controlsFullScreen="Autohide";
// Enable buttons: true  | false
listButtons=true
stopButton=true;
stopButtonFirst=true;
rewindFastForwardButtons=true;
// rewind and fast forward intervals (rw_ff_interval) in seconds
rw_ff_interval=4;
// Enable volume slider: true  | false
volumeSlider=true;
// Enable timecode display: true  | false
showTimecode=true;
controlsBarPress=true;
// Set time display format: timeFormat="mm:ss" | timeFormat="hh:mm:ss" | timeFormat="mm:ss|duration" | timeFormat="hh:mm:ss|duration"
timeFormat="mm:ss|duration";


// BUFFER MESSAGE
// Set buffer message parameters
bufferMessage=true;
bufferText="Загрузка...";
bufferTextColor="#FFFFFF";
bufferTextBackgroundColor="#993333";
bufferTextBackgroundAlpha=40;
// bufferTextPosition="Upper Right" | bufferTextPosition="Upper Left" | bufferTextPosition="Lower Right" | bufferTextPosition="Lower Left" | bufferTextPosition="Center"
bufferTextPosition="Upper Right";


// LOGO
// Enables a logo to be displayed on the screen during playback: true | false
// logo file has to be a jpg or a swf.
logo=false;
// Set path to the logo file
logoPath="images/logo_video.jpg";
// logoPosition="Upper Right" | logoPosition="Upper Left" | logoPosition="Lower Right" | logoPosition="Lower Left"
logoPosition="Upper Left";
logoAlpha=70;
// Set logo position in pixels from edge of player
logo_x=10;
logo_y=10;


// COMMERCIAL
// If differentVersions=false then only upload a single version of a commercial movie to your server: [commercialMovieName].flv
// if differentVersions=true, you will need to upload multiple versions (ex: low, med, hi) of a commercial movie to your server:
// Set the commercial movie name extension that is associated with each different bitrate version
// Use the same bitrate version extensions that were defined in the BANDWIDTH DETECTION section above
// [commercialMovieName][version_hi_extension].flv
// [commercialMovieName][version_medium_extension].flv
// [commercialMovieName][version_low_extension].flv
// Set the commercialMovieName without extension:
commercialMovieName="";
// Javascript functions to call when the commercial starts and when the user clicks on the screen during a commercial.
// Flash will call getURL("javascript:"+nameOfYourFunction);
// For example you can call an alert Function, commercialFunctionStart="alert(\'start commercial\');"
commercialFunctionStart="";
commercialFunctionClickScreen="";
// Select to show either a commercial text message or a commercial text message plus the "coming next" movie title
// commercialMessage="CommercialText" | commercialMessage="CommercialText and MovieTitle"
commercialMessage="CommercialText";
// Set movieTitleForComingNext property if commercialMessage="CommercialText and MovieTitle"
movieTitleForComingNext="";
// Define the commercial text message and its properties:
commercialText="Your movie will start after this commercial...";
commercialTextColor="#FFFFFF";
commercialTextBold=true;


// SUBTITLES
// Subtitles are defined in an XML file; set the XML path here:
subtitleXML="";
// Sub Titles are visible at start: subVisible= true | false  
subVisible=false;
// Sub titles can be controlled with a button; subButton= true | false:
subButton=false;
subButtonOffColor="#FFFFFF";
subColor="#FFFFFF";
subBackground=true;
subBackgroundColor="#000000";
subBackgroundAlpha=90;
subFontSize=16;
autohideSubBackground=true;
// Set sub title text alignment: subAlign="left" | subAlign="center" | subAlign="right"
subAlign="center";

// SUBTITLES IN FULLSCREEN MODE
// Enables sub titles to be styled differently when in full screen mode
subColorFullScreen="#FFFFFF";
subBackgroundFullScreen=false;
subBackgroundColorFullScreen="#000000";
subBackgroundAlphaFullScreen=90;
subFontSizeFullScreen=30;
autohideSubBackgroundFullScreen=true;
// subAlignFullScreen="left" | subAlignFullScreen="center" | subAlignFullScreen="right"
subAlignFullScreen="center";
blankLineFullScreen=0;



// MEDIA LIST PROPERTIES
// mediaXML : Absolute or relative path to Media List XML file. Set mediaXML to "" if you don\'t want to use media list mode.
mediaXML="";
// startBehaviour="Wait for click" | startBehaviour="Play first media" | startBehaviour="Pause on first media"
startBehaviour="Pause on first media";
displayListAtStart=false;
displayListInFullScreenMode=true;
//displayListWhenEnteringFullScreenMode can be defined only if displayListInFullScreenMode is set to true.
displayListWhenEnteringFullScreenMode=true;
scrollListener=true;
scrollSize_auto=true;
scrollSize=50;
scrollAirSkin=false;

// PROPERTIES IN NORMAL MODE
pictureWidth=60;
pictureHeight=45;
listWidth=200;
// listSpace : space between movie\'s border and player\'s border when playing in list mode.
listSpace=5;
cellHeight=60;
showDescription=true;
showPicture=true;
textFontSize=9;
titleFontSize=10;
titleBold=true;
blankLineAfterTitle=false;
textColor="#FFFFFF";
titleColor="#FFFFFF";
selectColor="#121212";
selectAlpha=100;
focusColor="#121212";
focusAlpha=50;
listButtonsColor="#FFFFFF";
listBarColor="#222222";
skinColor="#121212";
listBackgroundColor="#222222";
listBackgroundAlpha=100;
border=true;
borderColor="#222222";
// spaceBP : space Before Picture | spaceBT : space Before Text | spaceAT : space After Text | spaceTT : space Top Text
spaceBP=5;
spaceBT=5;
spaceAT=20;
spaceTT=10;
// PROPERTIES IN FULL SCREEN MODE
pictureWidthFullScreen=80;
pictureHeightFullScreen=60;
listWidthFullScreen=220;
// listSpace : space between movie\'s border and player\'s border when playing in list mode.
listSpaceFullScreen=5;
cellHeightFullScreen=80;
showDescriptionFullScreen=true;
showPictureFullScreen=true;
textFontSizeFullScreen=10;
titleFontSizeFullScreen=11;
titleBoldFullScreen=true;
blankLineAfterTitleFullScreen=false;
textColorFullScreen="#FFFFFF";
titleColorFullScreen="#FFFFFF";
selectColorFullScreen="#222222";
selectAlphaFullScreen=80;
focusColorFullScreen="#222222";
focusAlphaFullScreen=40;
listButtonsColorFullScreen="#FFFFFF";
listBarColorFullScreen="#222222";
skinColorFullScreen="#121212";
listBackgroundColorFullScreen="#000000";
listBackgroundAlphaFullScreen=100;
borderFullScreen=true;
borderColorFullScreen="#111111";
// spaceBP : space Before Picture | spaceBT : space Before Text | spaceAT : space After Text | spaceTT : space Top Text
spaceBPFullScreen=10;
spaceBTFullScreen=10;
spaceATFullScreen=20;
spaceTTFullScreen=10;


// POP UP
// Set display properties for Detect Flash Player Pop Up
// Detect Flash Player Text=[detectPopUpText1]+[versionNumber]+[detectPopUpText2]
// You can use HTML tags to style your text
detectPopUpTitle="Adobe Flash Player Version";
detectPopUpText1="You have Flash Player<br>version ";
detectPopUpText2=" installed.<br><br>Enjoy a fullscreen<br>experience with the new Flash Player.";
// Button label to get update for Flash Player
detectPopUpButton1="Get Flash";
// Button label to bypass Flash Player update feature
detectPopUpButton2="Continue";

// Detect Bandwidth Pop Up
// Enable Bandwidth Detect Pop Up window: true | false
displayBandwidthDetectPopUp=true;
// Set display text for Bandwidth detect Pop Up feature
// Detect Bandwidth Result Text=[bandwidthDetectPopUpText2]+[bandwidth]+" kbps"
bandwidthDetectPopUpTitle="Rich Media Project Speed Detector";
bandwidthDetectPopUpText1="Detecting Bandwidth<br>Please wait...";
bandwidthDetectPopUpText2="Your Bandwidth :<br>"

// Loading Movie Pop Up	
// Enable Movie Loading Pop Up window text: true | false
displayLoadingMoviePopUp=true;
loadingMoviePopUpTitle="Loading...";
loadingMoviePopUpText="Loading Movie<br>Please wait..."

// Select Bandwidth Pop Up
// Enables viewer to select bandwidth manually via this popup window
// Select Bandwidth Text=[bandwidthSelectPopUpText1]+[bandwidthTextForXXXBandwidth]+[bandwidthSelectPopUpText2]+[version_XXX_value]+[bandwidthSelectPopUpText3]
bandwidthSelectPopUpTitle="Bandwidth Selector";
bandwidthSelectPopUpButton1="Low";
bandwidthSelectPopUpButton2="Medium";
bandwidthSelectPopUpButton3="Hi";
bandwidthTextForLowBandwidth="Low";
bandwidthTextForMediumBandwidth="Medium";
bandwidthTextForHiBandwidth="Hi";
bandwidthSelectPopUpText1="";
bandwidthSelectPopUpText2=" bandwidth is selected :<br>";
bandwidthSelectPopUpText3=" kbps movie.<br><br>Please select the desired<br>bandwidth :";

// Info Pop Up
// This pop up is displayed if no movie is loaded at start (movieName="")
displayInfoPopUp=true;
infoPopUpTitle="Select movie";
infoPopUpText="Please select a movie in the gallery"


// SWF PROPERTIES
// swfPath : path of the swf that contains the player (without swf extension)
swfPath="swf/RMP_PlayerMedia";
// swfID : name of the Flash Object
swfID="RMP_PlayerMedia";
// speedClipPath : path of the swf that contains the speed detector clip (without swf extension)
speedClipPath="swf/speedClip";
swfWidth=playerWidth;
swfHeight=playerHeight+controlsY+1;
swfBackgroundColor="#FFFFFF";


////////////////////////////////////////////////
// OBJECT BUILDER : DO NOT MODIFY THIS SCRIPT //
////////////////////////////////////////////////

var isIE  = (navigator.appVersion.indexOf("MSIE") != -1) ? true : false;
var isWin = (navigator.appVersion.toLowerCase().indexOf("win") != -1) ? true : false;
var isOpera = (navigator.userAgent.indexOf("Opera") != -1) ? true : false;



function AC_AddExtension(src, ext)
{
  if (src.indexOf(\'?\') != -1)
    return src.replace(/\?/, ext+\'?\'); 
  else
    return src + ext;
}

function AC_Generateobj(objAttrs, params, embedAttrs) 
{ 
    var str = \'\';
    if (isIE && isWin && !isOpera)
    {
  		str += \'<object \';
  		for (var i in objAttrs)
  			str += i + \'="\' + objAttrs[i] + \'" \';
  		for (var i in params)
  			str += \'><param name="\' + i + \'" value="\' + params[i] + \'" /> \';
  		str += \'></object>\';
    } else {
  		str += \'<embed \';
  		for (var i in embedAttrs)
  			str += i + \'="\' + embedAttrs[i] + \'" \';
			str +=\'swLiveConnect=true \';
  		str += \'> </embed>\';
    }

    document.write(str);
}

function AC_FL_RunContent(){
  var ret = 
    AC_GetArgs
    (  arguments, ".swf", "movie", "clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
     , "application/x-shockwave-flash"
    );
  AC_Generateobj(ret.objAttrs, ret.params, ret.embedAttrs);
}

function AC_GetArgs(args, ext, srcParamName, classid, mimeType){
  var ret = new Object();
  ret.embedAttrs = new Object();
  ret.params = new Object();
  ret.objAttrs = new Object();
  for (var i=0; i < args.length; i=i+2){
    var currArg = args[i].toLowerCase();    

    switch (currArg){	
      case "classid":
        break;
      case "pluginspage":
        ret.embedAttrs[args[i]] = args[i+1];
        break;
      case "src":
      case "movie":	
        args[i+1] = AC_AddExtension(args[i+1], ext);
        ret.embedAttrs["src"] = args[i+1];
        ret.params[srcParamName] = args[i+1];
        break;
      case "onafterupdate":
      case "onbeforeupdate":
      case "onblur":
      case "oncellchange":
      case "onclick":
      case "ondblClick":
      case "ondrag":
      case "ondragend":
      case "ondragenter":
      case "ondragleave":
      case "ondragover":
      case "ondrop":
      case "onfinish":
      case "onfocus":
      case "onhelp":
      case "onmousedown":
      case "onmouseup":
      case "onmouseover":
      case "onmousemove":
      case "onmouseout":
      case "onkeypress":
      case "onkeydown":
      case "onkeyup":
      case "onload":
      case "onlosecapture":
      case "onpropertychange":
      case "onreadystatechange":
      case "onrowsdelete":
      case "onrowenter":
      case "onrowexit":
      case "onrowsinserted":
      case "onstart":
      case "onscroll":
      case "onbeforeeditfocus":
      case "onactivate":
      case "onbeforedeactivate":
      case "ondeactivate":
      case "type":
      case "codebase":
      case "id":
        ret.objAttrs[args[i]] = args[i+1];
        break;
      case "width":
      case "height":
      case "align":
      case "vspace": 
      case "hspace":
      case "class":
      case "title":
      case "accesskey":
      case "name":
      case "tabindex":
        ret.embedAttrs[args[i]] = ret.objAttrs[args[i]] = args[i+1];
        break;
      default:
        ret.embedAttrs[args[i]] = ret.params[args[i]] = args[i+1];
    }
  }
  ret.objAttrs["classid"] = classid;
  if (mimeType) ret.embedAttrs["type"] = mimeType;
  return ret;
}


function createObject() {

AC_FL_RunContent(
			"src", swfPath,
			"FlashVars", "version_hi_value="+version_hi_value+\'&version_medium_value=\'+version_medium_value+\'&version_low_value=\'+version_low_value+\'&version_hi_extension=\'+version_hi_extension+\'&version_medium_extension=\'+version_medium_extension+\'&version_low_extension=\'+version_low_extension+\'&detectFlashPlayer=\'+detectFlashPlayer+\'&phpPath=\'+phpPath+\'&movieName=\'+movieName+\'&differentVersions=\'+differentVersions+\'&subtitleXML=\'+subtitleXML+\'&autoSize=\'+autoSize+\'&playerWidth=\'+playerWidth+\'&playerHeight=\'+playerHeight+\'&bufferTime=\'+bufferTime+\'&autoStart=\'+autoStart+\'&endBehaviour=\'+endBehaviour+\'&controls=\'+controls+\'&screenBorder=\'+screenBorder+\'&controlsY=\'+controlsY+\'&stopButton=\'+stopButton+\'&rewindFastForwardButtons=\'+rewindFastForwardButtons+\'&subButton=\'+subButton+\'&volumeSlider=\'+volumeSlider+\'&playerColor=\'+playerColor+\'&playbarColor=\'+playbarColor+\'&timeColor=\'+timeColor+\'&barColor=\'+barColor+\'&buttonsColor=\'+buttonsColor+\'&screenBorderColor=\'+screenBorderColor+\'&subColor=\'+subColor+\'&subButtonOffColor=\'+subButtonOffColor+\'&subBackgroundColor=\'+subBackgroundColor+\'&subBackgroundAlpha=\'+subBackgroundAlpha+\'&backgroundColor=\'+backgroundColor+\'&backgroundAlpha=\'+backgroundAlpha+\'&subBackground=\'+subBackground+\'&subVisible=\'+subVisible+\'&subFontSize=\'+subFontSize+\'&autohideSubBackground=\'+autohideSubBackground+\'&volume=\'+volume+\'&commercialText=\'+commercialText+\'&commercialTextColor=\'+commercialTextColor+\'&commercialTextBold=\'+commercialTextBold+\'&bufferTextBackgroundColor=\'+bufferTextBackgroundColor+\'&bufferTextBackgroundAlpha=\'+bufferTextBackgroundAlpha+\'&bufferTextColor=\'+bufferTextColor+\'&bufferTextPosition=\'+bufferTextPosition+\'&bufferText=\'+bufferText+\'&timeFormat=\'+timeFormat+\'&logo=\'+logo+\'&logoPath=\'+logoPath+\'&logoPosition=\'+logoPosition+\'&logoAlpha=\'+logoAlpha+\'&logo_x=\'+logo_x+\'&logo_y=\'+logo_y+\'&controlsBarPress=\'+controlsBarPress+\'&bufferMessage=\'+bufferMessage+\'&showTimecode=\'+showTimecode+\'&subAlign=\'+subAlign+\'&rw_ff_interval=\'+rw_ff_interval+\'&stopButtonFirst=\'+stopButtonFirst+\'&detectPopUpTitle=\'+detectPopUpTitle+\'&detectPopUpText1=\'+detectPopUpText1+\'&detectPopUpText2=\'+detectPopUpText2+\'&detectPopUpButton1=\'+detectPopUpButton1+\'&detectPopUpButton2=\'+detectPopUpButton2+\'&bandwidthDetectPopUpTitle=\'+bandwidthDetectPopUpTitle+\'&bandwidthDetectPopUpText1=\'+bandwidthDetectPopUpText1+\'&bandwidthDetectPopUpText2=\'+bandwidthDetectPopUpText2+\'&bandwidthSelectPopUpTitle=\'+bandwidthSelectPopUpTitle+\'&bandwidthSelectPopUpButton1=\'+bandwidthSelectPopUpButton1+\'&bandwidthSelectPopUpButton2=\'+bandwidthSelectPopUpButton2+\'&bandwidthSelectPopUpButton3=\'+bandwidthSelectPopUpButton3+\'&bandwidthTextForLowBandwidth=\'+bandwidthTextForLowBandwidth+\'&bandwidthTextForMediumBandwidth=\'+bandwidthTextForMediumBandwidth+\'&bandwidthTextForHiBandwidth=\'+bandwidthTextForHiBandwidth+\'&bandwidthSelectPopUpText1=\'+bandwidthSelectPopUpText1+\'&bandwidthSelectPopUpText2=\'+bandwidthSelectPopUpText2+\'&bandwidthSelectPopUpText3=\'+bandwidthSelectPopUpText3+\'&playerColorFullScreen=\'+playerColorFullScreen+\'&barColorFullScreen=\'+barColorFullScreen+\'&playbarColorFullScreen=\'+playbarColorFullScreen+\'&buttonsColorFullScreen=\'+buttonsColorFullScreen+\'&timeColorFullScreen=\'+timeColorFullScreen+\'&backgroundColorFullScreen=\'+backgroundColorFullScreen+\'&backgroundAlphaFullScreen=\'+backgroundAlphaFullScreen+\'&blankLineFullScreen=\'+blankLineFullScreen+\'&controlsYFullScreen=\'+controlsYFullScreen+\'&subColorFullScreen=\'+subColorFullScreen+\'&subBackgroundFullScreen=\'+subBackgroundFullScreen+\'&subBackgroundColorFullScreen=\'+subBackgroundColorFullScreen+\'&subBackgroundAlphaFullScreen=\'+subBackgroundAlphaFullScreen+\'&subFontSizeFullScreen=\'+subFontSizeFullScreen+\'&autohideSubBackgroundFullScreen=\'+autohideSubBackgroundFullScreen+\'&subAlignFullScreen=\'+subAlignFullScreen+\'&speedClipPath=\'+speedClipPath+\'&fullScreenFunction=\'+fullScreenFunction+\'&endFullScreenBackToNormal=\'+endFullScreenBackToNormal+\'&displayBandwidthDetectPopUp=\'+displayBandwidthDetectPopUp+\'&commercialMovieName=\'+commercialMovieName+\'&commercialFunctionClickScreen=\'+commercialFunctionClickScreen+\'&commercialFunctionStart=\'+commercialFunctionStart+\'&loadingMoviePopUpTitle=\'+loadingMoviePopUpTitle+\'&loadingMoviePopUpText=\'+loadingMoviePopUpText+\'&displayLoadingMoviePopUp=\'+displayLoadingMoviePopUp+\'&endJavascriptFunction=\'+endJavascriptFunction+\'&stopCloseConnect=\'+stopCloseConnect+\'&infoPopUpText=\'+infoPopUpText+\'&infoPopUpTitle=\'+infoPopUpTitle+\'&displayInfoPopUp=\'+displayInfoPopUp+\'&autoSizeFullScreen=\'+autoSizeFullScreen+\'&pictures=\'+pictures+\'&picturesFolder=\'+picturesFolder+\'&picturesBackgroundColor=\'+picturesBackgroundColor+\'&picturesKeepAspectRatio=\'+picturesKeepAspectRatio+\'&picturesKeepAspectRatioFullScreen=\'+picturesKeepAspectRatioFullScreen+\'&clickOnScreen_useHandCursor=\'+clickOnScreen_useHandCursor+\'&singleClickPlayPause=\'+singleClickPlayPause+\'&screenPlayButton=\'+screenPlayButton+\'&screenPlayButtonAlpha=\'+screenPlayButtonAlpha+\'&screenPlayButtonAlpha=\'+screenPlayButtonAlpha+\'&doubleClickListener=\'+doubleClickListener+\'&bestQuality=\'+bestQuality+\'&smoothing=\'+smoothing+\'&smoothingFullScreen=\'+smoothingFullScreen+\'&deblocking=\'+deblocking+\'&controlsWidthFullScreen=\'+controlsWidthFullScreen+\'&movieTitleForComingNext=\'+movieTitleForComingNext+\'&controlsFullScreen=\'+controlsFullScreen+\'&commercialMessage=\'+commercialMessage+\'&mediaXML=\'+mediaXML+\'&displayListAtStart=\'+displayListAtStart+\'&displayListInFullScreenMode=\'+displayListInFullScreenMode+\'&scrollListener=\'+scrollListener+\'&scrollSize_auto=\'+scrollSize_auto+\'&scrollSize=\'+scrollSize+\'&pictureWidth=\'+pictureWidth+\'&pictureHeight=\'+pictureHeight+\'&listWidth=\'+listWidth+\'&listSpace=\'+listSpace+\'&cellHeight=\'+cellHeight+\'&showDescription=\'+showDescription+\'&showPicture=\'+showPicture+\'&textFontSize=\'+textFontSize+\'&titleFontSize=\'+titleFontSize+\'&titleBold=\'+titleBold+\'&blankLineAfterTitle=\'+blankLineAfterTitle+\'&textColor=\'+textColor+\'&titleColor=\'+titleColor+\'&selectColor=\'+selectColor+\'&selectAlpha=\'+selectAlpha+\'&focusColor=\'+focusColor+\'&focusAlpha=\'+focusAlpha+\'&listButtonsColor=\'+listButtonsColor+\'&listBarColor=\'+listBarColor+\'&skinColor=\'+skinColor+\'&listBackgroundColor=\'+listBackgroundColor+\'&listBackgroundAlpha=\'+listBackgroundAlpha+\'&borderColor=\'+borderColor+\'&spaceBP=\'+spaceBP+\'&spaceBT=\'+spaceBT+\'&spaceAT=\'+spaceAT+\'&spaceTT=\'+spaceTT+\'&listWidthFullScreen=\'+listWidthFullScreen+\'&listSpaceFullScreen=\'+listSpaceFullScreen+\'&cellHeightFullScreen=\'+cellHeightFullScreen+\'&showDescriptionFullScreen=\'+showDescriptionFullScreen+\'&showPictureFullScreen=\'+showPictureFullScreen+\'&textFontSizeFullScreen=\'+textFontSizeFullScreen+\'&titleFontSizeFullScreen=\'+titleFontSizeFullScreen+\'&titleBoldFullScreen=\'+titleBoldFullScreen+\'&blankLineAfterTitleFullScreen=\'+blankLineAfterTitleFullScreen+\'&textColorFullScreen=\'+textColorFullScreen+\'&titleColorFullScreen=\'+titleColorFullScreen+\'&selectColorFullScreen=\'+selectColorFullScreen+\'&selectAlphaFullScreen=\'+selectAlphaFullScreen+\'&focusColorFullScreen=\'+focusColorFullScreen+\'&focusAlphaFullScreen=\'+focusAlphaFullScreen+\'&listButtonsColorFullScreen=\'+listButtonsColorFullScreen+\'&listBarColorFullScreen=\'+listBarColorFullScreen+\'&skinColorFullScreen=\'+skinColorFullScreen+\'&listBackgroundColorFullScreen=\'+listBackgroundColorFullScreen+\'&listBackgroundAlphaFullScreen=\'+listBackgroundAlphaFullScreen+\'&borderColorFullScreen=\'+borderColorFullScreen+\'&spaceBPFullScreen=\'+spaceBPFullScreen+\'&spaceBTFullScreen=\'+spaceBTFullScreen+\'&spaceATFullScreen=\'+spaceATFullScreen+\'&spaceTTFullScreen=\'+spaceTTFullScreen+\'&listButtons=\'+listButtons+\'&border=\'+border+\'&borderFullScreen=\'+borderFullScreen+\'&pictureWidthFullScreen=\'+pictureWidthFullScreen+\'&pictureHeightFullScreen=\'+pictureHeightFullScreen+\'&displayListWhenEnteringFullScreenMode=\'+displayListWhenEnteringFullScreenMode+\'&scrollAirSkin=\'+scrollAirSkin+\'&startBehaviour=\'+startBehaviour,
			"menu","false",
			"width", swfWidth,
			"height", swfHeight,
			"align", "middle",
			"id", swfID,
			"quality", "high",
			"bgcolor", swfBackgroundColor,
			"name", swfID,
			"allowFullScreen","true",
			"allowScriptAccess","always",
			"type", "application/x-shockwave-flash",
			"codebase", "http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab",
			"pluginspage", "http://www.adobe.com/go/getflashplayer"
);
}


// CONTROL FLASH FROM JAVASCRIPT
function RMP_play() {
	getMovieName(swfID).RMP_play(); 
}
function RMP_pause() {
	getMovieName(swfID).RMP_pause(); 
}
function RMP_stop() {
	getMovieName(swfID).RMP_stop(); 
}
function RMP_seek(val) {
	getMovieName(swfID).RMP_seek(val); 
}
function RMP_load(movie,sub,chapters,autostart) {
	getMovieName(swfID).RMP_load(movie,sub,chapters,autostart); 
}
function RMP_loadWithCommercial(commercial,movie,movieTitle,sub,chapters,autostart) {
	getMovieName(swfID).RMP_loadWithCommercial(commercial,movie,movieTitle,sub,chapters,autostart); 
}
function RMP_showSubtitles() {
	getMovieName(swfID).RMP_showSubtitles(); 
}
function RMP_hideSubtitles() {
	getMovieName(swfID).RMP_hideSubtitles(); 
}
function RMP_showControls() {
	getMovieName(swfID).RMP_showControls(); 
}
function RMP_hideControls() {
	getMovieName(swfID).RMP_hideControls(); 
}
function RMP_setVolume(vol) {
	getMovieName(swfID).RMP_setVolume(vol); 
}
function RMP_mute() {
	getMovieName(swfID).RMP_mute(); 
}
function RMP_changeSubtitles(subt) {
	getMovieName(swfID).RMP_changeSubtitles(subt); 
}
function RMP_changeSubVersion(subt) {
	getMovieName(swfID).RMP_changeSubVersion(subt); 
}
function RMP_changeProp(x,y,w,h) {
	getMovieName(swfID).RMP_changeProp(x,y,w,h); 
}
function RMP_changeZoom(zoom) {
	getMovieName(swfID).RMP_changeZoom(zoom); 
}
function RMP_changeVersion(ver) {
	getMovieName(swfID).RMP_changeVersion(ver); 
}
function RMP_close() {
	getMovieName(swfID).RMP_close(); 
}
function RMP_showList() {
	getMovieName(swfID).RMP_showList(); 
}
function RMP_hideList() {
	getMovieName(swfID).RMP_hideList(); 
}
function RMP_previousMedia() {
	getMovieName(swfID).RMP_previousMedia(); 
}
function RMP_nextMedia() {
	getMovieName(swfID).RMP_nextMedia(); 
}
function RMP_playMedia(val) {
	getMovieName(swfID).RMP_playMedia(val); 
}
function RMP_loadMediaList(val,autostart) {
	getMovieName(swfID).RMP_loadMediaList(val,autostart); 
}

function getMovieName(movieName) {
    if (navigator.appName.indexOf("Microsoft") != -1) {
        return window[movieName]
   }
   else {
       return document[movieName]
   }
}


// CREATE THE FLASH PLAYER

createObject();


//-->
</script>';
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>
