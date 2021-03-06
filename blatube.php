<?php
	/* Blatube - Script to retrive data about London Underground Tube statuses.*/

	//Orginal Author: Phil Burton <philbeansburton@gmail.com>

	// Version History
	// 0.0.1 Created blatube.php 
	// 0.0.2 Spelt Northern correctly, Added summary.
	// 0.0.3 Added Bakerloo.	
	// 0.0.4 Spelling mistake Be != Ba
	// 0.1.0 Fixed Summary. Feature complete.
	// 0.1.1 Added -m to give link to tube map	
	// 0.1.2 Added -u for status page URL

	$version = "BlaTube Version: 0.1.2";

	// get the input
	$stdin = read_stdin();
	
	$stdin = explode(" ", strtolower($stdin));
	$out = "";
	$token = "";
	for ($i=0 ; $i<sizeof($stdin); $i++)
	{
		switch ($stdin[$i])
		{
			// Give the URL tot he status page
			case "-u":
				echo "Status page: http://cloud.tfl.gov.uk/TrackerNet/LineStatus\n";
				break;
			// Give link to tube map pdf file
			case "-m":
				echo "Tube Map: http://www.tfl.gov.uk/assets/downloads/standard-tube-map.pdf\n";
				break;
			// List the Underground Lines
			case "-l":
				echo "Bakerloo (Ba) - Central (Ce) - Circle (Ci) - District (Di) - Hammersmith and City (Ha) - Jubilee (Ju) - Metropolitan (Me) - Northern (No) - Piccadilly (Pi) - Victoria (Vi) - Waterloo and City (Wa) - Overground (Ov) - DLR\n";
				break;
			// Version information
			case "-v":
				echo $version . "\n";
				break;
			// Help
			case "-h":
				echo "-l lists tube lines. -v shows version information -<TubeLine> will display information for that line. -s will display summary of lines (-s is run by default if no other options are chosen. -h Displays this help message. -m to see tube map (link to PDF). -u displays status page URL.\n";
				break;
			// Specfic Line information
			case "bakerloo":
			case "ba":
				$array = populateTubes()[0];
				echo $array[0] . ": " . $array[1] . " - " . $array[2] . " " . $array[3] . "\n";
				break;
			case "central":
			case "ce":
				$array = populateTubes()[1];
				echo $array[0] . ": " . $array[1] . " - " . $array[2] . " " . $array[3] . "\n";
				break;
			case "circle":
			case "ci":
				$array = populateTubes()[2];
				echo $array[0] . ": " . $array[1] . " - " . $array[2] . " " . $array[3] . "\n";
				break;
			case "district":
			case "di":
				$array = populateTubes()[3];
				echo $array[0] . ": " . $array[1] . " - " . $array[2] . " " . $array[3] . "\n";
				break;
			case "hammersmith":
			case "ha":
				$array = populateTubes()[4];
				echo $array[0] . ": " . $array[1] . " - " . $array[2] . " " . $array[3] . "\n";
				break;
			case "jubilee":
			case "ju":
				$array = populateTubes()[5];
				echo $array[0] . ": " . $array[1] . " - " . $array[2] . " " . $array[3] . "\n";
				break;
			case "metropolitan":
			case "me":
				$array = populateTubes()[6];
				echo $array[0] . ": " . $array[1] . " - " . $array[2] . " " . $array[3] . "\n";
				break;
			case "northern":
			case "no":
				$array = populateTubes()[7];
				echo $array[0] . ": " . $array[1] . " - " . $array[2] . " " . $array[3] . "\n";
				break;
			case "piccadilly":
			case "pi":
				$array = populateTubes()[8];
				echo $array[0] . ": " . $array[1] . " - " . $array[2] . " " . $array[3] . "\n";
				break;
			case "victoria":
			case "vi":
				$array = populateTubes()[9];
				echo $array[0] . ": " . $array[1] . " - " . $array[2] . " " . $array[3] . "\n";
				break;
			case "waterloo":
			case "wa":
				$array = populateTubes()[10];
				echo $array[0] . ": " . $array[1] . " - " . $array[2] . " " . $array[3] . "\n";
				break;
			case "overground":
			case "ov":
				$array = populateTubes()[11];
				echo $array[0] . ": " . $array[1] . " - " . $array[2] . " " . $array[3] . "\n";
				break;
			case "dlr":
				$array = populateTubes()[12];
				echo $array[0] . ": " . $array[1] . " - " . $array[2] . " " . $array[3] . "\n";
				break;
			// Summary of Line info
			case "-s":
			default:
				$array = populateTubes();
				$out = "";
				foreach($array as $element)
				{
					if($element[3] != "")
					{
						$out .= $element[0] . ": " . $element[1] . " - " . $element[2] . " " . $element[3] . "\n";
					}
				}
				if($out == "")
				{
					$out = "Good service on all lines\n";
				}
				echo $out;
				break;
		}
	}

	function populateTubes()
	{
		$stuff = file_get_contents("http://cloud.tfl.gov.uk/TrackerNet/LineStatus");
		$stuff = explode("<LineStatus",$stuff);
		
		// Create array to hold all tube information
		$tubes = array();

		$j = 0;
		foreach($stuff as $line)
		{
			if($j>0)
			{
				$line = explode("\"",$line);			
				
				$details = $line[3];
				$name = $line[7];
				if($line[13] != "")
				{ 
					$desc = "- " . $line[13];
				}
				if($line[15] == "true")
				{ 
					$active = "Running";	
				}
				else
				{
					$active = "Not Running";
				}
				// Create array for specfic Line
				$tube = array($name, $desc, $active, $details);
		
				// Add Line info to tubes array.
				$tubes[$j-1] = $tube;
			}
			$j++;
		}
		return $tubes;
	}

	function read_stdin()
	{
        	$fr=fopen("php://stdin","r");   // open our file pointer to read from stdin
	        $input = fgets($fr,128);        // read a maximum of 128 characters
	        $input = rtrim($input);         // trim any trailing spaces.
        	fclose ($fr);                   // close the file handle
	        return $input;                  // return the text entered
	}

?>
