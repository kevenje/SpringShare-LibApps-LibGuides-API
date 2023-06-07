<?PHP
//Referring URL in format yoursite.com/libapps-guides.php?type=2&account=1234
//This script outputs a list of libguides by type for one user
//This uses API Endpoints v1.1 - Accounts (Depracated), most current is v1.2
$siteID = 4321; //libguides -> Tools -> API -> Endpoints v1.1 (Deprecated) -> Get Guides
$key = ''; //libguides -> Tools -> API -> Endpoints v1.1 (Deprecated) -> Get Guides
if (preg_match("/^[0-9]+$/", $_GET['type'])) //is the guide type value in the referring URL only a number?
$type = $_GET['type']; //From URL - Available Guide Types from libguides -> Tools -> API -> Endpoints v1.1 (Deprecated) -> Get Guides
else 
$type = 1; // default to general guides
if (preg_match("/^[0-9]+$/", $_GET['account'])) {  //is the account id value in the referring URL only a number?
	$accountID = $_GET['account']; //From URL - libapps -> Admin -> Manage Accounts
	$guidesrow='';
	$libguidesurl = 'https://lgapi-us.libapps.com/1.1/guides?account_ids='.$accountID.'&status=1&guide_types='.$type.'&sort_by=name&site_id='.$siteID.'&key='.$key.'&expand=profile';
	$libguidesjson = file_get_contents($libguidesurl, false);
	if($libguidesjson !== false && !empty($libguidesjson)) {	// the api is not empty
		$libguidesresults = json_decode($libguidesjson, true);
		if ($libguidesresults !== null) { // check for json errors
		$libguidestest = $libguidesresults[0]['name']; //does name exist?
			if($libguidestest !=''){ //if name is not blank output results
				echo '<div class="row">';
				echo '<div class="col-sm-12">';	
				echo '<div class="card"><div class="card-header"><strong>';
				if($type == 2) 
					echo 'Course Related Guides';
				elseif($type == 3) 
					echo 'Guides by Topic';
				else 
					echo 'Liaison Subjects';
				echo '</strong></div><ul class="card-text">';
			if(!is_array($libguidesresults)) { //not an array output one result
				$libguide = $libguidesresults[0]['name'];
				$libguidesurl = $libguidesresults[0]['url'];
				$guidesrow .= '<a target="_new" href="'.$libguidesurl.'" target="_new">';
				$guidesrow .=  $libguide;
				$guidesrow .=  '</a></div>';
			}
			else
			{
				foreach($libguidesresults as $libguidesrow) //more than one result in an array, output results
					{
					$libguides = $libguidesrow['name'];
					$libguidesurl = $libguidesrow['url'];
					$guidesrow .=  '<a target="_new" href="'.$libguidesurl.'" target="_new">';
					$guidesrow .=  $libguides;
					$guidesrow .=  '</a>, ';
					}
			}
		echo $guides = substr($guidesrow, 0, -2).'</div>'; //list guides, but remove the comma and space from the last result
			}
		echo '</div></div>';
		echo '</div>'; // end row
		}	
	}
} 
?>
